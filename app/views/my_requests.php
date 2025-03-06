<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: selling.php");
    exit();
}

define('ALLOW_ACCESS', true);

require_once '../config/conn.php';

$conn->query("
    CREATE TABLE IF NOT EXISTS chat_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        request_id INT DEFAULT NULL,
        message TEXT,
        attachment_path VARCHAR(255) DEFAULT NULL,
        is_bot BOOLEAN DEFAULT 0,
        is_read BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )
");

// Current user
$user_id = $_SESSION['user_id'];

/*******************************************
 * AJAX REQUESTS
 *******************************************/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = isset($_POST['request_id']) ? (int)$_POST['request_id'] : 0;

    try {
        // (A) Poll new messages
        if (isset($_POST['get_messages'])) {
            $last_id = (int)$_POST['last_id'];
            $stmt = $conn->prepare("
                SELECT * FROM chat_messages
                WHERE user_id = ? AND request_id = ? AND id > ?
                ORDER BY created_at ASC
            ");
            $stmt->bind_param('iii', $user_id, $request_id, $last_id);
            $stmt->execute();
            $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['status' => 'success', 'messages' => $messages]);
            exit();
        }

        // (B) Clear chat
        if (isset($_POST['clear_chat'])) {
            $stmt = $conn->prepare("
                DELETE FROM chat_messages
                WHERE user_id = ? AND request_id = ?
            ");
            $stmt->bind_param('ii', $user_id, $request_id);
            $stmt->execute();

            echo json_encode(['status' => 'success', 'message' => 'Chat cleared.']);
            exit();
        }

        // (C) Send message
        if (isset($_POST['send_message'])) {
            $messageText = trim($_POST['message'] ?? '');
            $attachmentPath = null;
            $messages = [];

            // 1. Handle file upload if any
            if (!empty($_FILES['attachment']['name'])) {
                $uploadDir = __DIR__ . '/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['attachment']['name']);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
                    // Store relative path
                    $attachmentPath = 'uploads/' . $fileName;
                }
            }

            // 2. Insert user message
            if ($messageText || $attachmentPath) {
                $stmt = $conn->prepare("
            INSERT INTO chat_messages 
                (user_id, request_id, message, attachment_path, is_bot)
            VALUES (?, ?, ?, ?, 0)
        ");
                $stmt->bind_param('iiss', $user_id, $request_id, $messageText, $attachmentPath);
                $stmt->execute();

                // Get inserted user message
                $userMessageId = $conn->insert_id;
                $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE id = ?");
                $stmt->bind_param('i', $userMessageId);
                $stmt->execute();
                $messages[] = $stmt->get_result()->fetch_assoc();
            }

            // 3. Bot response
            $botResponse = processMessage($messageText, $user_id, $request_id, $conn);
            if ($botResponse) {
                $stmt = $conn->prepare("
            INSERT INTO chat_messages
                (user_id, request_id, message, is_bot)
            VALUES (?, ?, ?, 1)
        ");
                $stmt->bind_param('iis', $user_id, $request_id, $botResponse);
                $stmt->execute();

                // Get inserted bot message
                $botMessageId = $conn->insert_id;
                $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE id = ?");
                $stmt->bind_param('i', $botMessageId);
                $stmt->execute();
                $messages[] = $stmt->get_result()->fetch_assoc();
            }

            echo json_encode(['status' => 'success', 'messages' => $messages]);
            exit();
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit();
    }
}

/*******************************************
 * Enhanced BOT LOGIC with Watch Knowledge Base
 *******************************************/


function processMessage($message, $user_id, $request_id, $conn)
{
    $messageLower = strtolower($message);

    // Example: if user references "request #123"
    if (preg_match('/request #(\d+)/i', $message, $matches)) {
        $reqNum = (int)$matches[1];
        $stmt = $conn->prepare("
            SELECT status FROM requestss
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bind_param('ii', $reqNum, $user_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? "Request #$reqNum status: " . $res['status'] : "Request #$reqNum not found.";
    }

    // Basic FAQ
    $faq = [
        'evaluation' => [
            'keywords' => ['evaluation', 'time', 'long', 'how long'],
            'response' => 'Evaluation takes 3-5 business days.'
        ],
        'documents' => [
            'keywords' => ['document', 'paper', 'need', 'required'],
            'response' => 'Please provide watch photos, purchase receipt, and valid ID.'
        ],
        'update' => [
            'keywords' => ['update', 'change', 'modify'],
            'response' => 'To update your request, please contact support@celestialwatches.com.'
        ],
    ];
    foreach ($faq as $entry) {
        foreach ($entry['keywords'] as $kw) {
            if (strpos($messageLower, $kw) !== false) {
                return $entry['response'];
            }
        }
    }

    // Default fallback
    return $message ? "Thanks for your message. We'll connect you with an agent soon." : "";
}

/*******************************************
 * FETCH REQUESTS & CHAT
 *******************************************/
// 1) Fetch user requests
$stmt = $conn->prepare("
    SELECT * FROM requestss
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 2) Determine current request ID
$currentRequestId = 0;
if (isset($_GET['request_id'])) {
    $currentRequestId = (int)$_GET['request_id'];
} elseif (!empty($requests)) {
    $currentRequestId = (int)$requests[0]['id'];
}

// 3) Fetch chat for the current request
$chatHistory = [];
if ($currentRequestId) {
    $stmt = $conn->prepare("
        SELECT * FROM chat_messages
        WHERE user_id = ? AND request_id = ?
        ORDER BY created_at ASC
    ");
    $stmt->bind_param('ii', $user_id, $currentRequestId);
    $stmt->execute();
    $chatHistory = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Celestial Watches - My Stock Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ICONS & FONTS -->
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- JS -->
    <script src="/src/assets/js/navigation.js" async></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="/src/assets/css/deskView.css">
    <link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/src/assets/css/google-header.css">
    <link rel="stylesheet" href="assets/css/stock-request.css">

</head>

<body>

    <?php include '../../PHP/components/navbar.php'; ?>
    <div class="my-account-container">
        <!-- LEFT SIDEBAR -->
        <aside class="sidebar">
            <h2>My Account</h2>
            <ul>
                <li><a href="my_requests.php" class="active">My Stock Requests</a></li>
                <li><a href="user-dashboard.php">Personal Details</a></li>
                <li><a href="user-dashboard.php">Settings</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- REQUESTS SECTION -->
            <div class="requests-section">
                <div class="requests-header">
                    <i class="ri-mail-fill"></i>
                    <span>My Requests (<?= count($requests) ?>)</span>
                </div>

                <div class="request-list">
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $r): ?>
                            <?php
                            $reqId = (int)$r['id'];
                            $activeClass = ($reqId === $currentRequestId) ? 'style="border-color:#007bff;"' : '';
                            ?>
                            <div class="request-card" <?= $activeClass ?>>
                                <div class="request-badge">Request</div>
                                <div class="request-number">
                                    Request No. <?= htmlspecialchars($r['id']) ?>
                                </div>
                                <div class="request-watch-title">
                                    <?= htmlspecialchars($r['exchange_watch']) ?>
                                </div>
                                <div class="request-ref">
                                    <?php if (!empty($r['buy_watch'])): ?>
                                        <?= htmlspecialchars($r['buy_watch']) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="request-price">
                                    Price: <?= !empty($r['expected_price']) ? htmlspecialchars($r['expected_price']) : "On Request"; ?>
                                </div>
                                <!-- New: Display Request Status -->
                                <div class="request-status request-price">
                                    Status: <?= htmlspecialchars($r['status']) ?>
                                </div>
                                <span class="request-details-toggle" onclick="toggleDetails(this)">
                                    Show Details
                                </span>
                                <div class="extra-details">
                                    <p>Condition: <?= htmlspecialchars($r['request_condition']) ?></p>
                                    <p>Original Box: <?= htmlspecialchars($r['original_box']) ?></p>
                                    <p>Unworn with Stickers: <?= htmlspecialchars($r['unworn']) ?></p>
                                    <p>Original Papers: <?= htmlspecialchars($r['original_papers']) ?></p>
                                    <p>Purchased from Celestial Watches: <?= htmlspecialchars($r['purchased_from_ww']) ?></p>


                                    <!-- Feedback Section (Hidden by default) -->
                                    <div id="feedbackSection" style="display: none; margin-top: 20px; background:#e9ffe9; padding:10px;">
                                        <h3>Rate Your Experience</h3>
                                        <textarea id="feedbackText" placeholder="Leave your feedback..." rows="4" style="width:100%;"></textarea>
                                        <button onclick="submitFeedback()" style="margin-top:10px;">Submit Feedback</button>
                                    </div>
                                </div>
                                <!-- Link to open chat for this request -->
                                <a href="?request_id=<?= $r['id'] ?>" style="margin-top:10px; display:inline-block; font-size:13px; color:#007bff;">
                                    Open Chat
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No requests found.</p>
                    <?php endif; ?>
                </div>
            </div>


            <!-- CHAT SECTION (for $currentRequestId) -->
            <div class="chat-section">
                <?php if ($currentRequestId): ?>
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <span>Celestial Bot (Request #<?= $currentRequestId ?>)</span>
                        <button class="clear-chat-btn" onclick="clearChat()">Clear Chat</button>
                    </div>

                    <!-- Typing Indicator -->
                    <div class="typing-indicator" id="typingIndicator">
                        <span>Bot is typing</span>
                        <div class="typing-dots">
                            <div class="typing-dot" style="animation-delay: 0s"></div>
                            <div class="typing-dot" style="animation-delay: 0.2s"></div>
                            <div class="typing-dot" style="animation-delay: 0.4s"></div>
                        </div>
                    </div>

                    <!-- Chat messages container -->
                    <div class="chat-messages" id="chatMessages">
                        <?php foreach ($chatHistory as $msg): ?>
                            <div class="message <?= $msg['is_bot'] ? 'bot-message' : 'user-message' ?>">
                                <?php if (!empty($msg['message'])): ?>
                                    <p><?= htmlspecialchars($msg['message']) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($msg['attachment_path'])):
                                    $ext = strtolower(pathinfo($msg['attachment_path'], PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                ?>
                                    <?php if ($isImage): ?>
                                        <img src="<?= htmlspecialchars($msg['attachment_path']) ?>"
                                            alt="Attachment"
                                            class="attachment-thumb"
                                            onclick="openFullscreenModal('<?= htmlspecialchars($msg['attachment_path']) ?>')">
                                    <?php else: ?>
                                        <a href="<?= htmlspecialchars($msg['attachment_path']) ?>" target="_blank">
                                            Download File
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="timestamp">
                                    <?= date('H:i', strtotime($msg['created_at'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Quick Questions -->
                    <div class="quick-questions">
                        <button class="quick-question" onclick="askQuestion('What is my latest request status?')">Request Status</button>
                        <button class="quick-question" onclick="askQuestion('How long does evaluation take?')">Evaluation Time</button>
                        <button class="quick-question" onclick="askQuestion('What documents do I need?')">Required Documents</button>
                        <button class="quick-question" onclick="askQuestion('How to update my request?')">Update Request</button>
                    </div>

                    <!-- Chat Input (with bigger file preview) -->
                    <div class="chat-input-container">
                        <!-- Hidden file input -->
                        <input type="file" id="attachmentInput" accept="image/*" onchange="previewFile(event)" />
                        <!-- Attach icon triggers file input -->
                        <ion-icon name="attach-outline" class="attach-btn" onclick="document.getElementById('attachmentInput').click();"></ion-icon>

                        <!-- Text input -->
                        <input type="text" id="userInput" placeholder="Your message..." />

                        <!-- Preview container for images -->
                        <div class="preview-container" id="previewContainer">
                            <img id="previewImage" alt="Preview" onclick="openFullscreenModalFromPreview()" />
                            <ion-icon name="close-circle" class="remove-preview" onclick="removePreview()"></ion-icon>
                        </div>

                        <!-- Send button -->
                        <button class="send-btn" onclick="sendMessage()">
                            <ion-icon name="send"></ion-icon>
                        </button>
                    </div>
                <?php else: ?>
                    <div style="padding:20px;">Please select a request to view the chat.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Full-screen modal for viewing images -->
    <div class="fullscreen-modal" id="fullscreenModal" onclick="closeFullscreenModal()">
        <img id="fullscreenModalImg" alt="Full Screen Preview" />
    </div>

    <?php include '../../PHP/components/footer.php'; ?>

    <script>
        /*********************************************
         * TOGGLE REQUEST DETAILS
         *********************************************/
        function toggleDetails(el) {
            const extra = el.parentElement.querySelector('.extra-details');
            if (!extra) return;
            extra.style.display = (extra.style.display === 'block') ? 'none' : 'block';
            el.textContent = (extra.style.display === 'block') ? 'Hide Details' : 'Show Details';
        }

        /*********************************************
         * IMAGE PREVIEW + FULL-SCREEN MODAL
         *********************************************/
        function previewFile(e) {
            const file = e.target.files[0];
            if (!file) return;
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            previewContainer.style.display = 'flex';
            previewImage.src = URL.createObjectURL(file);
        }

        function removePreview() {
            const fileInput = document.getElementById('attachmentInput');
            fileInput.value = '';
            const previewContainer = document.getElementById('previewContainer');
            previewContainer.style.display = 'none';
        }

        // Full-screen from the preview
        function openFullscreenModalFromPreview() {
            const previewImage = document.getElementById('previewImage');
            openFullscreenModal(previewImage.src);
        }

        // Full-screen from chat image
        function openFullscreenModal(imgSrc) {
            const modal = document.getElementById('fullscreenModal');
            const modalImg = document.getElementById('fullscreenModalImg');
            modalImg.src = imgSrc;
            modal.style.display = 'flex';
        }

        function closeFullscreenModal() {
            document.getElementById('fullscreenModal').style.display = 'none';
        }

        /*********************************************
         * CHAT LOGIC
         *********************************************/
        const typingIndicator = document.getElementById('typingIndicator');
        const requestId = <?= (int)$currentRequestId ?>;

        let chatLock = false; // Prevent simultaneous sends
        let lastMessageId = <?= ($chatHistory && count($chatHistory)) ? end($chatHistory)['id'] : 0 ?>;


        function addMessageElement(msg) {
            const chatMessages = document.getElementById('chatMessages');
            const isUser = !msg.is_bot;

            const wrapper = document.createElement('div');
            wrapper.className = 'message ' + (isUser ? 'user-message' : 'bot-message');

            // If there's text
            if (msg.message) {
                const p = document.createElement('p');
                p.innerText = msg.message;
                wrapper.appendChild(p);
            }

            // If there's an attachment
            if (msg.attachment_path) {
                const ext = msg.attachment_path.split('.').pop().toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
                if (isImage) {
                    const img = document.createElement('img');
                    img.src = msg.attachment_path;
                    img.className = 'attachment-thumb';
                    img.onclick = () => openFullscreenModal(msg.attachment_path);
                    wrapper.appendChild(img);
                } else {
                    const a = document.createElement('a');
                    a.href = msg.attachment_path;
                    a.target = '_blank';
                    a.innerText = 'Download File';
                    wrapper.appendChild(a);
                }
            }

            // Timestamp
            const timeDiv = document.createElement('div');
            timeDiv.className = 'timestamp';
            const dateObj = new Date(msg.created_at);
            timeDiv.innerText = dateObj.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            wrapper.appendChild(timeDiv);

            chatMessages.appendChild(wrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Clear entire chat
        function clearChat() {
            if (!confirm('Are you sure you want to clear all chat messages?')) return;
            fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `clear_chat=1&request_id=${requestId}`
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('chatMessages').innerHTML = '';
                        lastMessageId = 0;
                    }
                })
                .catch(err => console.error(err));
        }

        async function sendMessage() {
            if (chatLock || !requestId) return;
            chatLock = true;

            const userInput = document.getElementById('userInput');
            const message = userInput.value.trim();
            const fileInput = document.getElementById('attachmentInput');
            const file = fileInput.files[0];

            if (!message && !file) {
                chatLock = false;
                return;
            }

            // Show typing indicator
            typingIndicator.style.display = 'flex';

            try {
                const formData = new FormData();
                formData.append('send_message', '1');
                formData.append('request_id', requestId.toString());
                formData.append('message', message);
                if (file) formData.append('attachment', file);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                if (data.status === 'success') {
                    // Add server-generated messages
                    if (data.messages && data.messages.length) {
                        data.messages.forEach(msg => {
                            addMessageElement(msg);
                            lastMessageId = Math.max(lastMessageId, msg.id);
                        });
                    }
                    userInput.value = '';
                    removePreview();
                }
            } catch (error) {
                console.error('Send error:', error);
            } finally {
                chatLock = false;
                typingIndicator.style.display = 'none';
            }
        }

        // Enhanced Polling with Backoff
        let pollTimeout;
        async function pollMessages() {
            if (!requestId) return;

            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `get_messages=1&request_id=${requestId}&last_id=${lastMessageId}`
                });

                const data = await response.json();
                if (data.status === 'success' && data.messages?.length) {
                    data.messages.forEach(msg => {
                        addMessageElement(msg);
                        lastMessageId = Math.max(lastMessageId, msg.id);
                    });
                }
            } catch (error) {
                console.error('Poll error:', error);
            } finally {
                // Adaptive polling with backoff
                const delay = data?.messages?.length ? 1000 : 3000;
                pollTimeout = setTimeout(pollMessages, delay);
            }
        }

        // Initialize polling
        if (requestId) {
            pollMessages();
        }

        // Cleanup polling when leaving
        window.addEventListener('beforeunload', () => {
            clearTimeout(pollTimeout);
        });


        // Quick question shortcuts
        function askQuestion(q) {
            document.getElementById('userInput').value = q;
            sendMessage();
        }

        // Press Enter to send
        const userInputEl = document.getElementById('userInput');
        if (userInputEl) {
            userInputEl.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        }

        // Start polling if request is valid
        if (requestId) {
            setTimeout(pollMessages, 3000);
        }

        // Polling for request status updates every 5 seconds
        async function pollRequestStatus() {
            if (!requestId) return;
            try {
                const response = await fetch('chat/get_request_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `request_id=${requestId}`
                });
                const data = await response.json();
                if (data.status === 'success') {
                    document.getElementById('requestStatusBanner').innerText = `Current Status: ${data.request_status}`;

                    // If the request is completed, show the feedback form.
                    if (data.request_status.toLowerCase() === 'completed') {
                        document.getElementById('feedbackSection').style.display = 'block';
                    }
                }
            } catch (error) {
                console.error("Error polling request status:", error);
            }
            setTimeout(pollRequestStatus, 5000);
        }

        if (requestId) {
            pollRequestStatus();
        }

        // Feedback submission
        async function submitFeedback() {
            const feedback = document.getElementById('feedbackText').value.trim();
            if (!feedback) {
                alert("Please enter your feedback.");
                return;
            }
            try {
                const response = await fetch('chat/submit_feedback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `request_id=${requestId}&feedback=${encodeURIComponent(feedback)}`
                });
                const data = await response.json();
                if (data.status === 'success') {
                    alert("Thank you for your feedback!");
                    // Hide the feedback form after submission
                    document.getElementById('feedbackSection').style.display = 'none';
                } else {
                    alert("Failed to submit feedback.");
                }
            } catch (error) {
                console.error("Feedback error:", error);
            }
        }
    </script>

    <!-- Additional JS Files -->
    <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
    <script src="/src/assets/js/index.js" async></script>
    <script src="/src/assets/js/currency-language.js" async></script>
    <script src="/src/assets/js/cookie-monitor.js" async></script>
    <script src="/src/assets/js/imagePreview.js" async></script>
</body>

</html>
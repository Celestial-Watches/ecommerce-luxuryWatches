<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /selling.php");
    exit();
}

define('ALLOW_ACCESS', true);
require_once '../config/conn.php';

$user_id = $_SESSION['user_id'];

// Optionally, determine the current request for chat context.
// You can pass the request ID via URL parameter, or set a default.
$currentRequestId = isset($_GET['request_id']) ? (int)$_GET['request_id'] : 0;

// Fetch existing chat history (optional if you want to preload)
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
    <title>Chat Widget</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include your CSS libraries as needed -->
    <link rel="stylesheet" href="/src/assets/css/deskView.css">
    <link rel="stylesheet" href="/src/assets/css/stock-request.css">
    <!-- Icons & Fonts -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>
    <style>
        /* Custom styles for chat widget */
        .chat-widget-container { border: 1px solid #ccc; padding: 10px; max-width: 500px; margin: 0 auto; }
        .chat-header { display: flex; justify-content: space-between; align-items: center; }
        .chat-messages { height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 5px; margin-top: 10px; }
        .message { margin-bottom: 10px; }
        .user-message { text-align: right; }
        .bot-message { text-align: left; }
        .timestamp { font-size: 0.8em; color: #777; }
        .typing-indicator { display: none; }
    </style>
</head>
<body>
<div class="chat-widget-container">
    <div class="chat-header">
        <span>Celestial Bot (Request #<?= $currentRequestId ?>)</span>
        <button onclick="clearChat()">Clear Chat</button>
    </div>
    <!-- Typing indicator -->
    <div class="typing-indicator" id="typingIndicator">
        <span>Bot is typing</span>
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
                        <img src="<?= htmlspecialchars($msg['attachment_path']) ?>" alt="Attachment" style="max-width:100px;" onclick="openFullscreenModal('<?= htmlspecialchars($msg['attachment_path']) ?>')">
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($msg['attachment_path']) ?>" target="_blank">Download File</a>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="timestamp"><?= date('H:i', strtotime($msg['created_at'])) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Chat Input -->
    <div class="chat-input-container" style="margin-top:10px;">
        <input type="file" id="attachmentInput" accept="image/*" style="display:none;" onchange="previewFile(event)" />
        <ion-icon name="attach-outline" class="attach-btn" onclick="document.getElementById('attachmentInput').click();"></ion-icon>
        <input type="text" id="userInput" placeholder="Your message..." style="width:70%;" />
        <button onclick="sendMessage()">Send</button>
    </div>
    <!-- Preview Container for image attachment -->
    <div id="previewContainer" style="display:none;">
        <img id="previewImage" alt="Preview" style="max-width:100px;" onclick="openFullscreenModalFromPreview()">
        <button onclick="removePreview()">Remove</button>
    </div>
</div>

<!-- Full-screen modal for image preview -->
<div id="fullscreenModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.8); align-items:center; justify-content:center;" onclick="closeFullscreenModal()">
    <img id="fullscreenModalImg" alt="Full Screen Preview" style="max-width:90%; max-height:90%;">
</div>

<script>
    // Image preview and full-screen modal functions
    function previewFile(e) {
        const file = e.target.files[0];
        if (!file) return;
        document.getElementById('previewContainer').style.display = 'block';
        document.getElementById('previewImage').src = URL.createObjectURL(file);
    }
    function removePreview() {
        document.getElementById('attachmentInput').value = '';
        document.getElementById('previewContainer').style.display = 'none';
    }
    function openFullscreenModalFromPreview() {
        openFullscreenModal(document.getElementById('previewImage').src);
    }
    function openFullscreenModal(imgSrc) {
        document.getElementById('fullscreenModalImg').src = imgSrc;
        document.getElementById('fullscreenModal').style.display = 'flex';
    }
    function closeFullscreenModal() {
        document.getElementById('fullscreenModal').style.display = 'none';
    }

    /*********************************************
     * CHAT LOGIC
     *********************************************/
    const typingIndicator = document.getElementById('typingIndicator');
    const requestId = <?= (int)$currentRequestId ?>;
    let chatLock = false;
    let lastMessageId = <?= ($chatHistory && count($chatHistory)) ? end($chatHistory)['id'] : 0 ?>;

    function addMessageElement(msg) {
        const chatMessages = document.getElementById('chatMessages');
        const isUser = !msg.is_bot;
        const wrapper = document.createElement('div');
        wrapper.className = 'message ' + (isUser ? 'user-message' : 'bot-message');

        if (msg.message) {
            const p = document.createElement('p');
            p.innerText = msg.message;
            wrapper.appendChild(p);
        }
        if (msg.attachment_path) {
            const ext = msg.attachment_path.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
            if (isImage) {
                const img = document.createElement('img');
                img.src = msg.attachment_path;
                img.style.maxWidth = '100px';
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
        const timeDiv = document.createElement('div');
        timeDiv.className = 'timestamp';
        timeDiv.innerText = new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
        wrapper.appendChild(timeDiv);
        chatMessages.appendChild(wrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;
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
        typingIndicator.style.display = 'block';
        try {
            const formData = new FormData();
            formData.append('send_message', '1');
            formData.append('request_id', requestId);
            formData.append('message', message);
            if (file) formData.append('attachment', file);
            const response = await fetch('<?= '/chat/api.php' ?>', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.status === 'success' && data.messages) {
                data.messages.forEach(msg => {
                    addMessageElement(msg);
                    lastMessageId = Math.max(lastMessageId, msg.id);
                });
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

    async function pollMessages() {
        if (!requestId) return;
        try {
            const response = await fetch('<?= '/chat/api.php' ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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
            setTimeout(pollMessages, 3000);
        }
    }

    function clearChat() {
        if (!confirm('Clear all chat messages?')) return;
        fetch('<?= '/chat/api.php' ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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

    document.getElementById('userInput').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    if (requestId) pollMessages();
</script>
</body>
</html>

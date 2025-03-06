<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
    exit();
}

require_once '../config/conn.php';

// Create chat_messages table if not exists
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

$user_id = $_SESSION['user_id'];

// Process AJAX requests
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

            // Handle file upload if any
            if (!empty($_FILES['attachment']['name'])) {
                $uploadDir = __DIR__ . '/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['attachment']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
                    // Store relative path (adjust if needed)
                    $attachmentPath = 'uploads/' . $fileName;
                }
            }

            // Insert user message
            if ($messageText || $attachmentPath) {
                $stmt = $conn->prepare("
                    INSERT INTO chat_messages 
                        (user_id, request_id, message, attachment_path, is_bot)
                    VALUES (?, ?, ?, ?, 0)
                ");
                $stmt->bind_param('iiss', $user_id, $request_id, $messageText, $attachmentPath);
                $stmt->execute();
                $userMessageId = $conn->insert_id;
                $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE id = ?");
                $stmt->bind_param('i', $userMessageId);
                $stmt->execute();
                $messages[] = $stmt->get_result()->fetch_assoc();
            }

            // Bot response (using your bot logic)
            $botResponse = processMessage($messageText, $user_id, $request_id, $conn);
            if ($botResponse) {
                $stmt = $conn->prepare("
                    INSERT INTO chat_messages
                        (user_id, request_id, message, is_bot)
                    VALUES (?, ?, ?, 1)
                ");
                $stmt->bind_param('iis', $user_id, $request_id, $botResponse);
                $stmt->execute();
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

// Bot logic function
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
?>

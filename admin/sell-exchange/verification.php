<?php
// verification.php
session_start();

require '/MarwadiUniversity/Semester5/PROJECT/app/config/conn.php';  
 

// --- Function to Log Verification Actions ---
function logVerification($watchId, $action, $notes)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO verification_logs (watch_id, action, notes, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$watchId, $action, $notes]);
}

// --- Handle Verification Actions ---
$message = "";
if (!empty($_POST['action']) && !empty($_POST['watch_id'])) {
    $watchId = (int) $_POST['watch_id'];
    $notes   = trim($_POST['notes'] ?? '');

    if ($_POST['action'] === 'approve') {
        $stmt = $conn->prepare("UPDATE watches SET verified = 1 WHERE id = ?");
        $stmt->execute([$watchId]);
        logVerification($watchId, 'approved', $notes);
        $message = "Watch ID {$watchId} approved.";
    } elseif ($_POST['action'] === 'reject') {
        // You can choose to set a different status (e.g., 2 for rejected)
        $stmt = $conn->prepare("UPDATE watches SET verified = 2 WHERE id = ?");
        $stmt->execute([$watchId]);
        logVerification($watchId, 'rejected', $notes);
        $message = "Watch ID {$watchId} rejected.";
    }
}

// --- Fetch Pending Verifications ---
// Assuming pending watches have verified = 0
$stmt = $conn->query("SELECT * FROM watches WHERE verified = 0 ORDER BY created_at ASC");
$pending = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Pending Watch Verifications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .zoom {
            transform: scale(2);
            transition: transform 0.3s;
            z-index: 1000;
            position: relative;
        }

        .img-thumbnail {
            cursor: zoom-in;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-5">
        <h2>Pending Watch Verifications</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="row">
            <?php if (empty($pending)): ?>
                <p>No pending verifications.</p>
            <?php else: ?>
                <?php foreach ($pending as $watch): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($watch['model']) ?></h5>

                                <!-- Image Gallery -->
                                <div class="image-gallery mb-3">
                                    <?php
                                    // Assuming 'images' field stores a JSON-encoded array of URLs
                                    $images = json_decode($watch['images'], true);
                                    if ($images && is_array($images)) :
                                        foreach ($images as $image): ?>
                                            <img src="<?= htmlspecialchars($image) ?>" class="img-thumbnail zoomable" style="max-width: 200px; margin: 5px;">
                                    <?php endforeach;
                                    else:
                                        echo "<p>No images available.</p>";
                                    endif;
                                    ?>
                                </div>

                                <!-- Verification Controls -->
                                <form method="POST">
                                    <input type="hidden" name="watch_id" value="<?= $watch['id'] ?>">

                                    <div class="mb-3">
                                        <textarea name="notes" class="form-control" placeholder="Verification notes"></textarea>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Zoom Implementation -->
    <script>
        document.querySelectorAll('.zoomable').forEach(img => {
            img.addEventListener('click', () => {
                img.classList.toggle('zoom');
            });
        });
    </script>
</body>

</html>
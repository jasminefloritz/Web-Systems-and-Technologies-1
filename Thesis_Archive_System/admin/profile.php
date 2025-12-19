<?php
include("../config/db.php");


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$student = $_SESSION['user'];
$message = '';

function handle_upload($fieldName, $uploadDir, $dbField, $conn, &$student, &$message)
{
    if (!isset($_FILES[$fieldName]) || !is_uploaded_file($_FILES[$fieldName]['tmp_name'])) return;

    $original = $_FILES[$fieldName]['name'];
    $tmp_name = $_FILES[$fieldName]['tmp_name'];


    $safe = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $original);

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $target_file = rtrim($uploadDir, '/') . '/' . $safe;

    if (move_uploaded_file($tmp_name, $target_file)) {
        $escaped = mysqli_real_escape_string($conn, $safe);
        $user_id = (int)$student['user_id'];
        mysqli_query($conn, "UPDATE users SET {$dbField}='{$escaped}' WHERE user_id={$user_id}");
        // update session and local copy
        $_SESSION['user'][$dbField] = $safe;
        $student[$dbField] = $safe;
        $message = ucfirst(str_replace('_', ' ', $dbField)) . ' updated successfully.';
    } else {
        $message = 'Failed to upload file. Please try again.';
    }
}

if (isset($_POST['update_profile'])) {
    $updates = 0;

    $before = [$student['profile_picture'] ?? null, $student['signature'] ?? null];
    handle_upload('profile_picture', '../uploads/profiles', 'profile_picture', $conn, $student, $message);
    if ($student['profile_picture'] !== ($before[0] ?? null)) $updates++;
    $before2 = $student['signature'] ?? null;
    handle_upload('signature', '../uploads/signatures', 'signature', $conn, $student, $message);
    if ($student['signature'] !== ($before2 ?? null)) $updates++;

    if ($updates > 0) {

        $_SESSION['flash_message'] = 'Profile updated successfully.';
        header('Location: dashboard.php');
        exit;
    } else {
        $message = 'No files were uploaded. Please choose files to upload.';
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>My Profile</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>
    <?php include_once(__DIR__ . '/header.php'); ?>
    <main>
        <h1 class="welcome-title">My Profile</h1>

        <?php if ($message): ?>
            <p class="message" style="text-align:center; color:green; font-weight:600; margin-bottom:1rem;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <div class="profile-card">
            <div class="profile-section stacked-profile">
                <div class="profile-item">
                    <h4 class="profile-label">Profile Picture</h4>
                    <?php if (!empty($student['profile_picture'])): ?>
                        <img src="../uploads/profiles/<?= htmlspecialchars($student['profile_picture']); ?>" alt="Profile" class="profile-pic">
                    <?php else: ?>
                        <div class="profile-placeholder">No Profile Picture</div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" class="upload-form">
                        <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
                            <label style="display:flex;flex-direction:column;gap:.5rem;margin:0;">
                                <span style="font-size:.9rem;color:#444;">Profile Picture</span>
                                <input type="file" name="profile_picture" accept="image/*">
                            </label>

                            <label style="display:flex;flex-direction:column;gap:.5rem;margin:0;">
                                <span style="font-size:.9rem;color:#444;">Signature</span>
                                <input type="file" name="signature" accept="image/*">
                            </label>

                            <div style="flex:1 1 100%;text-align:center;margin-top:0.5rem;">
                                <button type="submit" name="update_profile" class="upload-btn">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
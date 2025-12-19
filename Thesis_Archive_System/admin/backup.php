<?php
include("../config/db.php");
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$message = '';

// Backup database
if (isset($_POST['backup'])) {
    $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $backupPath = __DIR__ . '/../backups/' . $backupFile;

    if (!is_dir(__DIR__ . '/../backups/')) {
        mkdir(__DIR__ . '/../backups/', 0777, true);
    }

    // Use mysqldump if available
    $command = "mysqldump --user=root --password= --host=localhost thesis_repository > \"$backupPath\"";
    exec($command, $output, $return);

    if ($return === 0) {
        // Log activity
        mysqli_query($conn, "INSERT INTO activity_logs (user_id, action) VALUES ({$_SESSION['user']['user_id']}, 'Created database backup: $backupFile')");


        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $backupFile . '"');
        header('Content-Length: ' . filesize($backupPath));
        readfile($backupPath);
        unlink($backupPath);
        exit;
    } else {
        $message = 'Backup failed. Ensure mysqldump is available.';
    }
}

// Restore database
if (isset($_POST['restore']) && isset($_FILES['sql_file'])) {
    $file = $_FILES['sql_file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $sql = file_get_contents($file['tmp_name']);
        $queries = array_filter(array_map('trim', explode(';', $sql)));

        $success = true;
        foreach ($queries as $query) {
            if (!empty($query) && !mysqli_query($conn, $query)) {
                $success = false;
                break;
            }
        }

        if ($success) {

            mysqli_query($conn, "INSERT INTO activity_logs (user_id, action) VALUES ({$_SESSION['user']['user_id']}, 'Restored database from file')");
            $message = 'Database restored successfully.';
        } else {
            $message = 'Restore failed: ' . mysqli_error($conn);
        }
    } else {
        $message = 'File upload error.';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Backup & Restore</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <?php include_once(__DIR__ . '/header.php'); ?>

    <main class="admin-container">
        <div class="card">
            <h2>Database Backup & Restore</h2>

            <?php if ($message): ?>
                <p class="message" style="color: <?= strpos($message, 'success') !== false ? 'green' : 'red'; ?>; font-weight:600; margin-bottom:1rem;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <div style="display:flex;gap:2rem;flex-wrap:wrap;">
                <div style="flex:1;min-width:300px;">
                    <h3>Create Backup</h3>
                    <form method="POST">
                        <p>Download a full database backup as SQL file.</p>
                        <button type="submit" name="backup" class="btn btn-primary">Download Backup</button>
                    </form>
                </div>

                <div style="flex:1;min-width:300px;">
                    <h3>Restore Database</h3>
                    <form method="POST" enctype="multipart/form-data">
                        <p>Upload an SQL file to restore the database. <strong>Warning: This will overwrite existing data!</strong></p>
                        <input type="file" name="sql_file" accept=".sql" required>
                        <button type="submit" name="restore" class="btn btn-danger" onclick="return confirm('Are you sure? This will replace all data!')">Restore</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

</body>

</html>
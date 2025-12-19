<?php
include("../config/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    http_response_code(404);
    echo "<p>Thesis not found.</p>";
    exit;
}

// Fetch thesis metadata
$sql = "SELECT t.*, u.full_name AS author, d.department_name, p.program_name
        FROM theses t
        LEFT JOIN users u ON t.author_id=u.user_id
        LEFT JOIN departments d ON t.department_id=d.department_id
        LEFT JOIN programs p ON t.program_id=p.program_id
        WHERE t.thesis_id={$id} LIMIT 1";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    http_response_code(404);
    echo "<p>Thesis not found.</p>";
    exit;
}
$thesis = mysqli_fetch_assoc($res);

// Fetch files
$files = mysqli_query($conn, "SELECT * FROM files WHERE thesis_id={$id}");

?>
<!DOCTYPE html>
<html>

<head>
    <title>View Thesis - <?= htmlspecialchars($thesis['title']) ?></title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .admin-table th,
        .admin-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .admin-table th {
            background-color: #007bff;
            color: #f2f2f2ff;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            background-color: #007bff;
            color: white;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>

<body>
    <?php include_once(__DIR__ . '/header.php'); ?>
    <main class="admin-container">
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
                <div>
                    <h2 style="margin:0;"><?= htmlspecialchars($thesis['title']) ?></h2>
                    <p style="margin:.25rem 0 0 0;color:#6b7280;font-weight:600;">By <?= htmlspecialchars($thesis['author'] ?? 'Unknown') ?></p>
                    <p style="margin:.25rem 0 0 0;color:#6b7280;font-size:.95rem;">Dept: <?= htmlspecialchars($thesis['department_name'] ?? 'Unknown') ?> • Program: <?= htmlspecialchars($thesis['program_name'] ?? 'Unknown') ?></p>
                </div>
                <div style="text-align:right;">
                    <div style="color:#6b7280;font-size:.9rem;margin-bottom:.5rem;">Submitted: <?= htmlspecialchars($thesis['submitted_at'] ?? '') ?></div>
                    <a href="theses.php" class="btn btn-secondary">Back</a>
                </div>
            </div>

            <hr style="margin:1rem 0;">

            <h3 style="margin-top:0;">Files</h3>

            <?php if ($files && mysqli_num_rows($files) > 0): ?>
                <div style="margin-bottom:1rem;">
                    <a href="download_zip.php?id=<?= $id ?>" class="btn btn-primary">Download All Files (ZIP)</a>
                </div>
            <?php endif; ?>

            <?php
            function fmt_size($bytes)
            {
                if (!$bytes || !is_numeric($bytes)) return '-';
                $units = ['B', 'KB', 'MB', 'GB'];
                $i = 0;
                while ($bytes >= 1024 && $i < count($units) - 1) {
                    $bytes /= 1024;
                    $i++;
                }
                return round($bytes, $i ? 1 : 0) . ' ' . $units[$i];
            }

            if ($files && mysqli_num_rows($files) > 0): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>File</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Uploaded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($f = mysqli_fetch_assoc($files)):
                                $filename = $f['file_path'];
                                $disk = __DIR__ . '/../uploads/thesis/' . $filename;
                                $exists = file_exists($disk);
                                $size = ($exists ? filesize($disk) : null);
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($filename) ?></td>
                                    <td><?= htmlspecialchars($f['file_type'] ?? pathinfo($filename, PATHINFO_EXTENSION)) ?></td>
                                    <td><?= $exists ? fmt_size($size) : '<span style="color:#b91c1c;">Missing</span>' ?></td>
                                    <td><?= htmlspecialchars($f['uploaded_at'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($exists): ?>
                                            <a class="btn" href="../uploads/thesis/<?= rawurlencode($filename) ?>" download>Download</a>
                                            <a class="btn btn-primary" href="../uploads/thesis/<?= rawurlencode($filename) ?>" target="_blank">View</a>
                                        <?php else: ?>
                                            <span style="color:#6b7280;">File missing on server</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No files uploaded for this thesis.</p>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_message'])): ?>
                <p style="margin-top:1rem;color:green;font-weight:700;"><?= htmlspecialchars($_SESSION['flash_message']) ?></p>
            <?php unset($_SESSION['flash_message']);
            endif; ?>

        </div>
    </main>
</body>

</html>
<?php
include("../config/db.php");
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$message = '';

// Export users
if (isset($_GET['export']) && $_GET['export'] === 'users') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="users_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Full Name', 'Email', 'Role', 'Department', 'Program', 'Created At']);

    $res = mysqli_query($conn, "SELECT u.*, d.department_name, p.program_name FROM users u LEFT JOIN departments d ON u.department_id=d.department_id LEFT JOIN programs p ON u.program_id=p.program_id");
    while ($row = mysqli_fetch_assoc($res)) {
        fputcsv($output, [$row['user_id'], $row['full_name'], $row['email'], $row['role'], $row['department_name'], $row['program_name'], $row['created_at']]);
    }
    fclose($output);
    exit;
}

// Export theses
if (isset($_GET['export']) && $_GET['export'] === 'theses') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="theses_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Title', 'Author', 'Adviser', 'Department', 'Program', 'Year', 'Status', 'Submitted At']);

    $res = mysqli_query($conn, "SELECT t.*, u.full_name AS author, f.full_name AS adviser, d.department_name, p.program_name FROM theses t LEFT JOIN users u ON t.author_id=u.user_id LEFT JOIN users f ON t.adviser_id=f.user_id LEFT JOIN departments d ON t.department_id=d.department_id LEFT JOIN programs p ON t.program_id=p.program_id");
    while ($row = mysqli_fetch_assoc($res)) {
        fputcsv($output, [$row['thesis_id'], $row['title'], $row['author'], $row['adviser'], $row['department_name'], $row['program_name'], $row['year'], $row['status'], $row['submitted_at']]);
    }
    fclose($output);
    exit;
}

// Export activity logs
if (isset($_GET['export']) && $_GET['export'] === 'logs') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="activity_logs_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'User', 'Action', 'Logged At']);

    $res = mysqli_query($conn, "SELECT a.*, u.full_name FROM activity_logs a LEFT JOIN users u ON a.user_id=u.user_id ORDER BY a.logged_at DESC");
    while ($row = mysqli_fetch_assoc($res)) {
        fputcsv($output, [$row['activity_log_id'], $row['full_name'], $row['action'], $row['logged_at']]);
    }
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Export Reports</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <?php include_once(__DIR__ . '/header.php'); ?>

    <main class="admin-container">
        <div class="card">
            <h2>Export Reports</h2>

            <?php if ($message): ?>
                <p class="message" style="color: green; font-weight:600; margin-bottom:1rem;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <p>Download data as CSV files:</p>
            <ul>
                <li><a href="export.php?export=users" class="btn btn-primary">Export Users</a></li>
                <li><a href="export.php?export=theses" class="btn btn-primary">Export Theses</a></li>
                <li><a href="export.php?export=logs" class="btn btn-primary">Export Activity Logs</a></li>
            </ul>
        </div>
    </main>

</body>

</html>
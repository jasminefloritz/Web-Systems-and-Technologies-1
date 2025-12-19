<?php
include("../config/db.php");


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
    header("Location: ../auth/login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['thesis_id']) ? (int)$_POST['thesis_id'] : 0;
    $action = isset($_POST['decision']) ? $_POST['decision'] : '';
} else {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $action = isset($_GET['action']) ? $_GET['action'] : '';
}

if (!$id || !in_array($action, ['approve', 'reject'])) {
    $_SESSION['flash_message'] = 'Invalid review request.';
    header('Location: dashboard.php');
    exit;
}

$decision = $action === 'approve' ? 'approved' : 'rejected';
$reviewer_id = (int)$_SESSION['user']['user_id'];


$stmt = mysqli_prepare($conn, "INSERT INTO approvals (thesis_id, reviewer_id, decision, decision_date) VALUES (?, ?, ?, NOW())");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iis", $id, $reviewer_id, $decision);
    $ok = mysqli_stmt_execute($stmt);
    if (!$ok) {
        error_log('DB error (faculty/review_thesis.php) - insert approvals: ' . mysqli_stmt_error($stmt));
        $_SESSION['flash_message'] = 'Failed to apply decision. Please try again.';
        header('Location: dashboard.php');
        exit;
    }
    mysqli_stmt_close($stmt);
} else {
    error_log('DB prepare failed (faculty/review_thesis.php): ' . mysqli_error($conn));
    $_SESSION['flash_message'] = 'Failed to apply decision. Please try again.';
    header('Location: dashboard.php');
    exit;
}


$dec_escaped = mysqli_real_escape_string($conn, $decision);
if (!mysqli_query($conn, "UPDATE theses SET status='{$dec_escaped}' WHERE thesis_id={$id}")) {
    error_log('DB error (faculty/review_thesis.php) updating thesis status: ' . mysqli_error($conn));
}


$action_text = "Reviewed thesis #{$id}: {$decision}";
if (!mysqli_query($conn, "INSERT INTO activity_logs (user_id, action, logged_at) VALUES ({$reviewer_id}, '" . mysqli_real_escape_string($conn, $action_text) . "', NOW())")) {

    error_log('DB error (faculty/review_thesis.php) inserting activity log: ' . mysqli_error($conn));
}

$_SESSION['flash_message'] = 'Decision recorded.';
header('Location: dashboard.php');
exit;

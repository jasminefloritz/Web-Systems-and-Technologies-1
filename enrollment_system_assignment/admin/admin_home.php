<?php
include("../config/config.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.dashboard-card {
    border-radius: 12px;
    padding: 30px;
}
</style>
</head>

<body class="container mt-4">

<!-- ================= HEADER ================= -->
<div class="d-flex justify-content-between align-items-center mb-4">

    <h4 class="fw-bold">Admin Dashboard</h4>

    <div class="btn-group">
        <a href="manage_subjects.php" class="btn btn-outline-primary">
            Manage Subjects
        </a>
        <a href="manage_users.php" class="btn btn-outline-primary">
            Manage Users
        </a>
        <a href="profile.php" class="btn btn-outline-primary">
            Profile
        </a>
        <a href="../auth/logout.php" class="btn btn-danger">
            Logout
        </a>
    </div>

</div>

<!-- ================= WELCOME CARD ================= -->
<div class="card shadow-sm dashboard-card">
    <div class="card-body">

        <h3 class="card-title mb-2">
            Welcome, <?= htmlspecialchars($user['name']); ?> 👋
        </h3>

        <p class="text-muted mb-3">
            You are logged in as <strong>Administrator</strong>.
        </p>

        <hr>

        <p class="mb-0">
            Use the buttons above to manage subjects, assign faculty and prerequisites,
            manage users, and update your profile.
        </p>

    </div>
</div>

</body>
</html>

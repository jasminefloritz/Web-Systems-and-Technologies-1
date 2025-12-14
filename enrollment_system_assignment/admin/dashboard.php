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
<link rel="stylesheet" href="../assets/css/dashboard.css">

<style>
.dashboard-card {
    border-radius: 12px;
    padding: 30px;
}
</style>
</head>

<body class="container mt-4">

<div class="main-dashboard">

   <!-- header -->
    <div class="header-dashboard">
        <div class="header-left">
            <img src="../uploads/profiles/<?= $user['profile_pic']; ?>" alt="Profile">
            <h2>Welcome, <?= htmlspecialchars($user['name']); ?> (Admin)</h2>
        </div>

        <div class="header-right">
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <!-- action button -->
    <div class="mb-4">
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
        </div>
    </div>

  <!-- content -->
<div class="card shadow-sm dashboard-card">
    <div class="card-body">

        <h4 class="fw-bold mb-2">Admin Dashboard</h4>
        <p class="text-muted mb-4">
            Manage and oversee the academic system from one place.
        </p>

        <div class="row g-4">

            <!-- Subjects -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm"
                     style="background-color: #dbeafe;">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-2">
                            📘 Subjects Management
                        </h6>
                        <p class="text-muted small mb-0">
                            Create, update, and organize subjects. Assign faculty
                            and manage prerequisites efficiently.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Users -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm"
                     style="background-color: #dbeafe;">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-2">
                            👥 User Management
                        </h6>
                        <p class="text-muted small mb-0">
                            Manage administrator, faculty, and student accounts.
                            Control roles and access permissions.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Profile -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm"
                     style="background-color: #dbeafe;">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-2">
                            ⚙️ Profile Settings
                        </h6>
                        <p class="text-muted small mb-0">
                            Update your personal information, profile photo,
                            and signature records.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <hr class="my-4">

        <p class="text-muted mb-0">
            Use the action buttons above to navigate between sections and
            keep the system data accurate and up to date.
        </p>

    </div>
</div>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

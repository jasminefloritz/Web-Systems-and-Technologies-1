<?php
include("../config/db.php");


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$admin = $_SESSION['user'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #333;
            padding: 1rem;
            color: #fff;
        }

        nav a {
            color: #fff;
            margin-right: 1rem;
            text-decoration: none;
        }

        main {
            padding: 2rem;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 0.5rem;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>

    <?php include_once(__DIR__ . '/header.php'); ?>

    <main>
        <h1>Welcome, <?= htmlspecialchars($admin['full_name']); ?></h1>
        <p>Use the links above to manage the system.</p>



        <?php if (isset($_SESSION['flash_message'])): ?>
            <p class="message" style="text-align:center; color:green; font-weight:600; margin-bottom:1rem;"><?php echo htmlspecialchars($_SESSION['flash_message']);
                                                                                                            unset($_SESSION['flash_message']); ?></p>
        <?php endif; ?>

        <div class="profile-card">
            <div class="profile-section compact-profile">
                <?php if (!empty($admin['profile_picture'])): ?>
                    <img src="../uploads/profiles/<?= htmlspecialchars($admin['profile_picture']); ?>" alt="Profile" class="profile-pic">
                <?php else: ?>
                    <div class="profile-placeholder">No Profile Picture</div>
                <?php endif; ?>

                <?php if (!empty($admin['signature'])): ?>
                    <img src="../uploads/signatures/<?= htmlspecialchars($admin['signature']); ?>" alt="Signature" class="signature-img" style="margin-top:1rem;">
                <?php else: ?>
                    <div class="profile-placeholder" style="margin-top:1rem;">No Signature</div>
                <?php endif; ?>

                <p style="margin-top:1rem;"><a href="profile.php"><button id="toggleProfile" class="upload-btn">Update Profile</button></a></p>
            </div>
        </div>

        <?php

        $user_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
        $user_count = $user_res ? mysqli_fetch_assoc($user_res)['total'] : 0;

        $thesis_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM theses");
        $thesis_count = $thesis_res ? mysqli_fetch_assoc($thesis_res)['total'] : 0;

        $approved_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM approvals WHERE decision='approved'");
        $approved_count = $approved_res ? mysqli_fetch_assoc($approved_res)['total'] : 0;
        ?>

        <h2>System Overview</h2>
        <div class="overview">
            <div class="stat-card">
                <div class="stat-value"><?= $user_count; ?></div>
                <div class="stat-label">Total Users</div>
            </div>

            <div class="stat-card">
                <div class="stat-value"><?= $thesis_count; ?></div>
                <div class="stat-label">Theses Submitted</div>
            </div>

            <div class="stat-card">
                <div class="stat-value"><?= $approved_count; ?></div>
                <div class="stat-label">Theses Approved</div>
            </div>
        </div>


        <h2>Recent Activities</h2>
        <?php
        $logs = mysqli_query($conn, "SELECT a.*, u.full_name FROM activity_logs a LEFT JOIN users u ON a.user_id=u.user_id ORDER BY a.logged_at DESC LIMIT 10");
        ?>
        <table>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Timestamp</th>
            </tr>
            <?php while ($log = mysqli_fetch_assoc($logs)): ?>
                <tr>
                    <td><?= htmlspecialchars($log['full_name'] ?? 'Unknown'); ?></td>
                    <td><?= htmlspecialchars($log['action']); ?></td>
                    <td><?= $log['logged_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>


    </main>

</body>

</html>
<?php
include("../config/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$student = $_SESSION['user'];




?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Dashboard</title>
    <?php include('header.php') ?>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>


    <main>
        <h1 class="welcome-title">Welcome, <?= htmlspecialchars($student['full_name']); ?></h1>
        <h2>Student Dashboard</h2>


        <div class="profile-card">
            <div class="profile-section compact-profile">
                <?php if (!empty($student['profile_picture'])): ?>
                    <img src="../uploads/profiles/<?= htmlspecialchars($student['profile_picture']); ?>" alt="Profile" class="profile-pic">
                <?php else: ?>
                    <div class="profile-placeholder">No Profile Picture</div>
                <?php endif; ?>

                <?php if (!empty($student['signature'])): ?>
                    <img src="../uploads/signatures/<?= htmlspecialchars($student['signature']); ?>" alt="Signature" class="signature-img" style="margin-top:1rem;">
                <?php else: ?>
                    <div class="profile-placeholder" style="margin-top:1rem;">No Signature</div>
                <?php endif; ?>

                <p style="margin-top:1rem;"><a href="profile.php"><button class="upload-btn">Update Profile</button></a></p>
            </div>
        </div>

        <hr>


        <h3>My Thesis Submissions</h3>

        <?php

        $author_id = (int)$student['user_id'];
        $sql = "SELECT t.*, d.department_name, p.program_name,\n    (SELECT decision FROM approvals a WHERE a.thesis_id=t.thesis_id ORDER BY a.decision_date DESC LIMIT 1) AS approval_status\n    FROM theses t\n    LEFT JOIN departments d ON t.department_id = d.department_id\n    LEFT JOIN programs p ON t.program_id = p.program_id\n    WHERE t.author_id = {$author_id}\n    ORDER BY t.thesis_id DESC";
        $theses = mysqli_query($conn, $sql);
        if (!$theses) {
            error_log('DB error (student/dashboard.php): ' . mysqli_error($conn) . ' -- SQL: ' . $sql);
            echo '<p class="error">Unable to load your submissions. Please contact the administrator.</p>';
            $theses = null;
        }
        ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Department</th>
                <th>Program</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php if ($theses): while ($t = mysqli_fetch_assoc($theses)): ?>
                    <tr>
                        <td><?= $t['thesis_id']; ?></td>
                        <td><?= htmlspecialchars($t['title']); ?></td>
                        <td><?= htmlspecialchars($t['department_name'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($t['program_name'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($t['approval_status'] ?? 'Pending'); ?></td>
                        <td>

                            <a href="edit_thesis.php?id=<?= $t['thesis_id']; ?>">Edit</a> |
                            <a href="view_thesis.php?id=<?= $t['thesis_id']; ?>">View Files</a>
                        </td>
                    </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="6">No submissions found.</td>
                </tr>
            <?php endif; ?>
        </table>

        <hr>


    </main>
</body>

</html>
<?php
include("../config/db.php");


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
    header("Location: ../auth/login.php");
    exit;
}

$faculty = &$_SESSION['user'];
$message = '';

function handle_uploaded_file($fieldName, $uploadDir, $conn, &$user, &$msg)
{
    if (!isset($_FILES[$fieldName]) || !is_uploaded_file($_FILES[$fieldName]['tmp_name'])) return null;

    $original = $_FILES[$fieldName]['name'];
    $tmp_name = $_FILES[$fieldName]['tmp_name'];
    $ext = pathinfo($original, PATHINFO_EXTENSION);
    $safe = time() . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', basename($original));

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $target = rtrim($uploadDir, '/') . '/' . $safe;
    if (move_uploaded_file($tmp_name, $target)) {
        return $safe;
    } else {
        $msg = 'Failed to move uploaded file.';
        return null;
    }
}


if (isset($_POST['update_profile'])) {
    $updates = 0;
    $before = [$faculty['profile_picture'] ?? null, $faculty['signature'] ?? null];

    $p = handle_uploaded_file('profile_picture', __DIR__ . '/../uploads/profiles', $conn, $faculty, $message);
    if ($p) {
        $escaped = mysqli_real_escape_string($conn, $p);
        $uid = (int)$faculty['user_id'];
        $res = mysqli_query($conn, "UPDATE users SET profile_picture='{$escaped}' WHERE user_id={$uid}");
        if (!$res) {
            error_log('DB update failed (faculty/dashboard.php profile_picture) user_id=' . $uid . ': ' . mysqli_error($conn));
        }
        $_SESSION['user']['profile_picture'] = $p;
        $faculty['profile_picture'] = $p;
        $updates++;
    }

    $s = handle_uploaded_file('signature', __DIR__ . '/../uploads/signatures', $conn, $faculty, $message);
    if ($s) {
        $escaped = mysqli_real_escape_string($conn, $s);
        $uid = (int)$faculty['user_id'];
        $res = mysqli_query($conn, "UPDATE users SET signature='{$escaped}' WHERE user_id={$uid}");
        if (!$res) {
            error_log('DB update failed (faculty/dashboard.php signature) user_id=' . $uid . ': ' . mysqli_error($conn));
        }
        $_SESSION['user']['signature'] = $s;
        $faculty['signature'] = $s;
        $updates++;
    }

    if ($updates > 0) {
        $_SESSION['flash_message'] = 'Profile updated successfully.';
        header('Location: dashboard.php');
        exit;
    } else {
        $message = $message ?: 'No files selected.';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>
    <?php include_once(__DIR__ . '/header.php'); ?>



    <main>
        <h2>Faculty Dashboard</h2>

        <div class="profile-card">
            <div class="profile-section compact-profile">
                <?php if (!empty($faculty['profile_picture'])):
                    $pp = $faculty['profile_picture'];
                    $pp_path = __DIR__ . '/../uploads/profiles/' . $pp;
                    $pp_ver = (file_exists($pp_path) ? filemtime($pp_path) : time());
                ?>
                    <img src="../uploads/profiles/<?= htmlspecialchars($pp); ?>?v=<?= $pp_ver; ?>" alt="Profile" class="profile-pic">
                <?php else: ?>
                    <div class="profile-placeholder">No Profile Picture</div>
                <?php endif; ?>

                <?php if (!empty($faculty['signature'])):
                    $sig = $faculty['signature'];
                    $sig_path = __DIR__ . '/../uploads/signatures/' . $sig;
                    $sig_ver = (file_exists($sig_path) ? filemtime($sig_path) : time());
                ?>
                    <img src="../uploads/signatures/<?= htmlspecialchars($sig); ?>?v=<?= $sig_ver; ?>" alt="Signature" class="signature-img" style="margin-top:1rem;">
                <?php else: ?>
                    <div class="profile-placeholder" style="margin-top:1rem;">No Signature</div>
                <?php endif; ?>

                <p style="margin-top:1rem;"><a class="btn btn-primary" href="profile.php">Update Profile</a></p>
            </div>
        </div>

        <!-- Thesis Submissions for Review -->
        <h3>Student Thesis Submissions</h3>

        <?php
        // Fetch all submissions for faculty to review (use correct `theses` table and check for errors)
        $faculty_id = (int)$faculty['user_id'];
        $sql = "SELECT t.*, u.full_name AS author, 
    (SELECT decision FROM approvals a WHERE a.thesis_id=t.thesis_id AND a.reviewer_id={$faculty_id} ORDER BY a.decision_date DESC LIMIT 1) AS faculty_decision
    FROM theses t
    LEFT JOIN users u ON t.author_id=u.user_id
    ORDER BY t.thesis_id DESC";
        $theses = mysqli_query($conn, $sql);
        if (!$theses) {
            error_log('DB error (faculty/dashboard.php): ' . mysqli_error($conn) . ' -- SQL: ' . $sql);
            $theses = null; // avoid passing bool to fetch functions
        }
        ?>

        <?php include_once(__DIR__ . '/../admin/live_search.php'); ?>

        <div class="table-responsive">
            <table class="admin-table" style="margin-top:1rem;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($theses): while ($t = mysqli_fetch_assoc($theses)): ?>
                            <tr>
                                <td><?= $t['thesis_id']; ?></td>
                                <td><?= htmlspecialchars($t['title']); ?></td>
                                <td><?= htmlspecialchars($t['author'] ?? 'Unknown'); ?></td>
                                <td><?= htmlspecialchars($t['faculty_decision'] ?? 'Pending'); ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a class="btn btn-primary" href="review_thesis.php?id=<?= $t['thesis_id']; ?>&action=approve">Approve</a>
                                        <a class="btn btn-danger" href="review_thesis.php?id=<?= $t['thesis_id']; ?>&action=reject">Reject</a>
                                        <a class="btn" href="view_thesis.php?id=<?= $t['thesis_id']; ?>">View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;padding:1rem;">No submissions found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <hr>

        <!-- Notes for integration -->
        <!--
1. review_thesis.php: handle approve/reject action and optionally add comments
   - Insert into approvals table with reviewer_id = $faculty['user_id'], thesis_id, decision, decision_date
   - Optionally log action in activity_logs table
2. view_thesis.php: list all files from files table for that thesis, allow download
3. Implement search/filter by title, author, keywords
4. Ensure file validation and security when downloading files
5. All faculty actions should be logged in activity_logs for auditing
-->

    </main>
</body>

</html>
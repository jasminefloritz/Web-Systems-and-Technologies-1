<?php
include('../config/db.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
  header('Location: ../auth/login.php');
  exit;
}
$faculty_id = (int)$_SESSION['user']['user_id'];
$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (isset($_POST['undo']) && isset($_POST['thesis_id'])) {
    $id = (int)$_POST['thesis_id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM approvals WHERE thesis_id = ? AND reviewer_id = ?");
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, 'ii', $id, $faculty_id);
      $ok = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      if ($ok) {
        if (!mysqli_query($conn, "UPDATE theses SET status='pending' WHERE thesis_id={$id}")) {
          error_log('DB error (faculty/theses.php) updating thesis status: ' . mysqli_error($conn));
        }

        mysqli_query($conn, "INSERT INTO activity_logs (user_id, action) VALUES ({$faculty_id}, 'Undid review for thesis #$id')");
        $_SESSION['flash_message'] = 'Decision undone.';
        header('Location: theses.php');
        exit;
      } else {
        $message = 'Failed to undo decision.';
      }
    } else {
      error_log('DB prepare failed (faculty/theses.php undo): ' . mysqli_error($conn));
      $message = 'Failed to undo decision.';
    }
  }

  // Apply a new decision
  if (isset($_POST['decision']) && isset($_POST['thesis_id'])) {
    $id = (int)$_POST['thesis_id'];
    $decision = ($_POST['decision'] === 'approve') ? 'approved' : 'rejected';

    $stmt = mysqli_prepare($conn, "INSERT INTO approvals (thesis_id, reviewer_id, decision, decision_date) VALUES (?, ?, ?, NOW())");
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, 'iis', $id, $faculty_id, $decision);
      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        $dec_escaped = mysqli_real_escape_string($conn, $decision);
        mysqli_query($conn, "UPDATE theses SET status='{$dec_escaped}' WHERE thesis_id={$id}");

        mysqli_query($conn, "INSERT INTO activity_logs (user_id, action) VALUES ({$faculty_id}, 'Reviewed thesis #$id: $decision')");
        $_SESSION['flash_message'] = 'Decision recorded.';
        header('Location: theses.php');
        exit;
      } else {
        error_log('DB error (faculty/theses.php) - insert approvals: ' . mysqli_stmt_error($stmt));
        $message = 'Failed to record decision.';
      }
    } else {
      error_log('DB prepare failed (faculty/theses.php): ' . mysqli_error($conn));
      $message = 'Failed to record decision.';
    }
  }
}


$sql = "SELECT t.*, u.full_name AS author,
    (SELECT decision FROM approvals a WHERE a.thesis_id=t.thesis_id AND a.reviewer_id={$faculty_id} ORDER BY a.decision_date DESC LIMIT 1) AS faculty_decision
    FROM theses t
    LEFT JOIN users u ON t.author_id=u.user_id
    WHERE t.adviser_id={$faculty_id} OR t.status='pending'
    ORDER BY t.thesis_id DESC";
$theses = mysqli_query($conn, $sql);
if (!$theses) {
  error_log('DB error (faculty/theses.php): ' . mysqli_error($conn) . ' -- SQL: ' . $sql);
  $theses = null;
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Manage Theses</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <?php include_once(__DIR__ . '/header.php'); ?>
  <main class="admin-container">
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <h2 style="margin:0;">Manage Theses</h2>
        <div><?php include_once(__DIR__ . '/../admin/live_search.php'); ?></div>
      </div>

      <?php if ($message): ?><p style="color:red;margin-top:0.6rem;font-weight:700;"><?= htmlspecialchars($message) ?></p><?php endif; ?>

      <div class="table-responsive">
        <table class="admin-table">
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
                  <td><?= $t['thesis_id'] ?></td>
                  <td><?= htmlspecialchars($t['title']) ?></td>
                  <td><?= htmlspecialchars($t['author'] ?? 'Unknown') ?></td>
                  <td><?= htmlspecialchars($t['faculty_decision'] ?? $t['status'] ?? 'Pending') ?></td>
                  <td class="table-actions">
                    <a class="btn btn-info" href="view_thesis.php?id=<?= $t['thesis_id'] ?>">View</a>

                    <?php if (!empty($t['faculty_decision'])): ?>
                      <?php if ($t['faculty_decision'] === 'approved'): ?>
                        <form method="POST" action="theses.php" style="display:inline;margin:0;">
                          <input type="hidden" name="thesis_id" value="<?= $t['thesis_id'] ?>">
                          <button type="submit" name="undo" value="1" class="btn" onclick="return confirm('Undo this decision?');">Undo</button>
                        </form>

                        <button class="btn btn-danger" disabled>Reject</button>
                      <?php else: ?>

                        <button class="btn btn-primary" disabled>Approve</button>
                        <form method="POST" action="theses.php" style="display:inline;margin:0;">
                          <input type="hidden" name="thesis_id" value="<?= $t['thesis_id'] ?>">
                          <button type="submit" name="undo" value="1" class="btn" onclick="return confirm('Undo this decision?');">Undo</button>
                        </form>
                      <?php endif; ?>
                    <?php else: ?>
                      <form method="POST" action="theses.php" style="display:inline;margin:0;">
                        <input type="hidden" name="thesis_id" value="<?= $t['thesis_id'] ?>">
                        <button type="submit" name="decision" value="approve" class="btn btn-primary" onclick="return confirm('Approve this thesis?');">Approve</button>
                      </form>

                      <form method="POST" action="theses.php" style="display:inline;margin:0;">
                        <input type="hidden" name="thesis_id" value="<?= $t['thesis_id'] ?>">
                        <button type="submit" name="decision" value="reject" class="btn btn-danger" onclick="return confirm('Reject this thesis?');">Reject</button>
                      </form>
                    <?php endif; ?>

                  </td>
                </tr>
              <?php endwhile;
            else: ?>
              <tr>
                <td colspan="5" style="text-align:center;padding:1rem;">No theses found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if (isset($_SESSION['flash_message'])): ?>
        <p style="margin-top:1rem;color:green;font-weight:700;"><?= htmlspecialchars($_SESSION['flash_message']) ?></p>
      <?php unset($_SESSION['flash_message']);
      endif; ?>

    </div>
  </main>
</body>

</html>
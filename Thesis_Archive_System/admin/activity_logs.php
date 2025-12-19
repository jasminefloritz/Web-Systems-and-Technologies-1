<?php
include("../config/db.php");
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') exit;

$message = '';

// Clear logs (POST)
if (isset($_POST['clear_logs'])) {
  $res = mysqli_query($conn, "TRUNCATE TABLE activity_logs");
  if ($res) {
    $_SESSION['flash_message'] = 'Activity logs cleared.';
  } else {
    error_log('DB error clearing activity_logs: ' . mysqli_error($conn));
    $message = 'Failed to clear activity logs.';
  }
  header('Location: activity_logs.php');
  exit;
}

$sql = "SELECT a.*, u.full_name FROM activity_logs a 
LEFT JOIN users u ON a.user_id=u.user_id ORDER BY a.logged_at DESC";
$logs = mysqli_query($conn, $sql);
if (!$logs) {
  error_log('DB error (admin/activity_logs.php): ' . mysqli_error($conn) . ' -- SQL: ' . $sql);
  $logs = null;
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Activity Logs</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>

  <?php include_once(__DIR__ . '/header.php'); ?>

  <main class="admin-container">
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <h2 style="margin:0;">Activity Logs</h2>
        <div class="actions-row">
          <form method="POST" onsubmit="return confirm('Clear all activity logs?');" style="margin:0;">
            <button type="submit" name="clear_logs" class="btn btn-danger">Clear Logs</button>
          </form>
        </div>
      </div>

      <?php if (isset($_SESSION['flash_message'])): ?>
        <p class="message" style="text-align:center;color:green;font-weight:600;margin-top:0.75rem"><?php echo htmlspecialchars($_SESSION['flash_message']);
                                                                                                    unset($_SESSION['flash_message']); ?></p>
      <?php endif; ?>

      <?php if (!empty($message)): ?>
        <div class="message" style="margin-top:0.75rem;color:#a00;font-weight:600"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <?php include_once(__DIR__ . '/live_search.php'); ?>

      <div class="table-responsive">
        <table class="admin-table" style="margin-top:1rem;">
          <thead>
            <tr>
              <th>User</th>
              <th>Action</th>
              <th>Timestamp</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($logs): while ($l = mysqli_fetch_assoc($logs)): ?>
                <tr>
                  <td><?= htmlspecialchars($l['full_name'] ?? 'Unknown'); ?></td>
                  <td><?= htmlspecialchars($l['action']); ?></td>
                  <td><?= $l['logged_at']; ?></td>
                </tr>
              <?php endwhile;
            else: ?>
              <tr>
                <td colspan="3" style="text-align:center;padding:1rem;">Unable to load activity logs. Please contact the administrator.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>

</html>
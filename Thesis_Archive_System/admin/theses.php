<?php
include("../config/db.php");
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') exit;

$message = '';


if (isset($_POST['decision']) && isset($_POST['thesis_id'])) {
  $id = (int)$_POST['thesis_id'];
  $decision = $_POST['decision'] === 'approve' ? 'approved' : 'rejected';

  $stmt = mysqli_prepare($conn, "INSERT INTO approvals (thesis_id, reviewer_id, decision, decision_date) VALUES (?, ?, ?, NOW())");
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iis", $id, $_SESSION['user']['user_id'], $decision);
    if (mysqli_stmt_execute($stmt)) {

      mysqli_query($conn, "INSERT INTO activity_logs (user_id, action) VALUES ({$_SESSION['user']['user_id']}, 'Admin reviewed thesis #$id: $decision')");
      $_SESSION['flash_message'] = 'Decision recorded.';
    } else {
      error_log('DB error (approvals insert): ' . mysqli_error($conn));
      $message = 'Failed to save decision.';
    }
    mysqli_stmt_close($stmt);
  } else {
    error_log('Prepare failed (approvals insert): ' . mysqli_error($conn));
    $message = 'Failed to save decision.';
  }

  header('Location: theses.php');
  exit;
}

// Fetch theses with latest approval status
$status_filter = isset($_GET['status']) && in_array($_GET['status'], ['approved', 'rejected', 'pending']) ? $_GET['status'] : '';
$where = $status_filter ? "AND (SELECT decision FROM approvals a WHERE a.thesis_id=t.thesis_id ORDER BY a.decision_date DESC LIMIT 1) = '$status_filter'" : '';
$sql = "SELECT t.*, u.full_name AS author, 
    (SELECT decision FROM approvals a WHERE a.thesis_id=t.thesis_id ORDER BY a.decision_date DESC LIMIT 1) AS approval_status
    FROM theses t
    LEFT JOIN users u ON t.author_id=u.user_id
    WHERE 1=1 $where
    ORDER BY t.thesis_id DESC";
$theses = mysqli_query($conn, $sql);
if (!$theses) {
  error_log('DB error (admin/theses.php): ' . mysqli_error($conn) . ' -- SQL: ' . $sql);
  $theses = null;
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Theses Approval</title>
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

    .table-actions {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .btn {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      background-color: #007bff;
    }

    .btn-primary {
      background-color: #007bff;
      color: white;
    }

    .btn-danger {
      background-color: #dc3545;
      color: white;
    }
  </style>
</head>

<body>

  <?php include_once(__DIR__ . '/header.php'); ?>

  <main class="admin-container">
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <h2 style="margin:0;">Theses Approval</h2>
        <div class="actions-row">
          <a href="theses.php" class="btn btn-primary">Refresh</a>
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

      <div style="margin-top:1rem;">
        <div class="live-search-container" style="display:flex;gap:.5rem;align-items:center;margin-top:0.75rem;">
          <label for="status_filter" style="margin:0;">Filter by Status:</label>
          <select id="status_filter" style="padding:0.5rem;border-radius:4px;border:1px solid #ddd;">
            <option value="">All Statuses</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="pending">Pending</option>
          </select>
        </div>
      </div>

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
                  <td><?= htmlspecialchars($t['approval_status'] ?? 'Pending'); ?></td>
                  <td>
                    <div class="table-actions">
                      <a class="btn" href="view_thesis.php?id=<?= $t['thesis_id']; ?>">View</a>
                      <form method="POST" action="theses.php" onsubmit="return confirm('Apply decision?');" style="display:inline;margin:0;">
                        <input type="hidden" name="thesis_id" value="<?= $t['thesis_id']; ?>">
                        <button type="submit" name="decision" value="approve" class="btn btn-primary">Approve</button>
                        <button type="submit" name="decision" value="reject" class="btn btn-danger">Reject</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endwhile;
            else: ?>
              <tr>
                <td colspan="5">Unable to load theses. Please contact the administrator.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>

</html>
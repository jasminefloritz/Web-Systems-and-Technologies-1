<?php
include('../config/db.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
  header('Location: ../auth/login.php');
  exit;
}
$user = $_SESSION['user'];


$sql = "SELECT user_id, full_name, email FROM users WHERE role='student' ORDER BY full_name ASC";
$students = mysqli_query($conn, $sql);
if (!$students) {
  error_log('DB error (faculty/users.php): ' . mysqli_error($conn));
  $students = null;
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Manage Students</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <?php include_once(__DIR__ . '/header.php'); ?>
  <main class="admin-container">
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <h2 style="margin:0;">Manage Students</h2>
        <a href="../admin/users.php" class="btn btn-secondary">Admin Users</a>
      </div>

      <div style="margin-top:0.8rem;display:flex;justify-content:flex-end;">
        <?php include_once(__DIR__ . '/../admin/live_search.php'); ?>
      </div>

      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($students): while ($s = mysqli_fetch_assoc($students)): ?>
                <tr>
                  <td><?= $s['user_id'] ?></td>
                  <td><?= htmlspecialchars($s['full_name']) ?></td>
                  <td><?= htmlspecialchars($s['email']) ?></td>
                  <td>
                    <a class="btn" href="view_student.php?id=<?= $s['user_id'] ?>">View</a>
                  </td>
                </tr>
              <?php endwhile;
            else: ?>
              <tr>
                <td colspan="4" style="text-align:center;padding:1rem;">No students found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body>

</html>
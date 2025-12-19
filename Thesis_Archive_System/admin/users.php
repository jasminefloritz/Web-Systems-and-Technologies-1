<?php
include("../config/db.php");


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}


$delete_msg = '';
$delete_id = null;
if (isset($_POST['delete_id'])) {
  $delete_id = (int)$_POST['delete_id'];
} elseif (isset($_GET['delete'])) {
  $delete_id = (int)$_GET['delete'];
}

if (!is_null($delete_id)) {

  if ($delete_id === (int)$_SESSION['user']['user_id']) {
    $delete_msg = 'You cannot delete your own account.';
  } else {
    $u_res = mysqli_query($conn, "SELECT role FROM users WHERE user_id={$delete_id}");
    $u = $u_res ? mysqli_fetch_assoc($u_res) : null;
    if ($u && $u['role'] === 'admin') {
      $adminCountRes = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role='admin'");
      $adminCount = $adminCountRes ? mysqli_fetch_assoc($adminCountRes)['c'] : 0;
      if ($adminCount <= 1) {
        $delete_msg = 'Cannot delete the last admin.';
      }
    }

    if (empty($delete_msg)) {
      mysqli_query($conn, "DELETE FROM users WHERE user_id={$delete_id}");
      header('Location: users.php?msg=User+deleted');
      exit;
    }
  }
}

// Fetch all users
$role_filter = isset($_GET['role']) && in_array($_GET['role'], ['student', 'faculty', 'admin']) ? $_GET['role'] : '';
$where = $role_filter ? "WHERE u.role='$role_filter'" : '';
$users = mysqli_query($conn, "SELECT u.*, d.department_name, p.program_name FROM users u 
LEFT JOIN departments d ON u.department_id=d.department_id
LEFT JOIN programs p ON u.program_id=p.program_id
$where
ORDER BY user_id DESC");
?>

<!DOCTYPE html>
<html>

<head>
  <title>Manage Users</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>

  <?php include_once(__DIR__ . '/header.php'); ?>

  <main class="admin-container">
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <h2 style="margin:0;">Users</h2>
        <div class="actions-row">
          <a href="add_user.php" class="btn btn-primary">Add New User</a>
        </div>
      </div>
      <?php include_once(__DIR__ . '/live_search.php'); ?>

      <div style="margin-top:1rem;">
        <form method="GET" style="display:inline;">
          <label for="role_filter">Filter by Role:</label>
          <select name="role" id="role_filter" onchange="this.form.submit()">
            <option value="">All Roles</option>
            <option value="admin" <?= $role_filter === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="faculty" <?= $role_filter === 'faculty' ? 'selected' : '' ?>>Faculty</option>
            <option value="student" <?= $role_filter === 'student' ? 'selected' : '' ?>>Student</option>
          </select>
        </form>
      </div>

      <?php if (!empty($_GET['msg'])): ?>
        <div class="message" style="margin-top:0.75rem;color:green;font-weight:600"><?= htmlspecialchars($_GET['msg']) ?></div>
      <?php endif; ?>
      <?php if (!empty($delete_msg)): ?>
        <div class="message" style="margin-top:0.75rem;color:#a00;font-weight:600"><?= htmlspecialchars($delete_msg) ?></div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Department</th>
              <th>Program</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($user = mysqli_fetch_assoc($users)): ?>
              <tr>
                <td><?= $user['user_id']; ?></td>
                <td><?= htmlspecialchars($user['full_name']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['role']); ?></td>
                <td><?= htmlspecialchars($user['department_name']); ?></td>
                <td><?= htmlspecialchars($user['program_name']); ?></td>
                <td>
                  <div class="table-actions">
                    <a class="btn btn-primary" href="edit_user.php?id=<?= $user['user_id']; ?>">Edit</a>
                    <?php if ($user['user_id'] != $_SESSION['user']['user_id']): ?>
                      <form method="POST" action="users.php" onsubmit="return confirm('Delete this user?');" style="display:inline;margin:0;">
                        <input type="hidden" name="delete_id" value="<?= $user['user_id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                      </form>
                    <?php else: ?>
                      <span style="color:#6b7280">Cannot delete self</span>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>

</html>
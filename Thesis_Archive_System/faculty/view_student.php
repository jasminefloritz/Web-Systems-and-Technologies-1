<?php
include('../config/db.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
  header('Location: ../auth/login.php');
  exit;
}
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
  http_response_code(404);
  echo '<p>Student not found.</p>';
  exit;
}

$stmt = mysqli_prepare($conn, "SELECT user_id, full_name, email, profile_picture, signature, role FROM users WHERE user_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = $res && mysqli_num_rows($res) ? mysqli_fetch_assoc($res) : null;
if (!$user) {
  http_response_code(404);
  echo '<p>Student not found.</p>';
  exit;
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Student Profile - <?= htmlspecialchars($user['full_name']) ?></title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <?php include_once(__DIR__ . '/header.php'); ?>
  <main class="admin-container">
    <div class="card">
      <div style="display:flex;gap:1rem;align-items:center;">
        <?php if (!empty($user['profile_picture'])):
          $pp = $user['profile_picture'];
          $pp_path = __DIR__ . '/../uploads/profiles/' . $pp;
          $pp_ver = (file_exists($pp_path) ? filemtime($pp_path) : time()); ?>
          <img src="../uploads/profiles/<?= htmlspecialchars($pp) ?>?v=<?= $pp_ver ?>" class="profile-pic" style="width:120px;height:120px;">
        <?php else: ?>
          <div class="profile-placeholder" style="width:120px;height:120px;">No Picture</div>
        <?php endif; ?>

        <div>
          <h2 style="margin:0;"><?= htmlspecialchars($user['full_name']) ?></h2>
          <p style="margin:.25rem 0;color:#6b7280;">Email: <?= htmlspecialchars($user['email']) ?></p>
          <p style="margin:.25rem 0;color:#6b7280;">Role: <?= htmlspecialchars($user['role']) ?></p>
        </div>
      </div>

      <hr style="margin:1rem 0;">
      <h3>Signature</h3>
      <?php if (!empty($user['signature'])):
        $sig = $user['signature'];
        $sig_path = __DIR__ . '/../uploads/signatures/' . $sig;
        $sig_ver = (file_exists($sig_path) ? filemtime($sig_path) : time()); ?>
        <img src="../uploads/signatures/<?= htmlspecialchars($sig) ?>?v=<?= $sig_ver ?>" class="signature-img">
      <?php else: ?>
        <p>No signature uploaded.</p>
      <?php endif; ?>

      <p style="margin-top:1rem;"><a href="users.php" class="btn">Back</a></p>
    </div>
  </main>
</body>

</html>
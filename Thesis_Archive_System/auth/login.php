<?php
include("../config/db.php");

$message = "";

if (isset($_POST['login'])) {

  $email = trim($_POST['email']);
  $password = $_POST['password'];


  $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);
  $user = mysqli_fetch_assoc($result);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user;

    if ($user['role'] === 'admin') {
      header("Location: ../admin/dashboard.php");
      exit;
    }

    if ($user['role'] === 'faculty') {
      header("Location: ../faculty/dashboard.php");
      exit;
    }

    if ($user['role'] === 'student') {
      header("Location: ../student/dashboard.php");
      exit;
    }
  } else {
    $message = "Invalid email or password";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>

  <div class="auth-container">
    <div class="auth-card">
      <div style="display:flex;align-items:center;gap:0.75rem;justify-content:center;">
        <div style="width:44px;height:44px;border-radius:8px;background:#ffd700;display:flex;align-items:center;justify-content:center;font-weight:800;color:#003366">TA</div>
        <h2 class="auth-title">Thesis Archive</h2>
      </div>

      <?php if ($message): ?>
        <div class="auth-alert auth-alert-error"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <form method="POST" class="auth-form">
        <label class="auth-label">Email</label>
        <input class="auth-input" type="email" name="email" required placeholder="you@example.com">

        <label class="auth-label">Password</label>
        <div class="auth-password-wrap">
          <input class="auth-input" id="password" type="password" name="password" required placeholder="Password">
          <button type="button" class="password-toggle" onclick="togglePassword()">Show</button>
        </div>

        <button class="auth-btn" type="submit" name="login">Sign in</button>
      </form>

      <div class="auth-footer">
        <span>Don't have an account?</span>
        <a href="register.php" class="auth-link">Create account</a>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      var p = document.getElementById('password');
      var btn = document.querySelector('.password-toggle');
      if (p.type === 'password') {
        p.type = 'text';
        btn.textContent = 'Hide';
      } else {
        p.type = 'password';
        btn.textContent = 'Show';
      }
    }
  </script>

</body>

</html>
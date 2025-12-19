<?php
include("../config/db.php");

$message = "";

if (isset($_POST['register'])) {

  $name  = mysqli_real_escape_string($conn, $_POST['full_name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role  = $_POST['role'];


  if ($role === 'admin') {
    $adminCheck = mysqli_query($conn, "SELECT * FROM users WHERE role='admin'");
    if (mysqli_num_rows($adminCheck) > 0) {
      die("Admin already exists. You cannot register as admin.");
    }
  }


  if (!in_array($role, ['student', 'faculty', 'admin'])) {
    die("Invalid role");
  }


  $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  if (mysqli_num_rows($check) > 0) {
    $message = "Email already exists";
  } else {

    mysqli_query($conn, "
            INSERT INTO users (full_name, email, password, role)
            VALUES ('$name', '$email', '$pass', '$role')
        ");

    $message = "Registration successful. You may now login.";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Register</title>
  <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>

  <div class="auth-container">
    <div class="auth-card">
      <h2 class="auth-title">Create account</h2>

      <?php if ($message): ?>
        <div class="auth-alert"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <form method="POST" class="auth-form">
        <label class="auth-label">Full name</label>
        <input class="auth-input" type="text" name="full_name" required>

        <label class="auth-label">Email</label>
        <input class="auth-input" type="email" name="email" required>

        <label class="auth-label">Password</label>
        <input class="auth-input" type="password" name="password" required>

        <label class="auth-label">Role</label>
        <select class="auth-input" name="role" required>
          <option value="">Select role</option>
          <?php
          $adminCheck = mysqli_query($conn, "SELECT * FROM users WHERE role='admin'");
          if (mysqli_num_rows($adminCheck) === 0) {
            echo '<option value="admin">Admin</option>';
          }
          ?>
          <option value="student">Student</option>
          <option value="faculty">Faculty</option>
        </select>

        <button class="auth-btn" type="submit" name="register">Create account</button>
      </form>

      <div class="auth-footer">
        <span>Already have an account?</span>
        <a href="login.php" class="auth-link">Sign in</a>
      </div>
    </div>
  </div>

</body>

</html>
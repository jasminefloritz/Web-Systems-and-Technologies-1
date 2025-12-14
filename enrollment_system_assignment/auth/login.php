<?php include("../config/config.php"); ?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/auth.css">

</head>
<body class="container mt-5">
  <div class="auth-card">
    <?php include("header.php"); ?>


<h3>Enrollment System <br>Login</h3>

<form method="POST" autocomplete="off">
  <input class="form-control mb-2" name="email" placeholder="Email">
  <input class="form-control mb-2" name="password" type="password" placeholder="Password">
  <button class="btn btn-success" name="login">Login</button>
</form>
</div>



<?php
if(isset($_POST['login'])){
  $email = $_POST['email'];
  $pass = $_POST['password'];

  $res = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
  $user = mysqli_fetch_assoc($res);

  if($user && password_verify($pass, $user['password'])){
    $_SESSION['user'] = $user;
        header("Location: ../admin/dashboard.php");

    if($user['role']=="admin") header("Location: ../admin/dashboard.php");
    if($user['role']=="faculty") header("Location: ../faculty/dashboard.php");
    if($user['role']=="student") header("Location: ../student/dashboard.php");
  } else {
    echo "<div class='alert alert-danger mt-2'>Invalid login</div>";
  }
}
?>
</body>
</html>

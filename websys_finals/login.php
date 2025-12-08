<?php include("config.php"); ?>

<?php
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql) or die("SQL ERROR: " . mysqli_error($conn));

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){
            $_SESSION['user'] = $row;
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        echo "<script>alert('Email not found!');</script>";
    }
}
?>

<head>
   <link rel="stylesheet" href="style.css">
  
</head>

<body>
    <?php include("header.php"); ?>
    
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button name="login">Login</button>
        </form>
    </div>
</body>

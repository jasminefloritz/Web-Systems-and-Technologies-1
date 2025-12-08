<?php include("config.php"); ?>

<?php
if(isset($_POST['register'])){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if($_POST['password'] !== $_POST['confirm_password']){
    echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
    exit;
}


    // Upload picture
    $pic = "";
    if(isset($_FILES['picture'])){
        $pic = "uploads/" . time() . "_" . $_FILES['picture']['name'];
        move_uploaded_file($_FILES['picture']['tmp_name'], $pic);
    }

    $sql = "INSERT INTO users(fullname,email,password,user_type,picture)
            VALUES('$fullname','$email','$password','$user_type','$pic')";
    mysqli_query($conn,$sql);

    echo "<script>
        alert('Registration Successful! You may login.');
        window.location='login.php';
      </script>";
}
?>

<head>
   <link rel="stylesheet" href="style.css">
  
</head>

<body>
    <?php include("header.php"); ?>
    
    <div class="register-container">
        <h2>Register</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <select name="user_type" required>
                <option value="" disabled selected>Select User Type</option>
                <option value="faculty">Faculty</option>
                <option value="admin">Admin</option>
            </select>
            <input type="file" name="picture" required>
            <button name="register">Register</button>
        </form>

        <a href="login.php">Already registered? Login</a>
    </div>
</body>

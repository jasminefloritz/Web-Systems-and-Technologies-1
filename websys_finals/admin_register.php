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
        alert('User added successfully!');
        window.location='admin_users.php';
      </script>";
}
?>

<head>
   <link rel="stylesheet" href="style.css">
  <style>
        .register-container h2 { margin-bottom: 5px; }
        .register-container h4 { font-weight: normal; margin-top: 0; margin-bottom: 15px; }
    </style>
</head>

<body>
    <?php include("header.php"); ?>
    
    <div class="register-container">
        <h2>Admin Register</h2>
        <h4>Add a new user to register</h4>
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
            <button name="register">Add User</button>
        </form>

        
    </div>
</body>

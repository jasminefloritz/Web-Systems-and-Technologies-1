<?php
include("../config/config.php");

$message = "";


if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate form inputs
    if(empty($name) || empty($email) || empty($password) || empty($role)){
        $message = "<div class='alert alert-danger'>All fields are required.</div>";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "<div class='alert alert-danger'>Invalid email format.</div>";
    } elseif(!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['error'] !== 0 ||
             !isset($_FILES['signature']) || $_FILES['signature']['error'] !== 0){
        $message = "<div class='alert alert-danger'>Please upload both profile picture and signature.</div>";
    } else {
      
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);

        
        $profile = basename($_FILES['profile_pic']['name']);
        $signature = basename($_FILES['signature']['name']);

        
        if(!is_dir("../uploads/profiles")) mkdir("../uploads/profiles", 0777, true);
        if(!is_dir("../uploads/signatures")) mkdir("../uploads/signatures", 0777, true);

        $profile_path = "../uploads/profiles/$profile";
        $signature_path = "../uploads/signatures/$signature";

        if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_path) &&
           move_uploaded_file($_FILES['signature']['tmp_name'], $signature_path)){

           
            $stmt = $conn->prepare("INSERT INTO users (role,name,email,password,profile_pic,signature) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $role, $name, $email, $pass_hash, $profile, $signature);

            if($stmt->execute()){
                
                header("Location: login.php");
                exit;
            } else {
                $message = "<div class='alert alert-danger'>Database error: ".$stmt->error."</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Failed to upload files. Check folder permissions.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/auth.css">

</head>

<body class="container mt-5">
  <div class="auth-card">
<?php include("header.php"); ?>


<h3>Enrollment System Registration</h3>


<?= $message ?>

<form method="POST" enctype="multipart/form-data">
  <input class="form-control mb-2" name="name" placeholder="Full Name" required>
  <input class="form-control mb-2" name="email" type="email" placeholder="Email" required>
  <input class="form-control mb-2" name="password" type="password" placeholder="Password" required>

  <label>Profile Picture</label>
  <input class="form-control mb-2" type="file" name="profile_pic" required>

  <label>Signature</label>
  <input class="form-control mb-2" type="file" name="signature" required>

  <select class="form-control mb-3" name="role" required>
    <option value="">-- Select Role --</option>
    <option value="student">Student</option>
    <option value="faculty">Faculty</option>
  </select>

  <button class="btn btn-primary" name="register">Register</button>
</form>
</div>
</body>
</html>
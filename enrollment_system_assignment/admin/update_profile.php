<?php
include("../config/config.php");

// Check admin
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['user'];
$message = "";

// Handle profile picture & signature upload
if(isset($_POST['update_profile'])){
    $profile = $_FILES['profile_pic'];
    $signature = $_FILES['signature'];

    // Upload profile picture
    if($profile['name']){
        $profile_name = time() . "_" . $profile['name'];
        move_uploaded_file($profile['tmp_name'], "../uploads/profiles/" . $profile_name);
        mysqli_query($conn, "UPDATE users SET profile_pic='$profile_name' WHERE id={$user['id']}");
        $_SESSION['user']['profile_pic'] = $profile_name;
    }

    // Upload signature
    if($signature['name']){
        $signature_name = time() . "_" . $signature['name'];
        move_uploaded_file($signature['tmp_name'], "../uploads/signatures/" . $signature_name);
        mysqli_query($conn, "UPDATE users SET signature='$signature_name' WHERE id={$user['id']}");
        $_SESSION['user']['signature'] = $signature_name;
    }

        header("Location: profile.php");
        exit;

    $message = "<div class='alert alert-success'>Profile updated successfully.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body class="container mt-4">

<!-- Header -->
   <div class="header-dashboard">
   <div class="header-left">
    <img src="../uploads/profiles/<?= $user['profile_pic']; ?>" alt="Profile">
    <h2>Welcome, <?= htmlspecialchars($user['name']); ?> (Admin)</h2>
</div>
         <div class="header-right">
        <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        <a href="dashboard.php" class="btn btn-light">Back</a>
     </div>   
      </div>    
    </div>



<?= $message ?>

<div class="card shadow-sm p-4 mt-4">
    <h4 class="mb-3">Update Profile</h4>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Profile Picture</label>
            <input type="file" name="profile_pic" class="form-control">
        </div>
        <div class="mb-3">
            <label>Signature</label>
            <input type="file" name="signature" class="form-control">
        </div>
        <button name="update_profile" class="btn btn-primary">Update</button>
    </form>
</div>

</body>
</html>

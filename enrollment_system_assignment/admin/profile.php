<?php
include("../config/config.php");

// Check admin
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['user'];
$message = "";


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Profile</title>
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

<!-- Profile Update Card -->
<div class="card shadow-sm p-4">
    
        

    <!-- Display current images -->
   <div class="mt-4 text-center">

        <p><strong>Current Profile Picture:</strong></p>
        <?php if(!empty($user['profile_pic'])): ?>
            <img src="../uploads/profiles/<?= $user['profile_pic'] ?>" width="100">
        <?php else: ?>
            <span>No profile picture uploaded.</span>
        <?php endif; ?>
        
        <p class="mt-3"><strong>Current Signature:</strong></p>
        <?php if(!empty($user['signature'])): ?>
            <img src="../uploads/signatures/<?= $user['signature'] ?>" width="150"><br><br>
        <?php else: ?>
            <span>No signature uploaded.</span>
        <?php endif; ?>
    </div>
 <div class="text-center mt-3">
    <a href="update_profile.php" 
       class="btn btn-primary px-3 py-3">
        Update Profile
    </a>
</div>


</div>

</body>
</html>

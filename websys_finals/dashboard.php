<?php
include("config.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<head>
   <link rel="stylesheet" href="style.css">
   <style>

.container h2 {
    font-size: 24px; 
    word-wrap: break-word; 
}

.container img {
    display: block;       
    margin: 15px auto;    
    width: 150px;         
    height: 150px;        
    border-radius: 50%;   
    object-fit: cover;    
}

   </style>
</head>

<?php include("header.php"); ?>
<div class="container">



<h2>Welcome, <?php echo $user['fullname']; ?></h2>
<img src="<?php echo $user['picture']; ?>" width="150" height="150"><br><br>

<p class="dashboard-info"><strong>Email:</strong> <?php echo $user['email']; ?></p>
<p class="dashboard-info"><strong>User Type:</strong> <?php echo $user['user_type']; ?></p>




</div>

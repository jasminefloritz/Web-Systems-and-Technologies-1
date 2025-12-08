<?php
include("config.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user']['id'];


if (!isset($_POST['confirm'])) {
?>
    <link rel="stylesheet" href="style.css">

    <div class="container" style="text-align:center; margin-top:50px;">
        <h2>Confirm Account Deletion</h2>
        <p>Are you sure you want to delete your account? This action cannot be undone.</p>

    
        <form method="POST" action="">
            <button type="submit" name="confirm" value="yes" 
                    style="padding:10px 20px; background:red; color:white; border:none; cursor:pointer;">
                Yes, Delete My Account
            </button>
        </form>

        <br>

       
        <form action="dashboard.php">
            <button type="submit" 
                    style="padding:10px 20px; background:gray; color:white; border:none; cursor:pointer;">
                Cancel
            </button>
        </form>
    </div>

<?php
    exit; 
}

mysqli_query($conn, "DELETE FROM users WHERE id=$id");

session_unset();
session_destroy();


header("Location: register.php");
exit;

?>

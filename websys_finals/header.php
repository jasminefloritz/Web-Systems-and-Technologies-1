<?php 
include("config.php"); 

$current_page = basename($_SERVER['PHP_SELF']);
?>

    <div class="header">
        <div class="header-title">DTR System</div>

        <div class="header-links">

            <?php if(isset($_SESSION['user'])): ?>

              
                <?php if($current_page == "dashboard.php"): ?>
                    <a href="delete_account.php">Delete Account</a>
                <?php endif; ?>

                <a href="logout.php">Logout</a>
                
<?php if($_SESSION['user']['user_type'] == 'admin'): ?>
    <a href="admin_users.php">Manage Users</a>
<?php endif; ?>

            <?php else: ?>
                

                <?php if($current_page == "login.php"): ?>
                    <a href="register.php">Register</a>

                <?php elseif($current_page == "register.php"): ?>
                    <a href="login.php">Login</a>

                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>

            <?php endif; ?>

        </div>
 

</div>

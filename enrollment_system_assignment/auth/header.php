<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-light bg-light px-4 mb-4 border-bottom">
    <span class="navbar-brand mb-0 h4 fw-bold">
        
    </span>

    <div>
        <?php if ($currentPage === 'login.php'): ?>
            <a href="register.php" class="btn btn-outline-primary">
                Register
            </a>
        <?php elseif ($currentPage === 'register.php'): ?>
            <a href="login.php" class="btn btn-outline-success">
                Login
            </a>
        <?php endif; ?>
    </div>
</nav>

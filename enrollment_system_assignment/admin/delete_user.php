<?php
include("../config/config.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM users WHERE id=$id");
header("Location: manage_users.php");

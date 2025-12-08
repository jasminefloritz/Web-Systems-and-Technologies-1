<?php
$conn = mysqli_connect("localhost", "root", "", "websys_finals");

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

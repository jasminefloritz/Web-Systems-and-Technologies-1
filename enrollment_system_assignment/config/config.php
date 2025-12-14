<?php
$conn = mysqli_connect("localhost", "root", "", "enrollment_db");
if (!$conn) {
    die("Database connection failed");
}
session_start();
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = mysqli_connect("localhost", "root", "", "thesis_repository");

if (!$conn) {
    die("Database connection failed");
}

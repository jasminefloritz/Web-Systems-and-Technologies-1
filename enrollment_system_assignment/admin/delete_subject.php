<?php
include("../config/config.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM prerequisites WHERE subject_id=$id");
mysqli_query($conn, "DELETE FROM subjects WHERE id=$id");
header("Location: manage_subjects.php");

<?php
include("../config/config.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$id = (int)$_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($res);
$message = "";

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    mysqli_query($conn, "UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$id");

    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['name']){
        $profile_name = time()."_".$_FILES['profile_pic']['name'];
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], "../uploads/profiles/".$profile_name);
        mysqli_query($conn, "UPDATE users SET profile_pic='$profile_name' WHERE id=$id");
    }

    if(isset($_FILES['signature']) && $_FILES['signature']['name']){
        $sig_name = time()."_".$_FILES['signature']['name'];
        move_uploaded_file($_FILES['signature']['tmp_name'], "../uploads/signatures/".$sig_name);
        mysqli_query($conn, "UPDATE users SET signature='$sig_name' WHERE id=$id");
    }

    $message = "<div class='alert alert-success'>User updated successfully.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
<style>

body {
    background: linear-gradient(135deg, #e6f0ff, #f2f6fc);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}


.edit-card {
    background-color: #fff;
    border-radius: 20px;
    box-shadow: 0 12px 36px rgba(0,0,0,0.12);
    padding: 30px;
    width: 100%;
    max-width: 600px;
}


.edit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
</style>
</head>
<body>

<div class="edit-card">
    <div class="edit-header">
        <h4>Edit User</h4>
        <a href="manage_users.php" class="btn btn-secondary">Back</a>
    </div>

    <?= $message ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control mb-3" placeholder="Name" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control mb-3" placeholder="Email" required>
        <select name="role" class="form-select mb-3" required>
            <option value="student" <?= $user['role']=='student'?'selected':'' ?>>Student</option>
            <option value="faculty" <?= $user['role']=='faculty'?'selected':'' ?>>Faculty</option>
            <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
        </select>

        <label class="form-label">Profile Picture</label>
        <input type="file" name="profile_pic" class="form-control mb-3">

        <label class="form-label">Signature</label>
        <input type="file" name="signature" class="form-control mb-3">

        <button name="update" class="btn btn-primary w-100">Update User</button>
    </form>
</div>

</body>
</html>

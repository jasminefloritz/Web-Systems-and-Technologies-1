<?php
include("config/db.php");
$user = $_SESSION['user'];

if (isset($_POST['upload'])) {
    $pic = $_FILES['profile']['name'];
    $sig = $_FILES['signature']['name'];

    move_uploaded_file($_FILES['profile']['tmp_name'], "uploads/profiles/$pic");
    move_uploaded_file($_FILES['signature']['tmp_name'], "uploads/signatures/$sig");

    mysqli_query($conn, "UPDATE users SET 
        profile_picture='$pic',
        signature='$sig'
        WHERE user_id='{$user['user_id']}'");

    mysqli_query($conn, "INSERT INTO activity_logs(user_id,action)
        VALUES('{$user['user_id']}','Updated profile & signature')");
}
?>

<form method="POST" enctype="multipart/form-data">
    Profile Picture: <input type="file" name="profile" required><br>
    Signature: <input type="file" name="signature" required><br>
    <button name="upload">Save</button>
</form>
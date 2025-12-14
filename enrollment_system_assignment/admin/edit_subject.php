<?php
include("../config/config.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$id = (int)$_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM subjects WHERE id=$id");
$subject = mysqli_fetch_assoc($res);
$message = "";

if(isset($_POST['update'])){
    $code = $_POST['code'];
    $name = $_POST['name'];
    mysqli_query($conn, "UPDATE subjects SET code='$code', name='$name' WHERE id=$id");

    // Update prerequisites
    mysqli_query($conn, "DELETE FROM prerequisites WHERE subject_id=$id");
    if(isset($_POST['prerequisites'])){
        foreach($_POST['prerequisites'] as $pre){
            mysqli_query($conn, "INSERT INTO prerequisites(subject_id, prerequisite_id) VALUES($id,$pre)");
        }
    }
    $message = "<div class='alert alert-success'>Subject updated successfully.</div>";
}

$all_subjects = mysqli_query($conn, "SELECT * FROM subjects WHERE id != $id");
$subject_prereqs = [];
$preq = mysqli_query($conn, "SELECT prerequisite_id FROM prerequisites WHERE subject_id=$id");
while($p = mysqli_fetch_assoc($preq)){
    $subject_prereqs[] = $p['prerequisite_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Subject</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
<style>
/* Center card vertically */
body {
    background: linear-gradient(135deg, #e6f0ff, #f2f6fc);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Container card */
.edit-card {
    background-color: #fff;
    border-radius: 20px;
    box-shadow: 0 12px 36px rgba(0,0,0,0.12);
    padding: 30px;
    width: 100%;
    max-width: 600px;
}

/* Back button and title alignment */
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
        <h4>Edit Subject</h4>
        <a href="manage_subjects.php" class="btn btn-secondary">Back</a>
    </div>

    <?= $message ?>

    <form method="POST">
        <input type="text" name="code" value="<?= htmlspecialchars($subject['code']) ?>" required class="form-control mb-3" placeholder="Subject Code">
        <input type="text" name="name" value="<?= htmlspecialchars($subject['name']) ?>" required class="form-control mb-3" placeholder="Subject Name">

        <label class="form-label">Prerequisites</label>
        <select multiple name="prerequisites[]" class="form-select mb-3">
            <?php while($s = mysqli_fetch_assoc($all_subjects)): ?>
                <option value="<?= $s['id'] ?>" <?= in_array($s['id'], $subject_prereqs) ? 'selected':'' ?>>
                    <?= htmlspecialchars($s['code']) ?> - <?= htmlspecialchars($s['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button class="btn btn-primary w-100" name="update">Update Subject</button>
    </form>
</div>

</body>
</html>

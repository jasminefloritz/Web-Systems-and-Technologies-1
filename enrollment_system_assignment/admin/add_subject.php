<?php
include("../config/config.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$message = "";

if(isset($_POST['add'])){
    $code = $_POST['code'];
    $name = $_POST['name'];
    mysqli_query($conn, "INSERT INTO subjects(code,name) VALUES('$code','$name')");
    $subject_id = mysqli_insert_id($conn);

    if(isset($_POST['prerequisites'])){
        foreach($_POST['prerequisites'] as $pre){
            mysqli_query($conn, "INSERT INTO prerequisites(subject_id, prerequisite_id) VALUES($subject_id,$pre)");
        }
    }
    header("Location: add_subject.php?success=1");
exit;

}

$all_subjects = mysqli_query($conn, "SELECT * FROM subjects");
?>

<h4>Add Subject</h4>
<?= $message ?>
<form method="POST">
    <input type="text" name="code" placeholder="Code" required class="form-control mb-2">
    <input type="text" name="name" placeholder="Name" required class="form-control mb-2">
    <label>Prerequisites (optional)</label>
    <select multiple name="prerequisites[]" class="form-select mb-2">
        <?php while($s = mysqli_fetch_assoc($all_subjects)): ?>
            <option value="<?= $s['id'] ?>"><?= $s['code'] ?> - <?= $s['name'] ?></option>
        <?php endwhile; ?>
    </select>
    <button class="btn btn-success" name="add">Add Subject</button>
</form>

<?php
include("../config/config.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$message = "";
if(isset($_POST['enroll'])){
    $student_id = (int)$_POST['student_id'];
    $subject_id = (int)$_POST['subject_id'];

    mysqli_query($conn, "INSERT INTO enrollments(student_id, subject_id, status) VALUES($student_id, $subject_id, 'enrolled')");
    $message = "<div class='alert alert-success'>Student enrolled successfully.</div>";
}

$students = mysqli_query($conn, "SELECT * FROM users WHERE role='student'");
$subjects = mysqli_query($conn, "SELECT * FROM subjects");
?>

<h4>Enrollment Override</h4>
<?= $message ?>
<form method="POST" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="student_id" class="form-select" required>
            <option value="">Select Student</option>
            <?php while($s = mysqli_fetch_assoc($students)): ?>
                <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-4">
        <select name="subject_id" class="form-select" required>
            <option value="">Select Subject</option>
            <?php while($sub = mysqli_fetch_assoc($subjects)): ?>
                <option value="<?= $sub['id'] ?>"><?= $sub['code'] ?> - <?= $sub['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-4">
        <button class="btn btn-success" name="enroll">Enroll</button>
    </div>
</form>

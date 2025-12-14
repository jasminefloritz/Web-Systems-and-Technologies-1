<?php
include("../config/config.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student'){
    header("Location: ../student/dashboard.php");
    exit;
}

$user = $_SESSION['user'];
$student_id = $user['id'];
$message = "";

// Handle enrollment
if(isset($_POST['enroll'])){
    $subject_id = (int)$_POST['subject_id'];

    // Check if faculty is assigned
    $faculty_check = mysqli_query($conn, "SELECT faculty_id FROM subject_faculty WHERE subject_id=$subject_id");
    $faculty_row = mysqli_fetch_assoc($faculty_check);

    if(!$faculty_row || !$faculty_row['faculty_id']){
        $message = "<div class='alert alert-warning'>Cannot enroll: No instructor assigned for this subject yet.</div>";
    } else {
        // Check if already enrolled
        $check_existing = mysqli_query($conn, "SELECT * FROM enrollments WHERE student_id=$student_id AND subject_id=$subject_id");
        if(mysqli_num_rows($check_existing) > 0){
            $message = "<div class='alert alert-warning'>You are already enrolled in this subject.</div>";
        } else {
            // Check prerequisites
            $pre = mysqli_query($conn, "SELECT prerequisite_id FROM prerequisites WHERE subject_id=$subject_id");
            $can_enroll = true;
            while($p = mysqli_fetch_assoc($pre)){
                $prereq_id = $p['prerequisite_id'];
                $check = mysqli_query($conn, "SELECT * FROM enrollments WHERE student_id=$student_id AND subject_id=$prereq_id AND status='completed'");
                if(mysqli_num_rows($check) == 0){
                    $can_enroll = false;
                    $prereq_sub = mysqli_query($conn, "SELECT code FROM subjects WHERE id=$prereq_id");
                    $prereq_code = mysqli_fetch_assoc($prereq_sub)['code'];
                    $message = "<div class='alert alert-danger'>Cannot enroll: prerequisite {$prereq_code} not completed.</div>";
                    break;
                }
            }

            if($can_enroll){
                mysqli_query($conn, "INSERT INTO enrollments(student_id, subject_id, status) VALUES($student_id, $subject_id, 'enrolled')");
                $message = "<div class='alert alert-success'>Successfully enrolled in the subject.</div>";
            }
        }
    }
}

// Fetch available subjects for enrollment
$all_subjects = mysqli_query($conn, "
    SELECT * FROM subjects 
    WHERE id NOT IN (
        SELECT subject_id FROM enrollments WHERE student_id=$student_id
    )
    ORDER BY code ASC
");

// Fetch enrolled subjects (after enrollment handling)
$enrolled = mysqli_query($conn, "
    SELECT s.code, s.name, e.status, e.grade
    FROM enrollments e
    JOIN subjects s ON e.subject_id = s.id
    WHERE e.student_id=$student_id
    ORDER BY s.code ASC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="main-dashboard">
    <div class="header-dashboard">
        <div class="header-left">
            <img src="../uploads/profiles/<?= htmlspecialchars($user['profile_pic']); ?>" alt="Profile" class="rounded-circle" width="60">
            <h2>Welcome, <?= htmlspecialchars($user['name']); ?></h2>
        </div>
        <div class="header-right">
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <!-- Display messages -->
    <?= $message ?>

    <!-- Enroll Form -->
    <h4>Enroll in Subject</h4>
    <form method="POST" class="row g-2 enroll-form mb-4">
        <div class="col-md-8">
            <select class="form-select" name="subject_id" required>
                <option value="">-- Select Subject --</option>
                <?php while($s = mysqli_fetch_assoc($all_subjects)): ?>
                    <option value="<?= $s['id'] ?>">
                        <?= htmlspecialchars($s['code']) ?> - <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary" name="enroll">Enroll</button>
        </div>
    </form>

    <!-- Enrolled Subjects Table -->
    <h4>My Enrolled Subjects</h4>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Code</th>
                <th>Description</th>
                <th>Status</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($enrolled) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($enrolled)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['code']); ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td>
                        <?php if($row['status']=='enrolled'): ?>
                            <span class="badge bg-warning text-dark">Enrolled</span>
                        <?php else: ?>
                            <span class="badge bg-success">Completed</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['grade'] ?? '-') ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">No enrolled subjects yet.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

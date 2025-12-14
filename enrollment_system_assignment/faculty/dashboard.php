<?php
include("../config/config.php");


if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'faculty'){
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['user'];
$message = "";


if(isset($_POST['mark_completed'])){
    $enrollment_id = (int)$_POST['enrollment_id'];
    $grade = $_POST['grade'];


   $grade = mysqli_real_escape_string($conn, $_POST['grade']); 
mysqli_query($conn, "UPDATE enrollments SET status='completed', grade='$grade' WHERE id=$enrollment_id");

    
    $check = mysqli_query($conn, "SELECT * FROM grades WHERE enrollment_id=$enrollment_id");
    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn, "UPDATE grades SET grade='$grade' WHERE enrollment_id=$enrollment_id");
    } else {
        mysqli_query($conn, "INSERT INTO grades(enrollment_id, grade) VALUES($enrollment_id, '$grade')");
    }

    $message = "<div class='alert alert-success'>Student marked as completed with grade $grade.</div>";
}


$faculty_subjects = mysqli_query($conn, "SELECT subject_id FROM subject_faculty WHERE faculty_id={$user['id']}");
$subject_ids = [];
while($s = mysqli_fetch_assoc($faculty_subjects)){
    $subject_ids[] = $s['subject_id'];
}

$ids = implode(',', $subject_ids ?: [0]); 


$res = mysqli_query($conn,"
    SELECT e.id AS enrollment_id, s.code AS subject_code, s.name AS subject_name, 
           u.id AS student_id, u.name AS student_name, u.profile_pic, u.signature, e.status,
           g.grade
    FROM enrollments e
    JOIN users u ON e.student_id = u.id
    JOIN subjects s ON e.subject_id = s.id
    LEFT JOIN grades g ON e.id = g.enrollment_id
    WHERE e.subject_id IN ($ids)
    ORDER BY s.code, u.name
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Faculty Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="..\assets\css\dashboard.css">
</head>
<body class="container mt-4">

<div class="main-dashboard">
   <div class="header-dashboard">
    <div class="header-left">
        <img src="../uploads/profiles/<?= $user['profile_pic']; ?>" alt="Profile">
        <h2>Welcome, <?= htmlspecialchars($user['name']); ?> (Faculty)</h2>
    </div>
    <div class="header-right">
        <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>


<?= $message ?>

<h4>Students Enrolled in Your Subjects</h4>
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Student</th>
            <th>Profile Picture</th>
            <th>Signature</th>
            <th>Subject Code</th>
            <th>Subject Name</th>
            <th>Status</th>
            <th>Grade</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_assoc($res)): ?>
        <tr>
            <td><?= htmlspecialchars($row['student_name']); ?></td>
            <td><img src="../uploads/profiles/<?= $row['profile_pic']; ?>" width="80"></td>
            <td><img src="../uploads/signatures/<?= $row['signature']; ?>" width="80"></td>
            <td><?= $row['subject_code']; ?></td>
            <td><?= $row['subject_name']; ?></td>
            <td>
                <?php if($row['status']=='enrolled'): ?>
                    <span class="badge bg-warning text-dark">Enrolled</span>
                <?php else: ?>
                    <span class="badge bg-success">Completed</span>
                <?php endif; ?>
            </td>
            <td>
                <?= $row['grade'] ?? '-' ?>
            </td>
            <td>
                <?php if($row['status']=='enrolled'): ?>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#gradeModal<?= $row['enrollment_id'] ?>">
                        Mark as Completed
                    </button>

                
                    <div class="modal fade" id="gradeModal<?= $row['enrollment_id'] ?>" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Assign Grade to <?= htmlspecialchars($row['student_name']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <input type="hidden" name="enrollment_id" value="<?= $row['enrollment_id'] ?>">
                            <select name="grade" class="form-select" required>
                                <option value="">-- Select Grade --</option>
                                <option value="1.00">1.00</option>
                                <option value="1.25">1.25</option>
                                <option value="1.50">1.50</option>
                                <option value="1.75">1.75</option>
                                <option value="2.00">2.00</option>
                                <option value="2.25">2.25</option>
                                <option value="2.50">2.50</option>
                                <option value="2.75">2.75</option>
                                <option value="3.00">3.00</option>
                                <option value="5.00">5.00 (Failed)</option>
                            </select>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" name="mark_completed" class="btn btn-success">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          </div>
                        </form>
                      </div>
                    </div>
                <?php else: ?>
                    <span class="text-success">Done</span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

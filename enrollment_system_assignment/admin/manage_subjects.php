<?php
include("../config/config.php");

// Check admin
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['user'];
$message = "";

if(isset($_POST['add_subject'])){
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    // Check if subject code already exists
    $check = mysqli_query($conn, "SELECT * FROM subjects WHERE code='$code'");
    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn, "INSERT INTO subjects (code, name) VALUES ('$code', '$name')");
        $message = "<div class='alert alert-success'>Subject added successfully.</div>";
    } else {
        $message = "<div class='alert alert-warning'>Subject code already exists.</div>";
    }
}


// Handle faculty assignment
if(isset($_POST['assign_faculty'])){
    $subject_id = (int)$_POST['subject_id'];
    $faculty_id = (int)$_POST['faculty_id'];

    // Check if assignment exists
    $check = mysqli_query($conn, "SELECT * FROM subject_faculty WHERE subject_id=$subject_id");
    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn, "UPDATE subject_faculty SET faculty_id=$faculty_id WHERE subject_id=$subject_id");
    } else {
        mysqli_query($conn, "INSERT INTO subject_faculty(subject_id, faculty_id) VALUES($subject_id, $faculty_id)");
    }

    $message = "<div class='alert alert-success'>Faculty assigned successfully!</div>";
}

// Handle prerequisite assignment
if(isset($_POST['assign_prerequisite'])){
    $subject_id = (int)$_POST['subject_id'];
    $prereq_id  = (int)$_POST['prerequisite_id'];

    if($subject_id === $prereq_id){
        $message = "<div class='alert alert-danger'>A subject cannot be its own prerequisite.</div>";
    } else {
        $check = mysqli_query($conn,"SELECT * FROM prerequisites WHERE subject_id=$subject_id AND prerequisite_id=$prereq_id");
        if(mysqli_num_rows($check) == 0){
            mysqli_query($conn,"INSERT INTO prerequisites(subject_id, prerequisite_id) VALUES($subject_id, $prereq_id)");
            $message = "<div class='alert alert-success'>Prerequisite assigned successfully.</div>";
        } else {
            $message = "<div class='alert alert-warning'>Prerequisite already exists.</div>";
        }
    }
}

// Fetch all subjects
$subject_result = mysqli_query($conn, "
    SELECT s.id, s.code, s.name, f.name AS faculty_name, f.id AS faculty_id, GROUP_CONCAT(p.prerequisite_id) AS prereqs
    FROM subjects s
    LEFT JOIN subject_faculty sf ON s.id = sf.subject_id
    LEFT JOIN users f ON sf.faculty_id = f.id AND f.role='faculty'
    LEFT JOIN prerequisites p ON s.id = p.subject_id
    GROUP BY s.id
");

$subjects = [];
while($row = mysqli_fetch_assoc($subject_result)){
    $subjects[] = $row;
}


// Fetch all faculties
$faculties = mysqli_query($conn, "SELECT id, name FROM users WHERE role='faculty'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Subjects</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body class="container mt-4">

<!-- Header -->
   <div class="header-dashboard">
   <div class="header-left">
    <img src="../uploads/profiles/<?= $user['profile_pic']; ?>" alt="Profile">
    <h2>Welcome, <?= htmlspecialchars($user['name']); ?> (Admin)</h2>
</div>
         <div class="header-right">
        <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        <a href="dashboard.php" class="btn btn-light">Back</a>
     </div>   
      </div>    
    </div>


<?= $message ?>

<!-- Add Subject -->
<h4>Add Subject</h4>
<form method="POST" class="row g-2 mb-4">
    <div class="col-md-4">
        <input type="text" name="code" placeholder="Subject Code" class="form-control" required>
    </div>
    <div class="col-md-6">
        <input type="text" name="name" placeholder="Subject Name" class="form-control" required>
    </div>
    <div class="col-md-2">
        <button name="add_subject" class="btn btn-success w-100">Add Subject</button>
    </div>
</form>

<!-- Assign Faculty -->
<h4>Assign Faculty</h4>
<form method="POST" class="row g-2 mb-4">
    <div class="col-md-5">
        <select name="subject_id" class="form-select" required>
            <option value="">-- Select Subject --</option>
            <?php foreach($subjects as $s): ?>
    <option value="<?= $s['id'] ?>"><?= $s['code'] ?> - <?= $s['name'] ?> (<?= $s['faculty_name'] ?? 'No faculty' ?>)</option>
<?php endforeach; ?>

        </select>
    </div>
    <div class="col-md-5">
        <select name="faculty_id" class="form-select" required>
            <option value="">-- Select Faculty --</option>
            <?php while($f = mysqli_fetch_assoc($faculties)): ?>
                <option value="<?= $f['id'] ?>"><?= $f['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button name="assign_faculty" class="btn btn-primary w-100">Assign</button>
    </div>
</form>

<!-- Assign Prerequisite -->
<h4>Assign Prerequisite</h4>
<form method="POST" class="row g-2 mb-4">
    <div class="col-md-5">
        <select name="subject_id" class="form-select" required>
            <option value="">-- Select Subject --</option>
            <?php foreach($subjects as $s): ?>
                <option value="<?= $s['id'] ?>"><?= $s['code'] ?> - <?= $s['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-5">
        <select name="prerequisite_id" class="form-select" required>
            <option value="">-- Select Prerequisite Subject --</option>
            <?php foreach($subjects as $s): ?>
                <option value="<?= $s['id'] ?>"><?= $s['code'] ?> - <?= $s['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button name="assign_prerequisite" class="btn btn-warning w-100">Assign</button>
    </div>
</form>

<!-- Subjects Table -->
<h4>Subjects</h4>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Faculty</th>
            <th>Prerequisites</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($subjects as $s): ?>
    <tr>
        <td><?= $s['code'] ?></td>
        <td><?= $s['name'] ?></td>
        <td><?= $s['faculty_name'] ?? 'No faculty' ?></td>
        <td>
            <?php
            if($s['prereqs']){
                $prereq_ids = explode(",", $s['prereqs']);
                $names = [];
                foreach($prereq_ids as $pid){
                    $res = mysqli_query($conn, "SELECT code FROM subjects WHERE id=$pid");
                    $names[] = mysqli_fetch_assoc($res)['code'];
                }
                echo implode(", ", $names);
            }
            ?>
        </td>
        <td>
            <a href="edit_subject.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
            <a href="delete_subject.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subject?')">Delete</a>
        </td>
    </tr>
<?php endforeach; ?>

        
    </tbody>
</table>

</body>
</html>

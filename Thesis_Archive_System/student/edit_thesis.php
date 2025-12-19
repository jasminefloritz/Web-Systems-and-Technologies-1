<?php
include("../config/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$student = $_SESSION['user'];
$message = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: dashboard.php');
    exit;
}


$thesisRes = mysqli_query($conn, "SELECT * FROM theses WHERE thesis_id=$id AND author_id=" . (int)$student['user_id']);
if (!$thesisRes || mysqli_num_rows($thesisRes) === 0) {
    header('Location: dashboard.php');
    exit;
}
$thesis = mysqli_fetch_assoc($thesisRes);


$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY department_name");
$programs = mysqli_query($conn, "SELECT * FROM programs ORDER BY program_name");
$faculty_list = mysqli_query($conn, "SELECT user_id, full_name FROM users WHERE role='faculty'");

if (isset($_POST['update_thesis'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $abstract = mysqli_real_escape_string($conn, $_POST['abstract']);
    $keywords = mysqli_real_escape_string($conn, $_POST['keywords']);
    $adviser = (int)$_POST['adviser_id'];
    $year = isset($_POST['year_level']) ? (int)$_POST['year_level'] : NULL;
    $department = (int)$_POST['department_id'];
    $program = (int)$_POST['program_id'];

    $sql = "UPDATE theses SET title='$title', abstract='$abstract', keywords='$keywords', adviser_id=$adviser, department_id=$department, program_id=$program" . ($year !== NULL ? ", year=$year" : "") . " WHERE thesis_id=$id AND author_id=" . (int)$student['user_id'];
    if (mysqli_query($conn, $sql)) {
        // Log
        mysqli_query($conn, "INSERT INTO activity_logs (user_id, action) VALUES ({$student['user_id']}, 'Updated thesis #$id')");
        $_SESSION['flash_message'] = 'Thesis updated successfully.';
        header('Location: dashboard.php');
        exit;
    } else {
        $message = 'Update failed: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Thesis</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <?php include_once(__DIR__ . '/header.php'); ?>
    <main class="admin-container">
        <div class="card">
            <h2>Edit Thesis</h2>

            <?php if ($message): ?>
                <p class="message" style="color: red; font-weight:600; margin-bottom:1rem;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($thesis['title']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Abstract:</label>
                    <textarea name="abstract" rows="4" required><?= htmlspecialchars($thesis['abstract']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Keywords:</label>
                    <input type="text" name="keywords" value="<?= htmlspecialchars($thesis['keywords']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Adviser:</label>
                    <select name="adviser_id" required>
                        <option value="">Select Adviser</option>
                        <?php while ($f = mysqli_fetch_assoc($faculty_list)): ?>
                            <option value="<?= $f['user_id'] ?>" <?= $f['user_id'] == $thesis['adviser_id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['full_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Department:</label>
                    <select name="department_id" required>
                        <option value="">Select Department</option>
                        <?php mysqli_data_seek($departments, 0);
                        while ($d = mysqli_fetch_assoc($departments)): ?>
                            <option value="<?= $d['department_id'] ?>" <?= $d['department_id'] == $thesis['department_id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['department_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Program:</label>
                    <select name="program_id" required>
                        <option value="">Select Program</option>
                        <?php mysqli_data_seek($programs, 0);
                        while ($p = mysqli_fetch_assoc($programs)): ?>
                            <option value="<?= $p['program_id'] ?>" <?= $p['program_id'] == $thesis['program_id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['program_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Year Level:</label>
                    <input type="number" name="year_level" value="<?= htmlspecialchars($thesis['year']) ?>">
                </div>

                <button type="submit" name="update_thesis" class="btn btn-primary">Update Thesis</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </main>
</body>

</html>
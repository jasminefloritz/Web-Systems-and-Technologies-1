<?php
include("../config/db.php");


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}



$student = $_SESSION['user'];
$message = "";


$departments = mysqli_query($conn, "SELECT * FROM departments");
$programs = mysqli_query($conn, "SELECT * FROM programs");
$programs = mysqli_query($conn, "SELECT * FROM programs");
$faculty_list = mysqli_query($conn, "SELECT user_id, full_name FROM users WHERE role='faculty'");



if (isset($_POST['submit_thesis'])) {
    $title      = mysqli_real_escape_string($conn, $_POST['title']);
    $abstract   = mysqli_real_escape_string($conn, $_POST['abstract']);
    $keywords   = mysqli_real_escape_string($conn, $_POST['keywords']);
    $adviser    = $adviser = (int)$_POST['adviser_id'];
    $year = isset($_POST['year_level']) ? (int)$_POST['year_level'] : NULL;
    $department = (int)$_POST['department_id'];
    $program    = (int)$_POST['program_id'];

    $insert = mysqli_query($conn, "
    INSERT INTO theses 
    (title, abstract, keywords, author_id, adviser_id, department_id, program_id, year, submitted_at)
    VALUES 
    (
        '$title',
        '$abstract',
        '$keywords',
        {$student['user_id']},
        '$adviser',
        $department,
        $program,
        " . ($year !== NULL ? $year : "NULL") . ",
        NOW()
    )
");


    if ($insert) {
        $thesisID = mysqli_insert_id($conn);


        mysqli_query($conn, "INSERT INTO activity_logs (user_id, action) VALUES ({$student['user_id']}, 'Submitted thesis: $title')");


        foreach ($_FILES['thesis_files']['name'] as $key => $filename) {
            $tmp_name = $_FILES['thesis_files']['tmp_name'][$key];
            $file_type = pathinfo($filename, PATHINFO_EXTENSION);

            $target_dir = "../uploads/thesis/";
            $target_file = $target_dir . basename($filename);

            if (move_uploaded_file($tmp_name, $target_file)) {
                mysqli_query($conn, "
                    INSERT INTO files (thesis_id, file_path, file_type, uploaded_at)
                    VALUES ($thesisID, '$filename', '$file_type', NOW())
                ");
            }
        }


        foreach ($_FILES['thesis_files']['name'] as $key => $filename) {
            $tmp_name = $_FILES['thesis_files']['tmp_name'][$key];


            if (empty($filename) || !is_uploaded_file($tmp_name)) {
                continue;
            }

            $file_type = pathinfo($filename, PATHINFO_EXTENSION);


            $allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];
            $max_size = 10 * 1024 * 1024; // 10 MB

            if (!in_array(strtolower($file_type), $allowed_types)) {
                continue;
            }

            if ($_FILES['thesis_files']['size'][$key] > $max_size) {
                continue;
            }

            $target_dir = "../uploads/thesis/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $new_filename = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $filename);
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $escaped_filename = mysqli_real_escape_string($conn, $new_filename);
                mysqli_query($conn, "
            INSERT INTO files (thesis_id, file_path, file_type, uploaded_at)
            VALUES ($thesisID, '$escaped_filename', '$file_type', NOW())
        ");
            }
        }


        mysqli_query($conn, "
            INSERT INTO activity_logs (user_id, action, logged_at)
            VALUES ({$student['user_id']}, 'Submitted thesis: $title', NOW())
        ");

        $message = "Thesis submitted successfully!";
    } else {
        $message = "Error submitting thesis: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Submit Thesis</title>
    <?php include('header.php') ?>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            padding: 2rem;
        }

        h2 {
            color: #007bff;
        }

        form {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        input,
        textarea,
        select,
        button {
            padding: 0.5rem;
            font-size: 1rem;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #ffd700;
            font-weight: bold;
            cursor: pointer;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.1s, box-shadow 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
        }

        .message {
            text-align: center;
            font-weight: bold;
            color: green;
        }
    </style>
</head>

<body>


    <h2>Submit Thesis</h2>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Thesis Title" required>
        <textarea name="abstract" placeholder="Abstract" required rows="4"></textarea>
        <input type="text" name="keywords" placeholder="Keywords (comma separated)" required>
        <select name="adviser_id" required>
            <option value="">Select Adviser</option>
            <?php while ($f = mysqli_fetch_assoc($faculty_list)): ?>
                <option value="<?= $f['user_id'] ?>"><?= htmlspecialchars($f['full_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <select name="department_id" required>
            <option value="">Select Department</option>
            <?php while ($d = mysqli_fetch_assoc($departments)): ?>
                <option value="<?= $d['department_id'] ?>">
                    <?= htmlspecialchars($d['department_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>


        <select name="program_id" required>
            <option value="">Select Program</option>
            <?php while ($p = mysqli_fetch_assoc($programs)): ?>
                <option value="<?= $p['program_id'] ?>">
                    <?= htmlspecialchars($p['program_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <select name="year_level" required>
            <option value="">Select Year Level</option>
            <option value="1">First Year</option>
            <option value="2">Second Year</option>
            <option value="3">Third Year</option>
            <option value="4">Fourth Year</option>
        </select>

        <input type="file" name="thesis_files[]" multiple required>

        <button name="submit_thesis">Submit Thesis</button>

    </form>



</body>

</html>
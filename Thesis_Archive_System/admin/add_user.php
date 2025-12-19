<?php
include("../config/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$message = '';

// Fetch departments
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY department_name");
$programs = mysqli_query($conn, "SELECT * FROM programs ORDER BY program_name");

if (isset($_POST['add_user'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $role = $_POST['role'];
    $department_id = !empty($_POST['department_id']) ? (int)$_POST['department_id'] : 'NULL';
    $program_id = !empty($_POST['program_id']) ? (int)$_POST['program_id'] : 'NULL';

    if (!$name || !$email || !$password || !$role) {
        $message = 'Please fill required fields.';
    } else {
        // check duplicate email
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $message = 'Email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $deptSql = ($department_id === 'NULL') ? 'NULL' : $department_id;
            $progSql = ($program_id === 'NULL') ? 'NULL' : $program_id;
            $sql = "INSERT INTO users (full_name, email, password, role, department_id, program_id) VALUES ('{$name}','{$email}','{$hash}','{$role}', {$deptSql}, {$progSql})";
            if (mysqli_query($conn, $sql)) {
                // Log activity
                mysqli_query($conn, "INSERT INTO activity_logs (user_id, action) VALUES ({$_SESSION['user']['user_id']}, 'Added new user: $email')");
                header('Location: users.php?msg=User added');
                exit;
            } else {
                $message = 'DB error: ' . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add User</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .message {
            margin-top: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <?php include_once(__DIR__ . '/header.php'); ?>

    <main class="admin-container">
        <div class="card">
            <h2>Add User</h2>

            <?php if ($message): ?>
                <div class="message" style="color:#a00;"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="faculty">Faculty</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Department (optional)</label>
                    <select name="department_id">
                        <option value="">-- None --</option>
                        <?php while ($d = mysqli_fetch_assoc($departments)): ?>
                            <option value="<?= $d['department_id'] ?>"><?= htmlspecialchars($d['department_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Program (optional)</label>
                    <select name="program_id">
                        <option value="">-- None --</option>
                        <?php while ($p = mysqli_fetch_assoc($programs)): ?>
                            <option value="<?= $p['program_id'] ?>"><?= htmlspecialchars($p['program_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button class="btn btn-primary" type="submit" name="add_user">Add User</button>
            </form>
        </div>
    </main>

</body>

</html>
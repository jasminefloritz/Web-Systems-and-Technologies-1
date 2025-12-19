<?php
include("../config/db.php");
// Only admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit;
}
$id = (int)$_GET['id'];

// fetch user
$uRes = mysqli_query($conn, "SELECT * FROM users WHERE user_id={$id}");
if (mysqli_num_rows($uRes) === 0) {
    header('Location: users.php');
    exit;
}
$user = mysqli_fetch_assoc($uRes);

// Fetch departments and programs
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY department_name");
$programs = mysqli_query($conn, "SELECT * FROM programs ORDER BY program_name");

$message = '';

if (isset($_POST['save_user'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $role = $_POST['role'];
    $department_id = !empty($_POST['department_id']) ? (int)$_POST['department_id'] : 'NULL';
    $program_id = !empty($_POST['program_id']) ? (int)$_POST['program_id'] : 'NULL';

    // check duplicate email
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}' AND user_id!={$id}");
    if (mysqli_num_rows($check) > 0) {
        $message = 'Email already used by another account.';
    } else {

        if ($user['role'] === 'admin' && $role !== 'admin') {
            $adminCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role='admin'"))['c'];
            if ($adminCount <= 1) {
                $message = 'Cannot remove admin role: at least one admin required.';
            }
        }

        if (!$message) {
            $deptSql = ($department_id === 'NULL') ? 'NULL' : $department_id;
            $progSql = ($program_id === 'NULL') ? 'NULL' : $program_id;
            $sql = "UPDATE users SET full_name='{$name}', email='{$email}', role='{$role}', department_id={$deptSql}, program_id={$progSql} WHERE user_id={$id}";
            if (!mysqli_query($conn, $sql)) {
                $message = 'DB error: ' . mysqli_error($conn);
            } else {
                //pass change
                if (!empty($_POST['password'])) {
                    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    mysqli_query($conn, "UPDATE users SET password='{$hash}' WHERE user_id={$id}");
                }
                // Log activity
                mysqli_query($conn, "INSERT INTO activity_logs (user_id, action) VALUES ({$_SESSION['user']['user_id']}, 'Updated user: $email')");
                header('Location: users.php?msg=User updated');
                exit;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #333;
            padding: 1rem;
            color: #fff;
        }

        nav a {
            color: #fff;
            margin-right: 1rem;
            text-decoration: none;
        }

        main {
            padding: 2rem;
        }

        form {
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            max-width: 600px
        }

        label {
            display: block;
            margin-top: 0.75rem
        }

        input,
        select {
            width: 100%;
            padding: 0.6rem;
            border-radius: 6px;
            border: 1px solid #e5e7eb
        }

        .btn {
            background: #0066ff;
            color: #fff;
            padding: 0.6rem 0.9rem;
            border: none;
            border-radius: 8px;
            margin-top: 0.8rem;
            cursor: pointer
        }

        .msg {
            margin: 0.75rem 0;
            color: green
        }

        .err {
            margin: 0.75rem 0;
            color: #a00
        }
    </style>
</head>

<body>
    <nav>
        <a href="dashboard.php">Admin</a> |
        <a href="users.php">Manage Users</a>
    </nav>
    <main>
        <h1>Edit User</h1>
        <?php if ($message): ?><div class="err"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <form method="POST">
            <label>Full name</label>
            <input type="text" name="full_name" required value="<?= htmlspecialchars($user['full_name']) ?>">
            <label>Email</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
            <label>New Password (leave blank to keep)</label>
            <input type="password" name="password" placeholder="New password">
            <label>Role</label>
            <select name="role" required>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="faculty" <?= $user['role'] === 'faculty' ? 'selected' : '' ?>>Faculty</option>
                <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
            </select>
            <label>Department (optional)</label>
            <select name="department_id">
                <option value="">-- None --</option>
                <?php while ($d = mysqli_fetch_assoc($departments)): ?>
                    <option value="<?= $d['department_id'] ?>" <?= $d['department_id'] == $user['department_id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['department_name']) ?></option>
                <?php endwhile; ?>
            </select>
            <label>Program (optional)</label>
            <select name="program_id">
                <option value="">-- None --</option>
                <?php while ($p = mysqli_fetch_assoc($programs)): ?>
                    <option value="<?= $p['program_id'] ?>" <?= $p['program_id'] == $user['program_id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['program_name']) ?></option>
                <?php endwhile; ?>
            </select>
            <button class="btn" type="submit" name="save_user">Save Changes</button>
        </form>
    </main>
</body>

</html>
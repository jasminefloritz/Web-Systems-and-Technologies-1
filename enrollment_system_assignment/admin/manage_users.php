<?php
include("../config/config.php");

// Check admin
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['user'];
$message = "";

// Fetch all users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY role, name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - User Management</title>
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

<!-- Users Management -->
<h4>Users</h4>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Profile</th>
            <th>Signature</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($u = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= $u['role'] ?></td>
                <td>
                    <?php if(!empty($u['profile_pic'])): ?>
                        <img src="../uploads/profiles/<?= $u['profile_pic'] ?>" width="50">
                    <?php else: ?>
                        <span>No profile</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if(!empty($u['signature'])): ?>
                        <img src="../uploads/signatures/<?= $u['signature'] ?>" width="50">
                    <?php else: ?>
                        <span>No signature</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>


<?php
include("config.php");

if($_SESSION['user']['user_type'] != 'admin'){
    echo "ACCESS DENIED";
    exit;
}
// DELETE 
if (isset($_GET['delete_id'])) {

    $delete_id = $_GET['delete_id'];


    if ($delete_id == $_SESSION['user']['id']) {
        echo "<script>alert('You cannot delete your own account!');</script>";
    } else {
        mysqli_query($conn, "DELETE FROM users WHERE id = $delete_id");
        echo "<script>alert('User deleted successfully!');</script>";
    }


    echo "<script>window.location='admin_users.php';</script>";
    exit;
}


// SEARCH
$keyword = "";
if(isset($_GET['search'])){
    $keyword = $_GET['search'];
}

// SORT
$sort = "fullname";
$order = "ASC";

if(isset($_GET['sort'])){
    $sort = $_GET['sort'];
    $order = $_GET['order'];
}

$sql = "SELECT * FROM users
        WHERE fullname LIKE '%$keyword%'
           OR email LIKE '%$keyword%'
           OR user_type LIKE '%$keyword%'
        ORDER BY $sort $order";

$result = mysqli_query($conn,$sql);
?>

<head>
   <link rel="stylesheet" href="style.css">
</head>
<?php include("header.php"); ?>

<form method="GET" class="search-box">
    <input type="text" name="search" 
           value="<?php echo htmlspecialchars($keyword); ?>" 
           placeholder="Search by name, email or user type">

    <button type="submit">Search</button>
    <button type="button" onclick="window.location='admin_register.php'">Add User</button>

</form>


<table border="1" cellpadding="5">
<tr>
    <th><a href="?sort=fullname&order=ASC">Name ▲</a> | <a href="?sort=fullname&order=DESC">▼</a></th>
    <th><a href="?sort=email&order=ASC">Email ▲</a> | <a href="?sort=email&order=DESC">▼</a></th>
    <th><a href="?sort=user_type&order=ASC">User Type ▲</a> | <a href="?sort=user_type&order=DESC">▼</a></th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?php echo $row['fullname']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['user_type']; ?></td>
    <td>
        <a href="admin_users.php?delete_id=<?php echo $row['id']; ?>" 
   onclick="return confirm('Are you sure you want to delete this user?');">
    <img src="delete_button.png" alt="Delete" width="25" height="25">
</a>



    </td>
</tr>
<?php endwhile; ?>
</table>



<?php
include("../config/db.php");

if (isset($_POST['add_dept'])) {
    mysqli_query($conn, "INSERT INTO departments(department_name)
    VALUES('{$_POST['department']}')");
}

if (isset($_POST['add_prog'])) {
    mysqli_query($conn, "INSERT INTO programs(program_name,department_id)
    VALUES('{$_POST['program']}','{$_POST['department_id']}')");
}
?>

<h3>Add Department</h3>
<form method="POST">
    <input name="department">
    <button name="add_dept">Add</button>
</form>

<h3>Add Program</h3>
<form method="POST">
    <input name="program">
    <input name="department_id">
    <button name="add_prog">Add</button>
</form>
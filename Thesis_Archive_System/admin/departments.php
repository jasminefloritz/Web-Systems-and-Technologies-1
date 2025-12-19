<?php
include("../config/db.php");
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') exit;

$message = '';

// Add department
if (isset($_POST['add'])) {
  $name_raw = trim($_POST['name'] ?? '');
  $program_raw = trim($_POST['program'] ?? '');
  if ($name_raw !== '') {
    $name = mysqli_real_escape_string($conn, $name_raw);
    mysqli_query($conn, "INSERT INTO departments(department_name) VALUES('{$name}')");
    $dept_id = mysqli_insert_id($conn);
    if ($program_raw !== '') {
      $program = mysqli_real_escape_string($conn, $program_raw);
      mysqli_query($conn, "INSERT INTO programs(program_name, department_id) VALUES('{$program}', {$dept_id})");
    }
    header('Location: departments.php?msg=Department+added');
    exit;
  }
}

// Delete 
if (isset($_POST['delete_id'])) {
  $id = (int)$_POST['delete_id'];
  mysqli_query($conn, "DELETE FROM departments WHERE department_id={$id}");
  header('Location: departments.php?msg=Department+deleted');
  exit;
}

// Update 
if (isset($_POST['update_department'])) {
  $id = (int)$_POST['id'];
  $name_raw = trim($_POST['name'] ?? '');
  $program_raw = trim($_POST['program'] ?? '');
  if ($name_raw !== '') {
    $name = mysqli_real_escape_string($conn, $name_raw);
    mysqli_query($conn, "UPDATE departments SET department_name='{$name}' WHERE department_id={$id}");
    if ($program_raw !== '') {
      $program = mysqli_real_escape_string($conn, $program_raw);
      $existing = mysqli_query($conn, "SELECT program_id FROM programs WHERE department_id={$id} LIMIT 1");
      if (mysqli_num_rows($existing) > 0) {
        mysqli_query($conn, "UPDATE programs SET program_name='{$program}' WHERE department_id={$id} LIMIT 1");
      } else {
        mysqli_query($conn, "INSERT INTO programs(program_name, department_id) VALUES('{$program}', {$id})");
      }
    }
    header('Location: departments.php?msg=Department+updated');
    exit;
  } else {
    $message = 'Name cannot be empty.';
  }
}

// Fetch departments
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY department_id DESC");


$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editRow = null;
$editProgram = null;
if ($editId) {
  $res = mysqli_query($conn, "SELECT * FROM departments WHERE department_id={$editId} LIMIT 1");
  $editRow = $res ? mysqli_fetch_assoc($res) : null;
  if ($editRow) {
    $progRes = mysqli_query($conn, "SELECT * FROM programs WHERE department_id={$editId} LIMIT 1");
    $editProgram = $progRes ? mysqli_fetch_assoc($progRes) : null;
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Departments</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>

  <?php include_once(__DIR__ . '/header.php'); ?>

  <main class="admin-container">
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <h2 style="margin:0;">Departments</h2>
        <div class="actions-row">
          <a href="departments.php" class="btn btn-primary">Refresh</a>
        </div>
      </div>

      <?php if (!empty($_GET['msg'])): ?>
        <div class="message" style="margin-top:0.75rem;color:green;font-weight:600"><?= htmlspecialchars($_GET['msg']) ?></div>
      <?php endif; ?>
      <?php if (!empty($message)): ?>
        <div class="message" style="margin-top:0.75rem;color:#a00;font-weight:600"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <!-- Add / Edit Form -->
      <?php if ($editRow): ?>
        <form method="POST" style="display:flex;gap:0.5rem;align-items:center;margin-top:0.75rem;flex-wrap:wrap;">
          <input type="hidden" name="id" value="<?= $editRow['department_id']; ?>">
          <input type="text" name="name" value="<?= htmlspecialchars($editRow['department_name']); ?>" placeholder="Department Name" required style="flex:1;min-width:200px;padding:0.5rem;border-radius:6px;border:1px solid #e5e7eb;">
          <input type="text" name="program" value="<?= htmlspecialchars($editProgram['program_name'] ?? ''); ?>" placeholder="Program Name (optional)" style="flex:1;min-width:200px;padding:0.5rem;border-radius:6px;border:1px solid #e5e7eb;">
          <button type="submit" name="update_department" class="btn btn-primary">Update</button>
          <a href="departments.php" class="btn">Cancel</a>
        </form>
      <?php else: ?>
        <form method="POST" style="display:flex;gap:0.5rem;align-items:center;margin-top:0.75rem;flex-wrap:wrap;">
          <input type="text" name="name" placeholder="Department Name" required style="flex:1;min-width:200px;padding:0.5rem;border-radius:6px;border:1px solid #e5e7eb;">
          <input type="text" name="program" placeholder="Program Name (optional)" style="flex:1;min-width:200px;padding:0.5rem;border-radius:6px;border:1px solid #e5e7eb;">
          <button type="submit" name="add" class="btn btn-primary">Add</button>
        </form>
      <?php endif; ?>

      <!-- Live search / filter (reusable) -->
      <?php include_once(__DIR__ . '/live_search.php'); ?>

      <div class="table-responsive">
        <table class="admin-table" style="margin-top:1rem;">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($d = mysqli_fetch_assoc($departments)): ?>
              <tr>
                <td><?= $d['department_id']; ?></td>
                <td><?= htmlspecialchars($d['department_name']); ?></td>
                <td>
                  <div class="table-actions">
                    <a href="?edit=<?= $d['department_id']; ?>" class="btn btn-primary">Edit</a>
                    <form method="POST" action="departments.php" onsubmit="return confirm('Delete this department?');" style="display:inline;margin:0;">
                      <input type="hidden" name="delete_id" value="<?= $d['department_id']; ?>">
                      <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>

</html>
<?php
include("../config/db.php");
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') exit;

$message = '';

// Add program
if (isset($_POST['add'])) {
  $name_raw = trim($_POST['name'] ?? '');
  $dept = isset($_POST['department_id']) ? (int)$_POST['department_id'] : 0;
  if ($name_raw !== '' && $dept > 0) {
    $name = mysqli_real_escape_string($conn, $name_raw);
    mysqli_query($conn, "INSERT INTO programs(program_name, department_id) VALUES('{$name}',{$dept})");
    header('Location: programs.php?msg=Program+added');
    exit;
  }
}

// Delete 
if (isset($_POST['delete_id'])) {
  $id = (int)$_POST['delete_id'];
  mysqli_query($conn, "DELETE FROM programs WHERE program_id={$id}");
  header('Location: programs.php?msg=Program+deleted');
  exit;
}

// Update program
if (isset($_POST['update_program'])) {
  $id = (int)$_POST['id'];
  $name_raw = trim($_POST['name'] ?? '');
  $dept = isset($_POST['department_id']) ? (int)$_POST['department_id'] : 0;
  if ($name_raw !== '' && $dept > 0) {
    $name = mysqli_real_escape_string($conn, $name_raw);
    mysqli_query($conn, "UPDATE programs SET program_name='{$name}', department_id={$dept} WHERE program_id={$id}");
    header('Location: programs.php?msg=Program+updated');
    exit;
  } else {
    $message = 'Name and department are required.';
  }
}

// Fetch programs and departments
$dept_filter = isset($_GET['dept']) ? (int)$_GET['dept'] : 0;
$where = $dept_filter ? "WHERE pr.department_id=$dept_filter" : '';
$programs = mysqli_query($conn, "SELECT pr.*, d.department_name FROM programs pr 
LEFT JOIN departments d ON pr.department_id=d.department_id $where ORDER BY pr.program_id DESC");
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY department_name");

// Edit
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editRow = null;
if ($editId) {
  $res = mysqli_query($conn, "SELECT * FROM programs WHERE program_id={$editId} LIMIT 1");
  $editRow = $res ? mysqli_fetch_assoc($res) : null;
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Programs</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>

  <?php include_once(__DIR__ . '/header.php'); ?>

  <main class="admin-container">
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <h2 style="margin:0;">Programs</h2>
        <div class="actions-row">
          <a href="programs.php" class="btn btn-primary">Refresh</a>
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
        <form method="POST" style="display:flex;gap:0.5rem;align-items:center;margin-top:0.75rem;">
          <input type="hidden" name="id" value="<?= $editRow['program_id']; ?>">
          <input type="text" name="name" value="<?= htmlspecialchars($editRow['program_name']); ?>" required style="flex:1;padding:0.5rem;border-radius:6px;border:1px solid #e5e7eb;">
          <select name="department_id" required style="padding:0.5rem;border-radius:6px;border:1px solid #e5e7eb;">
            <?php while ($d = mysqli_fetch_assoc($departments)): ?>
              <option value="<?= $d['department_id']; ?>" <?= ($d['department_id'] == $editRow['department_id']) ? 'selected' : '' ?>><?= htmlspecialchars($d['department_name']); ?></option>
            <?php endwhile; ?>
          </select>
          <button type="submit" name="update_program" class="btn btn-primary">Update</button>
          <a href="programs.php" class="btn">Cancel</a>
        </form>
      <?php else: ?>
        <form method="POST" style="display:flex;gap:0.5rem;align-items:center;margin-top:0.75rem;">
          <input type="text" name="name" placeholder="Program Name" required style="flex:1;padding:0.5rem;border-radius:6px;border:1px solid #e5e7eb;">
          <select name="department_id" required style="padding:0.5rem;border-radius:6px;border:1px solid #e5e7eb;">
            <option value="">Select Department</option>
            <?php while ($d = mysqli_fetch_assoc($departments)): ?>
              <option value="<?= $d['department_id']; ?>"><?= htmlspecialchars($d['department_name']); ?></option>
            <?php endwhile; ?>
          </select>
          <button type="submit" name="add" class="btn btn-primary">Add</button>
        </form>
      <?php endif; ?>

      <!-- Live search / filter -->
      <?php include_once(__DIR__ . '/live_search.php'); ?>

      <div style="margin-top:1rem;">
        <div class="live-search-container" style="display:flex;gap:.5rem;align-items:center;margin-top:0.75rem;">
          <label for="dept_filter" style="margin:0;">Filter by Department:</label>
          <select id="dept_filter" style="padding:0.5rem;border-radius:4px;border:1px solid #ddd;">
            <option value="">All Departments</option>
            <?php mysqli_data_seek($departments, 0);
            while ($d = mysqli_fetch_assoc($departments)): ?>
              <option value="<?= $d['department_id']; ?>"><?= htmlspecialchars($d['department_name']); ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="admin-table" style="margin-top:1rem;">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Department</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($p = mysqli_fetch_assoc($programs)): ?>
              <tr>
                <td><?= $p['program_id']; ?></td>
                <td><?= htmlspecialchars($p['program_name']); ?></td>
                <td><?= htmlspecialchars($p['department_name']); ?></td>
                <td>
                  <div class="table-actions">
                    <a href="?edit=<?= $p['program_id']; ?>" class="btn btn-primary">Edit</a>
                    <form method="POST" action="programs.php" onsubmit="return confirm('Delete this program?');" style="display:inline;margin:0;">
                      <input type="hidden" name="delete_id" value="<?= $p['program_id']; ?>">
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

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const deptFilter = document.getElementById("dept_filter");
      const table = document.querySelector(".admin-table");
      const tbody = table.querySelector("tbody");
      const rows = Array.from(tbody.querySelectorAll("tr"));

      deptFilter.addEventListener("change", function() {
        const selectedDept = this.value.toLowerCase();
        rows.forEach(row => {
          if (row.classList.contains("no-results-row")) return;
          const cells = row.querySelectorAll("td");
          if (cells.length >= 3) {
            const deptCell = cells[2].textContent.toLowerCase().trim();
            const matches = selectedDept === "" || deptCell.includes(selectedDept);
            row.style.display = matches ? "" : "none";
          }
        });
      });
    });
  </script>

</body>

</html>
<?php
include("../config/db.php");
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') exit;

$message = '';

// Fetch approved theses 
$sql = "SELECT t.*, u.full_name AS author, u.email, d.department_name, p.program_name, a.decision_date,
        YEAR(t.submitted_at) AS year, adv.full_name AS adviser, t.keywords
        FROM theses t
        LEFT JOIN users u ON t.author_id = u.user_id
        LEFT JOIN departments d ON u.department_id = d.department_id
        LEFT JOIN programs p ON u.program_id = p.program_id
        LEFT JOIN approvals a ON t.thesis_id = a.thesis_id
        LEFT JOIN users adv ON t.adviser_id = adv.user_id
        WHERE a.decision = 'approved'
        ORDER BY a.decision_date DESC";
$approved_theses = mysqli_query($conn, $sql);
if (!$approved_theses) {
  error_log('DB error (admin/archives.php): ' . mysqli_error($conn) . ' -- SQL: ' . $sql);
  $approved_theses = null;
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Thesis Library</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    .admin-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }

    .admin-table th,
    .admin-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .admin-table th {
      background-color: #007bff;
      color: #f2f2f2ff;
    }

    .filters {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      margin-top: 1rem;
      align-items: center;
    }

    .filters label {
      margin-right: 0.5rem;
    }

    .filters input,
    .filters select {
      padding: 0.5rem;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .btn {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      background-color: #007bff;
      color: white;
    }

    .btn-primary {
      background-color: #007bff;
      color: white;
    }
  </style>
  <script src="../js/jquery-3.7.1.js"></script>
  <script>
    $(document).ready(function() {
      function filterTable() {
        var title = $('#title_filter').val().toLowerCase();
        var author = $('#author_filter').val().toLowerCase();
        var year = $('#year_filter').val();
        var adviser = $('#adviser_filter').val().toLowerCase();
        var keywords = $('#keywords_filter').val().toLowerCase();

        $('.admin-table tbody tr').each(function() {
          var row = $(this);
          var rowTitle = row.find('td:nth-child(2)').text().toLowerCase();
          var rowAuthor = row.find('td:nth-child(3)').text().toLowerCase();
          var rowYear = row.find('td:nth-child(4)').text();
          var rowAdviser = row.find('td:nth-child(5)').text().toLowerCase();
          var rowKeywords = row.find('td:nth-child(6)').text().toLowerCase();

          var show = (!title || rowTitle.includes(title)) &&
            (!author || rowAuthor.includes(author)) &&
            (!year || rowYear === year) &&
            (!adviser || rowAdviser.includes(adviser)) &&
            (!keywords || rowKeywords.includes(keywords));

          row.toggle(show);
        });
      }

      $('#title_filter, #author_filter, #year_filter, #adviser_filter, #keywords_filter').on('input change', filterTable);
    });
  </script>
</head>

<body>

  <?php include_once(__DIR__ . '/header.php'); ?>

  <main class="admin-container">
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <h2 style="margin:0;">Thesis Library</h2>
        <div class="actions-row">
          <a href="archives.php" class="btn btn-primary">Refresh</a>
        </div>
      </div>

      <?php if (isset($_SESSION['flash_message'])): ?>
        <p class="message" style="text-align:center;color:green;font-weight:600;margin-top:0.75rem"><?php echo htmlspecialchars($_SESSION['flash_message']);
                                                                                                    unset($_SESSION['flash_message']); ?></p>
      <?php endif; ?>

      <?php if (!empty($message)): ?>
        <div class="message" style="margin-top:0.75rem;color:#a00;font-weight:600"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <div class="filters">
        <label>Title: <input type="text" id="title_filter" placeholder="Search title..."></label>
        <label>Author: <input type="text" id="author_filter" placeholder="Search author..."></label>
        <label>Year: <input type="text" id="year_filter" placeholder="e.g. 2023"></label>
        <label>Adviser: <input type="text" id="adviser_filter" placeholder="Search adviser..."></label>
        <label>Keywords: <input type="text" id="keywords_filter" placeholder="Search keywords..."></label>
      </div>

      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Author</th>
              <th>Year</th>
              <th>Adviser</th>
              <th>Keywords</th>
              <th>Download</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($approved_theses): ?>
              <?php while ($t = mysqli_fetch_assoc($approved_theses)): ?>
                <tr>
                  <td><?= $t['thesis_id']; ?></td>
                  <td><?= htmlspecialchars($t['title']) ?></td>
                  <td><?= htmlspecialchars($t['author'] ?? 'Unknown') ?></td>
                  <td><?= htmlspecialchars($t['year'] ?? '') ?></td>
                  <td><?= htmlspecialchars($t['adviser'] ?? 'Unknown') ?></td>
                  <td><?= htmlspecialchars($t['keywords'] ?? '') ?></td>
                  <td>
                    <a href="download_zip.php?id=<?= $t['thesis_id']; ?>" class="btn btn-primary">Download</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" style="text-align: center;">No approved theses found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>

</html>
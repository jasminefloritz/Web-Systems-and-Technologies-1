<?php
include("config/db.php");

$search = $_GET['search'] ?? '';

$query = "SELECT t.*, f.file_path 
          FROM theses t 
          JOIN files f ON t.thesis_id=f.thesis_id
          WHERE t.status='approved'
          AND (t.title LIKE '%$search%' 
          OR t.keywords LIKE '%$search%'
          OR t.year LIKE '%$search%')";

$result = mysqli_query($conn, $query);
?>

<form>
    <input name="search" placeholder="Search title, year, keyword">
    <button>Search</button>
</form>

<?php while ($t = mysqli_fetch_assoc($result)): ?>
    <h3><?= $t['title'] ?></h3>
    <p><?= $t['abstract'] ?></p>
    <a href="uploads/theses/<?= $t['file_path'] ?>" download>Download</a>
<?php endwhile; ?>
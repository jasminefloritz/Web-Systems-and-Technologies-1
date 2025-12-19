<?php
include("../config/db.php");

$theses = mysqli_query($conn, "SELECT * FROM theses WHERE status='pending'");

if (isset($_POST['review'])) {
    $id = $_POST['thesis_id'];
    $decision = $_POST['decision'];
    $comment = $_POST['comment'];

    mysqli_query($conn, "UPDATE theses SET status='$decision' WHERE thesis_id=$id");
    mysqli_query($conn, "INSERT INTO approvals (thesis_id, reviewer_id, decision)
    VALUES ('$id','{$_SESSION['user']['user_id']}','$decision')");
    mysqli_query($conn, "INSERT INTO review_logs (thesis_id, reviewer_id, comment)
    VALUES ('$id','{$_SESSION['user']['user_id']}','$comment')");
}
?>

<?php while ($t = mysqli_fetch_assoc($theses)): ?>
    <form method="POST">
        <h3><?= $t['title'] ?></h3>
        <textarea name="comment"></textarea>
        <input type="hidden" name="thesis_id" value="<?= $t['thesis_id'] ?>">
        <button name="decision" value="approved">Approve</button>
        <button name="decision" value="rejected">Reject</button>
    </form>
<?php endwhile; ?>
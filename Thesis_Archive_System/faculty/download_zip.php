<?php
include("../config/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
    header("Location: ../auth/login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    http_response_code(404);
    echo "Thesis not found.";
    exit;
}


$faculty_id = (int)$_SESSION['user']['user_id'];
$check = mysqli_query($conn, "SELECT adviser_id FROM theses WHERE thesis_id=$id AND (adviser_id=$faculty_id OR status='pending')");
if (!$check || mysqli_num_rows($check) === 0) {
    http_response_code(403);
    echo "Access denied.";
    exit;
}

// Fetch files
$files = mysqli_query($conn, "SELECT file_path FROM files WHERE thesis_id=$id");
if (!$files || mysqli_num_rows($files) === 0) {
    http_response_code(404);
    echo "No files found.";
    exit;
}

$zip = new ZipArchive();
$zipName = "thesis_$id.zip";
$zipPath = sys_get_temp_dir() . '/' . $zipName;

if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
    http_response_code(500);
    echo "Could not create ZIP file.";
    exit;
}

while ($f = mysqli_fetch_assoc($files)) {
    $filePath = __DIR__ . '/../uploads/thesis/' . $f['file_path'];
    if (file_exists($filePath)) {
        $zip->addFile($filePath, basename($f['file_path']));
    }
}

$zip->close();

// Download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $zipName . '"');
header('Content-Length: ' . filesize($zipPath));
readfile($zipPath);

// Clean up
unlink($zipPath);
exit;

<?php


if (session_status() === PHP_SESSION_NONE) session_start();

$student = $_SESSION['user'] ?? null;
$message = $message ?? '';

function handle_upload_shared($fieldName, $uploadDir, $dbField, $conn, &$student, &$message)
{
    if (!isset($_FILES[$fieldName]) || !is_uploaded_file($_FILES[$fieldName]['tmp_name'])) return;

    $original = $_FILES[$fieldName]['name'];
    $tmp_name = $_FILES[$fieldName]['tmp_name'];


    $safe = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $original);

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $target_file = rtrim($uploadDir, '/') . '/' . $safe;

    if (move_uploaded_file($tmp_name, $target_file)) {
        $escaped = mysqli_real_escape_string($conn, $safe);
        $user_id = (int)$student['user_id'];
        mysqli_query($conn, "UPDATE users SET {$dbField}='{$escaped}' WHERE user_id={$user_id}");

        $_SESSION['user'][$dbField] = $safe;
        $student[$dbField] = $safe;
        $message = ucfirst(str_replace('_', ' ', $dbField)) . ' updated successfully.';
    } else {
        $message = 'Failed to upload file. Please try again.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($student)) {
        if (isset($_POST['upload_profile'])) {
            handle_upload_shared('profile_picture', '../uploads/profiles', 'profile_picture', $conn, $student, $message);
        }
        if (isset($_POST['upload_signature'])) {
            handle_upload_shared('signature', '../uploads/signatures', 'signature', $conn, $student, $message);
        }
    }
}

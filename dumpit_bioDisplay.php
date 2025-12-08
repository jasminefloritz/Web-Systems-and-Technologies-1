<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty($_POST['name'])) {
        $errors[] = "Name is required.";
    }


    if (empty($_POST['email']) || strpos($_POST['email'], '@') === false) {
        $errors[] = "Email must contain '@'.";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }


    if (!empty($_POST['cellphone']) && !preg_match('/^[0-9]{11}$/', $_POST['cellphone'])) {
        $errors[] = "Cellphone must be exactly 11 digits.";
    }

    if (!empty($_POST['dob'])) {
        $dob = strtotime($_POST['dob']);
        $today = strtotime(date("Y-m-d"));
        if ($dob > $today) {
            $errors[] = "Date of birth cannot be in the future.";
        }
    }

  
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetFile = $targetDir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;

   
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check === false) {
        $uploadOk = 0;
        $errors[] = "Uploaded file is not a valid image.";
    }


    if (file_exists($targetFile)) {
        $uploadOk = 0;
        $errors[] = "This image already exists. Please rename your file.";
    }

  
    if ($uploadOk == 1 && empty($errors)) {
        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile);
    }

  
    if (!empty($errors)) {
        foreach ($errors as $e) {
            echo "<p style='color:red;'>$e</p>";
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bio-Data Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            width: 900px;
            margin: auto;
            border: 1px solid black;
            padding: 20px;
            position: relative;
        }
        h2 {
            text-align: center;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 5px;
            vertical-align: top;
        }
        .section {
            background: black;
            color: white;
            font-weight: bold;
            padding: 5px;
            margin-top: 15px;
        }
        .photo {
            position: absolute;
            top: 20px;
            right: 20px;
            border: 1px solid black;
            width: 120px;
            height: 120px;
            overflow: hidden;
        }
        .photo img {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>BIO-DATA</h2>

    <?php if ($uploadOk == 1): ?>
        <div class="photo">
            <img src="<?= $targetFile ?>" alt="Uploaded Photo">
        </div>
    <?php endif; ?>


    <div class="section">PERSONAL DATA</div><br>
    <table border="0">
        <tr>
            <td><b>Position Desired:</b> <?= $_POST['position'] ?></td>
            <td><b>Date:</b> <?= $_POST['date'] ?></td>
        </tr>
        <tr>
            <td><b>Name:</b> <?= $_POST['name'] ?></td>
            <td><b>Gender:</b> <?= $_POST['gender'] ?></td>
        </tr>
        <tr>
            <td><b>City Address:</b> <?= $_POST['city_address'] ?></td>
            <td><b>Provincial Address:</b> <?= $_POST['prov_address'] ?></td>
        </tr>
        <tr>
            <td><b>Telephone:</b> <?= $_POST['telephone'] ?></td>
            <td><b>Cellphone:</b> <?= $_POST['cellphone'] ?></td>
        </tr>
        <tr>
            <td><b>Email:</b> <?= $_POST['email'] ?></td>
            <td><b>Date of Birth:</b> <?= $_POST['dob'] ?></td>
        </tr>
        <tr>
            <td><b>Birthplace:</b> <?= $_POST['birthplace'] ?></td>
            <td><b>Citizenship:</b> <?= $_POST['citizenship'] ?></td>
        </tr>
        <tr>
            <td><b>Height:</b> <?= $_POST['height'] ?></td>
            <td><b>Weight:</b> <?= $_POST['weight'] ?></td>
        </tr>
        <tr>
            <td><b>Religion:</b> <?= $_POST['religion'] ?></td>
            <td><b>Civil Status:</b> <?= $_POST['civil_status'] ?></td>
        </tr>
        <tr>
            <td><b>Spouse:</b> <?= $_POST['spouse'] ?></td>
            <td><b>Date of Birth:</b> <?= $_POST['spouse_dob'] ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Name of Children:</b> <?= nl2br($_POST['children']) ?></td>
        </tr>
        <tr>
            <td><b>Father’s Name:</b> <?= $_POST['father'] ?></td>
            <td><b>Occupation:</b> <?= $_POST['father_occ'] ?></td>
        </tr>
        <tr>
            <td><b>Mother’s Name:</b> <?= $_POST['mother'] ?></td>
            <td><b>Occupation:</b> <?= $_POST['mother_occ'] ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Language/Dialect Spoken:</b> <?= $_POST['language'] ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Person to contact in case of emergency:</b> <?= $_POST['emergency'] ?></td>
        </tr>
    </table>

 
    <div class="section">EDUCATIONAL BACKGROUND</div><br>
    <table border="0">
        <tr>
            <td><b>Elementary:</b> <?= $_POST['elem'] ?></td>
            <td><b>Year Graduated:</b> <?= $_POST['elem_year'] ?></td>
        </tr>
        <tr>
            <td><b>High School:</b> <?= $_POST['hs'] ?></td>
            <td><b>Year Graduated:</b> <?= $_POST['hs_year'] ?></td>
        </tr>
        <tr>
            <td><b>College:</b> <?= $_POST['college'] ?></td>
            <td><b>Year Graduated:</b> <?= $_POST['college_year'] ?></td>
        </tr>
        <tr>
            <td><b>Degree Received:</b> <?= $_POST['degree'] ?></td>
            <td><b>Special Skills:</b> <?= $_POST['skills'] ?></td>
        </tr>
    </table>

 
    <div class="section">EMPLOYMENT RECORD</div><br>
    <table border="0">
        <tr>
            <td><b>Company Name:</b> <?= $_POST['company1'] ?></td>
            <td><b>Position:</b> <?= $_POST['position1'] ?></td>
            <td><b>From:</b> <?= $_POST['from1'] ?></td>
            <td><b>To:</b> <?= $_POST['to1'] ?></td>
        </tr>
        <tr>
            <td><b>Company Name:</b> <?= $_POST['company2'] ?></td>
            <td><b>Position:</b> <?= $_POST['position2'] ?></td>
            <td><b>From:</b> <?= $_POST['from2'] ?></td>
            <td><b>To:</b> <?= $_POST['to2'] ?></td>
        </tr>
    </table>

 
    <div class="section">CHARACTER REFERENCE</div><br>
    <table border="0">
        <tr>
            <td><b>Name:</b> <?= $_POST['ref1_name'] ?></td>
            <td><b>Position:</b> <?= $_POST['ref1_pos'] ?></td>
            <td><b>Company:</b> <?= $_POST['ref1_comp'] ?></td>
            <td><b>Contact No:</b> <?= $_POST['ref1_contact'] ?></td>
        </tr>
        <tr>
            <td><b>Name:</b> <?= $_POST['ref2_name'] ?></td>
            <td><b>Position:</b> <?= $_POST['ref2_pos'] ?></td>
            <td><b>Company:</b> <?= $_POST['ref2_comp'] ?></td>
            <td><b>Contact No:</b> <?= $_POST['ref2_contact'] ?></td>
        </tr>
    </table>
</div>
</body>
</html>
<?php
?>

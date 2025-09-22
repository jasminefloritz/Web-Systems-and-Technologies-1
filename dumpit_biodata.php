<!DOCTYPE html>
<html>
<head>
    <title>Dumpit Bio-Data Form</title>
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
        input, textarea {
            width: 95%;
            padding: 5px;
        }
        .btn {
            margin-top: 20px;
            display: block;
            padding: 10px;
            background: black;
            color: white;
            border: none;
            cursor: pointer;
            width: 200px;
        }
        .btn:hover {
            background: #333;
        }

    </style>
</head>
<body>
<div class="container">
    <h2>BIO-DATA</h2>
    <form action="dumpit_bioDisplay.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">

    <div class="section">UPLOAD PHOTO</div><br>
        <input type="file" name="photo" accept="image/*" required>
        
       
        <div class="section">PERSONAL DATA</div><br>
        <table border="0">
            <tr>
                <td><b>Position Desired:</b> <input type="text" name="position" required></td>
                <td>Date: <input type="date" name="date" required></td>
            </tr>
            <tr>
                <td><b>Name:</b> <input type="text" name="name" required></td>
                <td><b>Gender:</b> 
                    <select name="gender" required>
                        <option value="">--Select--</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>City Address:</b> <input type="text" name="city_address" required></td>
                <td><b>Provincial Address:</b> <input type="text" name="prov_address"></td>
            </tr>
            <tr>
                <td><b>Telephone:</b> <input type="text" name="telephone"></td>
                <td>Cellphone: <input type="text" name="cellphone" required></td>
            </tr>
            <tr>
                <td><b>Email:</b> <input type="email" name="email" required></td>
                <td><b>Date of Birth:</b> <input type="date" name="dob" required></td>
            </tr>
            <tr>
                <td><b>Birthplace:</b> <input type="text" name="birthplace" required></td>
                <td><b>Citizenship:</b> <input type="text" name="citizenship" required></td>
            </tr>
            <tr>
                <td><b>Height:</b> <input type="text" name="height"></td>
                <td><b>Weight:</b> <input type="text" name="weight"></td>
            </tr>
            <tr>
                <td><b>Religion:</b> <input type="text" name="religion"></td>
                <td><b>Civil Status:</b> <input type="text" name="civil_status"></td>
            </tr>
            <tr>
                <td><b>Spouse:</b> <input type="text" name="spouse"></td>
                <td><b>Date of Birth:</b> <input type="date" name="spouse_dob"></td>
            </tr>
            <tr>
                <td colspan="2"><b>Name of Children:</b> <textarea name="children"></textarea></td>
            </tr>
            <tr>
                <td><b>Father’s Name:</b> <input type="text" name="father"></td>
                <td><b>Occupation:</b> <input type="text" name="father_occ"></td>
            </tr>
            <tr>
                <td><b>Mother’s Name:</b> <input type="text" name="mother"></td>
                <td><b>Occupation:</b> <input type="text" name="mother_occ"></td>
            </tr>
            <tr>
                <td colspan="2"><b>Language/Dialect Spoken:</b> <input type="text" name="language"></td>
            </tr>
            <tr>
                <td colspan="2"><b>Person to contact in case of emergency:</b> <input type="text" name="emergency"></td>
            </tr>
        </table>

       
        <div class="section">EDUCATIONAL BACKGROUND</div><br>
        <table border="0">
            <tr>
                <td><b>Elementary:</b> <input type="text" name="elem"></td>
                <td><b>Year Graduated:</b> <input type="text" name="elem_year"></td>
            </tr>
            <tr>
                <td><b>High School:</b> <input type="text" name="hs"></td>
                <td><b>Year Graduated:</b> <input type="text" name="hs_year"></td>
            </tr>
            <tr>
                <td><b>College:</b> <input type="text" name="college"></td>
                <td><b>Year Graduated:</b> <input type="text" name="college_year"></td>
            </tr>
            <tr>
                <td><b>Degree Received:</b> <input type="text" name="degree"></td>
                <td><b>Special Skills:</b> <input type="text" name="skills"></td>
            </tr>
        </table>

       
        <div class="section">EMPLOYMENT RECORD</div><br>
        <table border="0">
            <tr>
                <td><b>Company Name:</b> <input type="text" name="company1"></td>
                <td><b>Position:</b> <input type="text" name="position1"></td>
                <td><b>From:</b> <input type="text" name="from1"></td>
                <td><b>To:</b> <input type="text" name="to1"></td>
            </tr>
            <tr>
                <td><b>Company Name:</b> <input type="text" name="company2"></td>
                <td><b>Position:</b> <input type="text" name="position2"></td>
                <td><b>From:</b> <input type="text" name="from2"></td>
                <td><b>To:</b> <input type="text" name="to2"></td>
            </tr>
        </table>

      
        <div class="section">CHARACTER REFERENCE</div><br>
        <table border="0">
            <tr>
                <td><b>Name:</b> <input type="text" name="ref1_name"></td>
                <td><b>Position:</b> <input type="text" name="ref1_pos"></td>
                <td><b>Company:</b> <input type="text" name="ref1_comp"></td>
                <td><b>Contact No:</b> <input type="text" name="ref1_contact"></td>
            </tr>
            <tr>
                <td><b>Name:</b> <input type="text" name="ref2_name"></td>
                <td><b>Position: </b><input type="text" name="ref2_pos"></td>
                <td><b>Company:</b> <input type="text" name="ref2_comp"></td>
                <td><b>Contact No:</b> <input type="text" name="ref2_contact"></td>
            </tr>
        </table>

        <button type="submit" class="btn">Submit</button>
    </form>
</div>

<script>
    function validateForm() {
        let name = document.forms[0]["name"].value;
        if (name === "") {
            alert("Name is required!");
            return false;
        }
        return true;
    }
</script>
</body>
</html>

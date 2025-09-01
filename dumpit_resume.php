<?php
// -------------------------
// PERSONAL INFORMATION
// -------------------------
$name        = "Floritz Jasmine Dumpit";
$title       = "Web and Mobile Technologies Student";
$phone       = "09566****42";
$email       = "22ur0481@psu.edu.ph";
$linkedin    = "linkedin.floritzjasmine";
$gitlab      = "gitlab.com/jasminefloritz.com";
$address     = "Babasit, Manaoag, Pangasinan";
$dob         = "April 23, 2003";
$gender      = "Female";
$nationality = " Filipino";
$photo       = "profile_picture.jpg"; // place your profile photo here (or set "NA")

// -------------------------
// EDUCATION
// -------------------------
$jhighschool   = "Manaoag National High School Junior High School";
$jhs_years     = "2016 to 2020";
$shighschool   = "Manaoag National High School Senior High School";
$shs_years     = "2020 to 2022";
$hs_activities = [
    "Special Program in Arts Student",
    "Overall SPA Visual Arts Vice President"
];

$college        = "Pangasinan State University Urdaneta Campus";
$college_years  = "2022 to Present";
$degree         = "BS Information Technology";
$specialization = "Web and Mobile Technologies";

// -------------------------
// EXPERIENCE
// -------------------------
$experience_title = "Bachelor of Science in Information Technology major in Web and Mobile Technologies";
$experience_years = "2022 – Present";
$experience_tasks = [
    "Developed responsive websites for school purposes.",
    "Created a plant inventory system website using HTML, CSS, JavaScript, and PHP as a Final Requirement in System Analysis and Design.",
    "Explored creative workflows combining design tools and digital artistry."
];

// -------------------------
// SKILLS
// -------------------------
$skills = ["HTML", "CSS", "Java", "Figma", "Canva"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $name; ?> - Resume</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            margin: 0;
            background: #f5f7fa;
            color: #333;
        }
        .container {
            max-width: 1100px;
            margin: 30px auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        /* Header */
        .header {
            background: linear-gradient(50deg, #003DA5 30%, #FFB81C );
            color: #fff;
            padding: 30px 40px;
            display: flex;
            align-items: center;
            gap: 25px;
        }
        .header img {
            border-radius: 10%;
            width: 130px;
            height: 130px;
            object-fit: cover;
            border: 2px solid #fff;
        }
        .header-info {
            flex: 1;
        }
        .header-info h1 {
            margin: 0;
            font-size: 32px;
        }
        .header-info p {
            margin:px 0;
            font-size: 18px;
            opacity: 0.9;
        }
        .contact {
            margin-top: 20px;
            font-size: 12px;
        }
        .contact span {
            margin-right: 20px;
        }

        /* Main Layout */
        .main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            padding: 40px;
        }

        /* Section */
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            margin-bottom: 15px;
            font-size: 22px;
            color: #003DA5;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }
        .section p {
            margin: 6px 0;
        }
        ul {
            margin: 8px 0 0 20px;
        }

        /* Skills */
        .skills {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    padding: 15px;
}

.skill-item {
    background: linear-gradient(135deg, #003DA5, #3c07a7);
    color: #fff;
    padding: 12px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    box-shadow: 0 3px 6px rgba(0,0,0,0.12);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.skill-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
}



        /* Experience List */
        .experience ul {
            list-style: none;
            padding: 0;
        }
        .experience ul li {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }
        .experience ul li::before {
            content: "▹";
            position: absolute;
            left: 0;
            color: #0074d9;
            font-weight: bold;
        }
        .about-text {
    text-align: justify;
}
    </style>
</head>
<body>

<div class="container">

    <!-- Header -->
    <div class="header">
        <?php if($photo != "NA") { ?>
            <img src="<?php echo $photo; ?>" alt="Profile Photo">
        <?php } ?>
        <div class="header-info">
            <h1><?php echo $name; ?></h1>
            <p><?php echo $title; ?></p>
            <div class="contact">
                <span>📞 <?php echo $phone; ?></span>
                <span>✉️ <?php echo $email; ?></span>
                <span>🔗 <?php echo $linkedin; ?></span>
                <span>💻 <?php echo $gitlab; ?></span>
                <span>📍 <?php echo $address; ?></span>
                <span>📅 <?php echo $dob; ?></span>
                <span>⚥ <?php echo $gender; ?></span>
                <span>🚩 <?php echo $nationality; ?></span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
<div class="main">

    <!-- Left Column -->
    <div>
        <div class="section education">
            <h2>Education</h2>
            <p><b><?php echo $jhs_years; ?></b> - <?php echo $jhighschool; ?></p>
            <p><b><?php echo $shs_years; ?></b> - <?php echo $shighschool; ?></p>

            <ul>
                <?php foreach ($hs_activities as $activity) {
                    echo "<li>$activity</li>";
                } ?>
            </ul>
            <p><b><?php echo $college_years; ?></b> - <?php echo $degree; ?>, <?php echo $college; ?></p>
            <p>Specialization: <?php echo $specialization; ?></p>
        </div>

        <!-- Skills moved here -->
        <div class="section">
            <h2>Skills</h2>
            <div class="skills">
                <?php foreach ($skills as $skill) { ?>
                    <div class="skill-item"><?php echo $skill; ?></div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div>
        <div class="section about">
            <h2>About Me</h2>
            <p class="about-text">
                Currently a 3rd year BSIT student passionate about front-end development and design.
                Strong in HTML/CSS, with foundational knowledge of JavaScript, PHP, and MySQL. 
                Proficient in using Figma and Canva to craft user-friendly designs.
            </p>
        </div>

        <!-- Experience moved here -->
        <div class="section experience">
            <h2>Experience</h2>
            <p><b><?php echo $experience_years; ?></b> - <?php echo $experience_title; ?></p>
            <ul>
                <?php foreach ($experience_tasks as $task) {
                    echo "<li>$task</li>";
                } ?>
            </ul>
        </div>
    </div>

</div>


</body>
</html>

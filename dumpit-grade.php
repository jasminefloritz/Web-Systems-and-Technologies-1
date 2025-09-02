<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Grade Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f4f4f9;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .result {
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #4CAF50;
            background: #f9fff9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Grade Evaluation</h1>

    <?php
    if (isset($_GET['name']) && isset($_GET['score'])) {
        $name = htmlspecialchars($_GET['name']);
        $score = (int) $_GET['score'];

        if ($score >= 95 && $score <= 100) {
            $grade = "A (Excellent)";
            $remark = "Outstanding Performance!";
        } elseif ($score >= 90 && $score<=94) {
            $grade = "B (Very Good)";
            $remark = "Great Job!";
        } elseif ($score >= 85 && $score<=89) {
            $grade = "C (Good)";
            $remark = "Good effort, keep it up!";
        } elseif ($score >= 75 && $score<=84) {
            $grade = "D (Needs Improvement)";
            $remark = "Work harder next time.";
            } elseif ($score <=74 && $score>=0) {
            $grade = "F (Failed)";
            $remark = "You need improvement";
        } else {
            echo "Something is wrong with your input!";
        }

        echo "<div class='result'>
                <h3>Student Result</h3>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Score:</strong> $score</p>
                <p><strong>Grade:</strong> $grade</p>
                <p><strong>Remarks:</strong> $remark</p>
              </div>";
    } else {
        echo "<p><h2>Welcome!</h2></p>";
    }
    ?>

    <h3>📊 Grading System Reference</h3>
    <table>
        <tr>
            <th>Score Range</th>
            <th>Grade</th>
            <th>Description</th>
            <th>Remarks</th>
        </tr>
        <tr>
            <td>95–100</td>
            <td>A</td>
            <td>Excellent</td>
            <td>Outstanding Performance!</td>
        </tr>
        <tr>
            <td>90–94</td>
            <td>B</td>
            <td>Very Good</td>
            <td>Great Job!</td>
        </tr>
        <tr>
            <td>85–89</td>
            <td>C</td>
            <td>Good</td>
            <td>Good effort, keep it up!</td>
        </tr>
        <tr>
            <td>75–84</td>
            <td>D</td>
            <td>Needs Improvement</td>
            <td>Work harder next time.</td>
        </tr>
        <tr>
            <td>74 Below</td>
            <td>F</td>
            <td>Failed</td>
            <td>You need to improve.</td>
        </tr>
    </table>
</div>
</body>
</html>

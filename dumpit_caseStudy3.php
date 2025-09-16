<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            text-align : center;
            margin-top: 20px
        }

        table{
            margin: auto;
            border-collapse: collapse;
            width: 80%;
            max-width: 600px;
        }

        th, td{
            border: 1px solid black;
            padding: 10px;
            text-align: center;
            width: 50px;
            height: 40px;
        }

        th{
            background-color: #f2f2f2;
        }

        .odd {
            background-color: yellow;
            font-weight: bold;
        }
        input[type="number"] {
    width: 200px;     
    padding: 8px;     
    font-size: 16px;  
    margin: 5px 0;   
}

input[type="submit"] {
    padding: 10px 100px;
    font-size: 16px;
    margin-top: 10px;
    cursor: pointer;
}
        </style>
</head>
<body>
    <form action="" method="post">
    
        Enter row size:    <input type="number" name="rows" required placeholder="Enter rows">

        Enter column size: <input type="number" name="cols" required placeholder="Enter columns">
    
    <div>
        <input type="submit" value="Submit">
    </div>
</form>
    <?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $rows = $_POST['rows'];
        $cols = $_POST['cols'];

        echo "<h1>Multiplication Table ($rows x $cols)</h1>";

        echo "<table>";
        echo "<tr><th>X</th>";
        for($i = 1; $i<=$cols;$i++){
            echo "<th>$i</th>";
        }
        echo "</tr>";

        for($i = 1; $i<=$rows;$i++){
            echo "<tr>";
            echo "<th>$i</th>";
        

        for($j = 1;$j<=$cols;$j++){
            $value = $i * $j;

            if($value % 2 !=0){
                echo "<td class = 'odd'>$value</td>";
            }else{
                echo "<td>$value</td>";
            }
        }
        echo "</tr>";
}
        echo "</table>";
    }?>
</body>
</html>
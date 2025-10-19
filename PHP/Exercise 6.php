<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Calculator</title>
</head>
<body>
    <h2><b>Salary Calculator</b></h2>
    <form method="post">
        Enter basic salary: <input type="number" name="basic_salary"><br><br>
        Enter allowance: <input type="number" name="allowance"><br><br>
        Enter deduction <input type="number" name="deduction"><br><br>
        <input type="submit" value="Calculate net salary">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $basic_salary = $_POST['basic_salary'];
        $allowance = $_POST['allowance'];
        $deduction = $_POST['deduction'];


        $net_salary = ($basic_salary + $allowance) - $deduction; 

        echo "Basic Salary = P 20,000<br>";
        echo "Allowance = P 5000 <br>";
        echo "Deduction = P 8,000<br>";

        echo "Net Salary = $net_salary";
    }
    ?>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        echo "<b>SALARY CALCULATOR</b>";
        echo "<ul>";

        $basic_salary = 20000;
        $allowance = 5000;
        $deduction = 8000;

        $net_salary = ($basic_salary + $allowance) - $deduction; 

        echo "Basic Salary = P 20,000<br>";
        echo "Allowance = P 5000 <br>";
        echo "Deduction = P 8,000<br>";

        echo "Net Salary = $net_salary";
    ?>
</body>
</html>
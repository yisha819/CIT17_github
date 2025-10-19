<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Math</title>
</head>
<body>
    <h2><b>Simple Math</b></h2>

    <form method="post">
        Enter first number: <input type="value" name="a" required><br><br>
        Enter second number: <input type="value" name="b" required><br><br>
        <input type="submit" value="Calculate">
    </form>
   <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $a = $_POST['a'];
        $b = $_POST['b'];

        
        echo "<br>";
        echo "Sum: $a + $b  = " . ($a + $b) . "<br>";
        echo "Difference: $a - $b  = " . ($a - $b) . "<br>";
        echo "Product: $a * $b  = " . ($a * $b) . "<br>";
        echo "Quotient: $a / $b  = " .  ($b != 0 ? ($a / $b) : "undefined (division by zero)") . "<br>";
    }
   ?>
</body>
</html>
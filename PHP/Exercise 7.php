<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator</title>
</head>
<body>
    <h2><b>BMI Calculator</b></h2>
    <form method="post">
        Enter weight (kgs): <input type="value" name="weight"><br><br>
        Enter height (m): <input type="value" name="height"><br><br>
        <input type="submit" value="Calculate BMI">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $weight = $_POST['weight'];
        $height = $_POST['height'];

        $bmi = $weight / ($height * $height);

        echo "Weight (kg): $weight <br>";
        echo "Height (m): $height <br>";
        echo "Your BMI is: " . round($bmi, 2);
    }
    ?>
    
</body>
</html>
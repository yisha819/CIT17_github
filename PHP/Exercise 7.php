<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator</title>
</head>
<body>
    <?php
    echo "<b>BMI Calculator</b>";
    echo "<ul>";
    $weight = 70; 
    $height = 1.75; 

    $bmi = $weight / ($height * $height);

    echo "Weight 
    (kg): 70 <br>";
    echo "Height (m): 1.75 <br>";
    echo "Your BMI is: " . round($bmi, 2);
    ?>
    
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Grading System</title>
</head>
<body>
    <?php
    echo "<b>SIMPLE GRADING SYSTEM</b>";
    echo "<ul>";
    $math = 85;
    $english = 92;
    $science = 78;


    $average = ($math + $english + $science) / 3;


    if ($average >= 90) {
        $grade = "A";
    } elseif ($average >= 80) {
        $grade = "B";
    } elseif ($average >= 70) {
        $grade = "C";
    } elseif ($average >= 60) {
        $grade = "D";
    } else {
        $grade = "F";
    }

    echo "Math: 85 <br>";
    echo "English: 92 <br>";
    echo "Science: 78 <br>";
    echo "<br>";
    echo "Average Score: " . round($average, 2) . "<br>";
    echo "Grade: " . $grade;
    ?>

    
</body>
</html>
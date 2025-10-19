<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Grading System</title>
</head>
<body>
    <h2><b>Simple Grading System</b></h2>
    <form method="post">
        Grade in Math: <input type="value" name="math"><br><br>
        Grade in English: <input type="value" name="english"><br><br>
        Grade in Science: <input type="value" name="science"><br><br>
        <input type="submit" value="Calculate Average"><br><br>
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    
    {
        $math = $_POST['math'];
        $english = $_POST['english'];
        $science = $_POST['science'];
        
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

        echo "Average Score: " . round($average, 2) . "<br>";
        echo "Grade: " . $grade;

    }
    ?>

    
</body>
</html>
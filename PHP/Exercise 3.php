<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area and Perimeter of a Rectangle</title>
</head>
<body>
    <h2><b>Area and Perimeter of a Rectangle</b></h2>
    <form method="post">
        Enter length: <input type ="value" name="length"><br><br>
        Enter width: <input type ="value" name="width"><br><br>
        <input type="submit" value="Calculate">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    { 
        $length = $_POST['length'];
        $width = $_POST['width'];

        $area = $length * $width;
        $perimeter = 2 * ($length + $width);
        
        echo "Length = $length<br>";
        echo "Width = $width<br><br>";
        echo "Area: $length * $width = $area<br>";
        echo "Perimeter: 2 * ($length + $width) = $perimeter<br>";
    }
        

    ?>
</body>
</html>
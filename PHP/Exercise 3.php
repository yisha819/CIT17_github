<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area and Perimeter of a Rectangle</title>
</head>
<body>
    <?php
        echo "<b>AREA AND PERIMETER OF A RECTANGLE </b>";
        echo "<ul>";

        $length = 8;
        $width = 5;

        $area = $length * $width;
        $perimeter = 2 * ($area);

        echo"Length = $length";
        echo "<br>";
        echo"Width = $width";
        echo "<br>";
        echo "<br>";
        echo "Area:  $length * $width = $area <br>";
        echo "Perimeter:  2 ($length * $width) = $perimeter";

    ?>
</body>
</html>
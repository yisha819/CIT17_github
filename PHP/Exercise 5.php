<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapping Variables</title>
</head>
<body>
    <?php
        echo "<b>SWAPPING VARIABLES </b>";
        echo "<ul>";
        $a = 10;
        $b = 6;

        echo "Before swapping:<br>";
        echo "<li>a = $a</li>";
        echo "<li>b = $b </li>";
        $temp = $a;
        $a = $b;
        $b = $temp;

        echo "After swapping:<br>";
        echo "<li>a = $a </li>";echo "<li>b = $b </li>";

    ?>
    
</body>
</html>
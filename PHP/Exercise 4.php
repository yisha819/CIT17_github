<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temperature Converter</title>
</head>
<body>
    <?php
        echo"<b>TEMPERATURE CONVERTER</b>";
        echo "<ul>";
        $Celsius = 32;
        $Convert_to_Farenheit = ($Celsius *1.8) + 32;
        
        echo "Celsius = $Celsius";
        echo "<br>";
        echo "<br>";

        echo "Convert to Farenheit: <br>";
        echo "Farenheit = $Convert_to_Farenheit";

    ?>
    
</body>
</html>
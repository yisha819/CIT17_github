<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temperature Converter</title>
</head>
<body>
    <h2><b>Temperature Converter</b></h2>
    <form method="post">
        Enter temperature in Celsius °C: <input type="value" name="Celsius"><br><br>
        <input type="submit" value="Calculate">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    { 
        $Celsius = $_POST['Celsius'];
        
        $Celsius = 32;
        $Convert_to_Farenheit = ($Celsius *1.8) + 32;
        
        echo "Celsius = $Celsius °C <br>";
        echo "<br>";

        echo "Convert to Farenheit: <br>";
        echo "Farenheit = $Convert_to_Farenheit °F";
    }

    ?>
    
</body>
</html>
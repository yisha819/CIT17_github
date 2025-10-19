<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>String Manipulation</title>
</head>
<body>
    <h2><b>String Manipulation</b></h2>
    <form method="post">
        Enter sentence: <input type="text" name="sentence"><br><br>
        <input type="submit" value="Enter">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $sentence = $_POST['sentence'];

        echo "Number of characters: " . strlen($sentence) . "<br>";

   
        echo "Number of words: " . str_word_count($sentence) . "<br>";

    
        echo "Uppercase: " . strtoupper($sentence) . "<br>";

    
        echo "Lowercase: " . strtolower($sentence) . "<br>";
    }
    ?>
    
    
</body>
</html>
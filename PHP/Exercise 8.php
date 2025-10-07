<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>String Manipulation</title>
</head>
<body>
    <?php
    echo "<b>STRING MANIPULATION</b>";
    echo "<ul>";

    $sentence = "Lightning danced across the sky as the old lighthouse blinked through the storm.";

    
    echo "Number of characters: " . strlen($sentence) . "<br>";

   
    echo "Number of words: " . str_word_count($sentence) . "<br>";

 
    echo "Uppercase: " . strtoupper($sentence) . "<br>";

   
    echo "Lowercase: " . strtolower($sentence) . "<br>";
    ?>

</body>
</html>
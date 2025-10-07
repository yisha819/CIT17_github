<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter</title>
</head>
<body>
    <?php
    echo "<b>CURRENCY CONVERTER</b>";
    echo "<ul>";
    $amountPHP = 1000.00;

    
    $rateUSD = 0.018;  
    $rateEUR = 0.017;  
    $rateJPY = 2.65;   

    
    $amountUSD = $amountPHP * $rateUSD;
    $amountEUR = $amountPHP * $rateEUR;
    $amountJPY = $amountPHP * $rateJPY;

    
    echo "Amount in PHP: ₱" . number_format($amountPHP, 2) . "<br>";
    echo "<br>";
    echo "Converted to USD: $" . number_format($amountUSD, 2) . "<br>";
    echo "Converted to EUR: €" . number_format($amountEUR, 2) . "<br>";
    echo "Converted to JPY: ¥" . number_format($amountJPY, 2);
    ?>

</body>
</html>
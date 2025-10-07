<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Cost Estimator</title>
</head>
<body>
    <?php
    echo "<b>TRAVEL COST ESTIMATOR</b>";
    echo "<ul>";
    
    $distance = 150; 
    $fuel_consumption = 12; 
    $fuel_price = 65.00;

    
    $fuel_needed = $distance / $fuel_consumption;

    
    $travel_cost = $fuel_needed * $fuel_price;

    echo "Distance: 150 <br>";
    echo "Fuel Consumption: 12 <br>";
    echo "Fuel Price: ₱ 65 <br>";
    echo "<br>";
    echo "Estimated Travel Cost: ₱" . number_format($travel_cost, 2);
    ?>

</body>
</html>
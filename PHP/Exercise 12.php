<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Cost Estimator</title>
</head>
<body>
    <h2><b>Travel Cost Estimator</b></h2>
    <form method="post">
        Distance (km): <input type="value" name="distance"><br><br>
        Fuel Consumption (km per liter): <input type="value" name="fuel_consumption"><br><br>
        Fuel Price (₱ per liter): <input type="value" name="fuel_price"><br><br>
        <input type="submit" value="Calculate"><br><br>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $distance = $_POST['distance'];
        $fuel_consumption = $_POST['fuel_consumption'];
        $fuel_price = $_POST['fuel_price'];

        $fuel_needed = $distance / $fuel_consumption;

    
        $travel_cost = $fuel_needed * $fuel_price;

        echo "<br>";
        echo "Estimated Travel Cost: ₱" . number_format($travel_cost, 2);

    }
    ?>

</body>
</html>
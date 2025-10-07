<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Account Simulation</title>
</head>
<body>
    <?php
    echo "<b>BANK ACCOUNT SIMULATION</b>";
    echo "<ul>";
    $balance = 1000.00; 

    $deposit = 250.00;
    $withdraw = 100.00;

    
    $balance += $deposit; 
    $balance -= $withdraw; 

    echo "Balance = $balance <br>";
    echo "Deposit = $deposit <br>";
    echo "Withdraw = $withdraw <br>";

    echo "Final Balance: P" . number_format($balance, 2);
    ?>

</body>
</html>
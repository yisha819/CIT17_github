<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Account Simulation</title>
</head>
<body>
    <h2><b>Bank Account Simulation</b></h2>
    <h3>Choose transaction: </h3>
    <form method="post">
        Enter amount to deposit: <input type="number" name="deposit" step="0.01"><br><br>
        Enter amount to withdraw: <input type="number" name="withdraw" step="0.01"><br><br>
        <input type="submit" value="Enter"><br>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $balance = 1000.00;
        $deposit = floatval($_POST["deposit"]);
        $withdraw = floatval($_POST["withdraw"]);

        echo "Initial Balance = ₱" . number_format($balance, 2) . "<br>";

        if ($deposit > 0) {
            $balance += $deposit;
            echo "Deposit = ₱" . number_format($deposit, 2) . "<br>";
        }

        if ($withdraw > 0) {
            if ($withdraw <= $balance) {
                $balance -= $withdraw;
                echo "Withdraw = ₱" . number_format($withdraw, 2) . "<br>";
            } else {
                echo "Error: Insufficient balance to withdraw ₱" . number_format($withdraw, 2) . "<br>";
            }
        }

        echo "<br>Final Balance: ₱" . number_format($balance, 2);
    }
    ?>
</body>
</html>

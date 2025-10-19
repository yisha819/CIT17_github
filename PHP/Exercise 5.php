<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapping Variables</title>
</head>
<body>
    <h2><b>Swapping Variables</b></h2>
    <form method="post">
        Enter variable a: <input type="value" name="a"><br><br>
        Enter variable b: <input type="value" name="b"><br><br>
        <input type="submit" value="Enter to swap">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $a = $_POST['a'];
        $b = $_POST['b'];

        echo "Before swapping:<br>";
        echo "<li>a = $a</li>";
        echo "<li>b = $b </li>";
        $temp = $a;
        $a = $b;
        $b = $temp;

        echo "After swapping:<br>";
        echo "<li>a = $a </li>";
        echo "<li>b = $b </li>";

    }
    ?>
    
</body>
</html>
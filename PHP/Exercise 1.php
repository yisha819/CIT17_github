<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introduce Yourself</title>
</head>
<body>
    <h2><b>Introduce Yourself</b></h2>
    <form method="post">
        Name: <input type="text" name="name"><br><br>
        Age: <input type="number" name="age"><br><br>
        Favorite Color: <input type="text" name="favColor"><br><br>
        <input type="submit" value="Enter"><br><br>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $age = $_POST['age'];
        $favColor = $_POST['favColor'];

        echo "<h3>Introduction:</h3>";
        echo "I am <b>$name</b>, I am <b>$age</b> years old, and my favorite color is <b>$favColor</b>.";
    }
    ?>
</body>
</html>

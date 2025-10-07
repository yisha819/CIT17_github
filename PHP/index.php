<?php
$exercises = range(1, 12); // Exercise 1 to 12
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Exercises</title>
    <style>
        :root {
            --bg:rgb(208, 229, 248);
            --card-bg: #ffffff;
            --text: #1a1a1a;
            --accent: #0077cc;
            --border:rgb(71, 172, 255);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg);
            font-family: 'Segoe UI', Roboto, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .wrapper {
            max-width: 800px;
            width: 100%;
            padding: 40px 20px;
        }

        h1 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 40px;
            color: var(--text);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-4px);
        }

        .card a {
            text-decoration: none;
            color: var(--accent);
            font-size: 18px;
            font-weight: 500;
        }

        .card a:hover {
            color: #005099;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <h1>PHP Exercise List</h1>
    <div class="grid">
        <?php foreach ($exercises as $num): ?>
            <div class="card">
                <a href="Exercise <?= $num ?>.php">Exercise <?= $num ?></a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>

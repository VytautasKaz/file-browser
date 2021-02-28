<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>

    <h1>File Browser</h1>

    <div class="container">
        <table>
            <tr>
                <th>Type</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <?php
            $array = scandir('./');
            for ($i = 0; $i < count($array); $i++) {
                if ($array[$i] === '.' || $array[$i] === '..') continue;
                if (is_file($array[$i])) print('<tr><td>File</td><td>' . $array[$i] . '</td><td></td><tr>');
                if (is_dir($array[$i])) print('<tr><td>Directory</td><td><a href="./' . $array[$i] . '">' . $array[$i] . '</td><td></td><tr>');
            }
            ?>
        </table>
    </div>
</body>

</html>
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
            $path = './' . $_GET['path'];
            $content = scandir($path);
            for ($i = 0; $i < count($content); $i++) {
                if ($content[$i] === '.' || $content[$i] === '..') continue;
                if (is_file($content[$i])) print('<tr>
                                                    <td>File</td>
                                                    <td>' . $content[$i] . '</td>
                                                    <td><button type="submit">Delete</button></td>
                                                <tr>');
                if (is_dir($content[$i])) print('<tr>
                                                    <td>Directory</td>
                                                    <td><a href="./' . $content[$i] . '">' . $content[$i] . '</td>
                                                    <td></td>
                                               <tr>');
            }
            ?>
        </table>
    </div>
</body>

</html>
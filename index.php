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
        <?php
        $path = './' . $_GET['path'];
        $content = scandir($path);

        print('<table>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>');

        for ($i = 0; $i < count($content); $i++) {
            if ($content[$i] === '.' || $content[$i] === '..') continue;
            if (is_file($path . $content[$i])) print('<tr>
                                                        <td>File</td>
                                                        <td>' . $content[$i] . '</td>
                                                        <td>
                                                            <button type="submit">Delete</button>
                                                        </td>
                                                    </tr>');
            if (is_dir($path . $content[$i])) {
                if (!isset($_GET['path'])) {
                    print('<tr>
                <td>Directory</td>
                    <td>
                        <a href="' . $_SERVER['REQUEST_URI'] . '?path=' . $content[$i] . '/">' . $content[$i] . '</a>
                    </td>
                    <td></td>
                </tr>');
                } else {
                    print('<tr>
                <td>Directory</td>
                    <td>
                        <a href="' . $_SERVER['REQUEST_URI'] . $content[$i] . '/">' . $content[$i] . '</a>
                    </td>
                    <td></td>
                </tr>');
                }
            }
        }
        print('</table>');
        print('<button class="back" onclick="history.go(-1);">Back</button>');
        ?>
    </div>
</body>

</html>
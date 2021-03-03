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

        // print('<button class="back" onclick="history.go(-1);">Back</button>');

        print('<button class="back"><a href="?path=' . $_GET['path'] . '../">Back</a></button>');

        print('<table>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>');

        for ($i = 0; $i < count($content); $i++) {
            if ($content[$i] === '.' || $content[$i] === '..') continue;
            if (is_file($path . $content[$i])) print('<tr name="' . $content[$i] . '">
                                                        <td>File</td>
                                                        <td>' . $content[$i] . '</td>
                                                        <td>
                                                            <form action="" method="POST">
                                                                <input type="submit" name="delete" value="Delete">
                                                            </form>
                                                            <form action="?path="' . $content[$i] . 'method="POST">
                                                                <input type="submit" name"download" value="Download">
                                                            </form>
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
        ?>
    </div>
</body>

</html>
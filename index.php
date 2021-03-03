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
    <?php
    if (isset($_POST['download'])) {
        $file = './' . $_POST['download'];
        $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, null, 'utf-8'));

        ob_clean();
        ob_start();
        header('Content-Description: File Transfer');
        header('Content-Type:' . mime_content_type($fileToDownloadEscaped));
        header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileToDownloadEscaped));
        ob_end_flush();

        readfile($fileToDownloadEscaped);
        exit;
    }
    ?>
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
            if (is_file($path . $content[$i])) print('<tr>
                                                        <td>File</td>
                                                        <td>' . $content[$i] . '</td>
                                                        <td>
                                                            <form action="" method="POST">
                                                                <button type="submit" name="delete" value="' . $content[$i] . '">Delete</button>
                                                            </form>
                                                            <form action="?path=' . $content[$i] . '" method="POST">
                                                                <button type="submit" name="download" value="' . $content[$i] . '">Download</button>
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
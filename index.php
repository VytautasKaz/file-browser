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

    // "Download" button logic

    if (isset($_POST['download'])) {
        $file = $_GET['path'] . $_POST['download'];

        print($_GET['path']);

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

    // "Delete" button logic

    if (isset($_POST['delete'])) {
        $file = $_GET['path'] . $_POST['delete'];
        unlink($file);
    }

    // Upload logic

    if (isset($_FILES['upload'])) {
        $file_name = $_FILES['upload']['name'];
        $file_size = $_FILES['upload']['size'];
        $file_tmp = $_FILES['upload']['tmp_name'];
        $file_type = $_FILES['upload']['type'];
        if (!empty($file_name)) {
            move_uploaded_file($file_tmp, $_GET['path'] . $file_name);
            print("<script type=\"text/javascript\">alert('File uploaded successfully!')</script>");
        }
    }

    // Create new dir logic

    if (isset($_POST['directory'])) {
        if (is_dir($_POST['directory'])) {
            error_reporting(E_ERROR | E_PARSE);
            print('<p style="color: red; font-size: 20px; text-align: center; margin-top: 10px;">Error: Directory name already exists.</p>');
        }
        if (!empty($_POST['directory'])) {
            $newDir = $_GET['path'] . $_POST['directory'];
            mkdir($newDir);
        } else {
            print('<p style="color: red; font-size: 20px; text-align: center; margin-top: 10px">Error: Directory name missing.</p>');
        }
    }

    ?>
    <h1>File Browser</h1>

    <div class="container">
        <?php
        $path = './' . $_GET['path'];
        $content = scandir($path);

        print('<button class="back" onclick="goBack()">Back</button>');

        // print('<button class="back"><a href="?path=' . $_GET['path'] . '../">Back</a></button>');

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
                                                                <button type="submit" name="delete" value="' . $content[$i] . '" onclick="return confirm(\'Are you sure?\')">Delete</button>
                                                            </form>
                                                            <form action="" method="POST">
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

        print('<h5>Upload a file</h5>
               <form class="upload-form" action="" method="POST" enctype="multipart/form-data">
                    <input type="file" name="upload" />
                    <button type="submit">Upload</button>
               </form>');

        print('<h5>Create a new directory</h5>
               <form class="new-dir" action="" method="POST">
                    <input type="text" name="directory" placeholder="Enter directory name"/>
                    <button type="submit">Create</button>
               </form>');

        // "Back" button logic

        print('<script type="text/javascript">
        function goBack() {
            let url = window.location.href.split("/");
            if (url[url.length-1] == "") {
                url.splice(url.length-2, 1);
            } else {
                url.splice(url.length-1);
            }
            window.location.href = url.join("/");
            return window.location.href;
        }
        </script>');
        ?>
    </div>
</body>

</html>
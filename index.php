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

    // Login logic

    session_start();
    if (
        isset($_POST['login'])
        && !empty($_POST['username'])
        && !empty($_POST['password'])
    ) {
        if (
            $_POST['username'] === 'test-login' &&
            $_POST['password'] === 'test-pw'
        ) {
            $_SESSION['logged_in'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = $_POST['username'];
        } else {
            print('<script type="text/javascript">alert("Wrong username or password.");</script>');
        }
    }

    // Logout logic

    if (isset($_GET['action']) == 'logout') {
        session_start();
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        unset($_SESSION['logged_in']);
        session_destroy();
        print('<script type="text/javascript">alert("You have been logged out successfully.");</script>');
    }

    // "Download" button logic

    if (isset($_POST['download'])) {
        $file = $_GET['path'] . $_POST['download'];

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
            if (is_file($file_name)) {
                print("<script type=\"text/javascript\">alert('Unable to upload a file with a name that already exists.')</script>");
            } else {
                move_uploaded_file($file_tmp, $_GET['path'] . $file_name);
                print("<script type=\"text/javascript\">alert('File uploaded successfully!')</script>");
            }
        }
    }

    // Create new dir logic

    if (isset($_POST['directory'])) {
        $newDir = $_GET['path'] . $_POST['directory'];

        if (strpbrk($_POST['directory'], "\\/?%*:|\"<>") === FALSE) {
            if (empty($_POST['directory'])) {
                print('<p style="color: red; font-size: 20px; text-align: center; margin-top: 10px">Error: Directory name missing.</p>');
            } else {
                if (is_dir($newDir)) {
                    $folderName = '';
                    $counter = 2;
                    while (!$folderName) {
                        if (!is_dir($newDir . "($counter)")) {
                            $folderName = $newDir . "($counter)";
                        }
                        $counter++;
                    }
                    mkdir($folderName);
                } else {
                    mkdir($newDir);
                }
            }
        }
    }

    ?>

    <h1>File Browser</h1>

    <div class="container">
        <?php
        $path = './' . $_GET['path'];
        $content = scandir($path);

        if ($_SESSION['logged_in'] == true) {

            print('<button class="back" onclick="goBack()">Back</button>');

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
                    <input type="text" name="directory" placeholder="Enter your directory name"/>
                    <button type="submit">Create</button>
               </form>');

            print('<p class="logout-line">Click <a href = "?action=logout">here</a> to logout.</p>');
        } else {
            print('<h4>Enter your login information</h4>
                   <form class="login-form" action="./index.php" method="post">
                        <input type="text" name="username" placeholder="username = test-login" required><br>
                        <input type="password" name="password" placeholder="password = test-pw" required><br>
                        <button class="login-btn" type="submit" name="login">Login</button>
                   </form>');
        }
        ?>

        <!-- "Back" button logic -->

        <script type="text/javascript">
            function goBack() {
                let url = window.location.href.split("/");
                if (url[url.length - 1] === "") {
                    url.splice(url.length - 2, 1);
                } else {
                    url.splice(url.length - 1);
                }
                window.location.href = url.join("/");
                return window.location.href;
            }
        </script>
    </div>
</body>

</html>
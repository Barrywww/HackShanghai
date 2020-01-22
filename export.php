<?php
session_start();
$config = require '.htconfig.php';
$auth = isset($_SESSION['auth']) && $_SESSION['auth'];
if (!$auth) {
    if (isset($_POST['auth'])) {
        if ($_POST['auth'] == $config['OP_PWD']) {
            $_SESSION['auth'] = true;
            header("Location: ?");
            die();
        } else if (isset($_POST['auth'])) {
            header("Location: ?error=0");
            die();
        }
    }
} else {
    if (isset($_GET['logout'])) {
        unset($_SESSION['auth']);
        header("Location: ?");
        die();
    }
    if (isset($_GET['export'])) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="RegForm_' . date("Y-m-d-h-i-s") . '.csv"');
        header('Cache-Control: max-age=0');
        $conn = new mysqli($config["DB_HOST"], $config["DB_USER"], $config["DB_PWD"], $config["DB_NAME"]);
        if ($conn->connect_error) {
            header("Location: ?error=1");
            die();
        }
        $conn->query("set names 'utf8'");
        $sql = "SELECT `form_id`,
       `update_time`,
       `name`,
       `birthday`,
       `country`,
       `province`,
       `city`,
       `district`,
       `school`,
       `major`,
       `grade`,
       `id`,
       `phone`,
       `im`,
       `github`,
       `talent`,
       `experience`,
       `has_team`,
       `leader_name`,
       `cv_original_filename`,
       `cv_stored_filename`
FROM `form`";
        if ($result = mysqli_query($conn, $sql)) {
            $fp = fopen('php://output', 'a');
            fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $head = array('form_id', 'update_time', 'name', 'birthday', 'country', 'province', 'city', 'district', 'school', 'major', 'grade', 'id', 'phone', 'im', 'github', 'talent', 'experience', 'has_team', 'leader_name', 'cv_original_filename', 'cv_stored_filename');
            fputcsv($fp, $head);
            // 就算是空的，也能导出空文件，暂时不做其他逻辑，遇到没记录的情况直接输出文件即可
            while ($row = $result->fetch_row()) {
                fputcsv($fp, $row);
            }
            $result->free_result();
        }
        fclose($fp);
        $conn->close();
        die();
    }
    if (isset($_POST['down'])) {
        header("Location: ?down=" . $_POST['down']);
        die();
    }
    if (isset($_GET['down'])) {
        $conn = new mysqli($config["DB_HOST"], $config["DB_USER"], $config["DB_PWD"], $config["DB_NAME"]);
        if ($conn->connect_error) {
            header("Location: ?error=2");
            die();
        }
        $conn->query("set names 'utf8'");
        $sql = "SELECT `cv_original_filename`,`cv_stored_filename` FROM `form` WHERE `form_id`=" . $_GET['down'];
        if ($result = mysqli_query($conn, $sql) and $row = $result->fetch_row()) {
            $file = "upload/" . $row[1];
            $filename = $row[0];
            $conn->close();
        } else {
            $conn->close();
            header("Location: ?error=3");
            die();
        }
        header("Content-type: application/octet-stream");
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        header("Content-Length: " . filesize($file));
        readfile($file);
        die();
    }
}
?>
<html>
<head>
    <meta charset="utf-8">
    <link href="image/favicon.png" rel="shortcut icon" type="image/png">
    <script src="lib/jquery/jquery.min.js"></script>
    <?php
    if (!$auth) {
        echo <<<EOF
    <script src="lib/crypto-js/crypto-js.min.js"></script>
    <script>
        $(document).ready(function () {
            $('form').submit(function () {
                $('input')[0].value = CryptoJS.SHA1(CryptoJS.SHA1($('input')[0].value).toString()).toString();
            });
        });
    </script>
EOF;
    } else {
        echo <<<EOF
    <script>
        $(document).ready(function () {
            $('#logout').click(function () {
                window.location.href = '?logout';
            });
            $('#export').click(function () {
                window.location.href = '?export';
            });
        });
    </script>
EOF;
    }
    ?>
    <style>
        body {
            background-color: #e0f9d4;
        }
        div {
            padding-top: 1em;
        }
        span {
            font-family: 'Microsoft YaHei', 'Helvetica Neue', Arial, Helvetica, sans-serif;
        }
        button {
            padding: 0;
            width: 180px;
            height: 36px;
            border-width: 0px;
            border-radius: 3px;
            background: #1E90FF;
            cursor: pointer;
            outline: none;
            font-family: 'Microsoft YaHei', 'Helvetica Neue', Arial, Helvetica, sans-serif;
            color: white;
            font-size: 17px;
        }
        button:hover {
            background: #5599FF;
        }
        input {
            outline-style: none;
            border: 1px solid #ccc;
            border-radius: 3px;
            padding: 13px 13px;
            height: 35px;
            width: 200px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Microsoft YaHei', 'Helvetica Neue', Arial, Helvetica, sans-serif;
        }
        input:focus {
            border-color: #66afe9;
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
        }
    </style>
    <title>Export Data</title>
</head>
<body>
<span>Auth Status:</span>
<?php
if (!$auth) {
    echo <<<EOF
<span style="font-weight: bold;color: red;">No</span>
<div>
    <form method="post" autocomplete="off">
        <label><span>Password</span></label>
        <input name="auth" type="password">
        <button type="submit">Continue</button>
    </form>
</div>
EOF;
} else {
    echo <<<EOF
<span style="font-weight: bold;color: green;">Yes</span>
<div>
    <button id="logout">Logout</button>
</div>
<div>
    <button id="export">Export As CSV</button>
</div>
<div>
    <form method="post" autocomplete="off">
        <label><span>form_id</span></label>
        <input name="down" type="text" style="width: 60px;">
        <button type="submit" style="width: 250px;">Download Attachment</button>
    </form>
</div>
EOF;
}
if (isset($_GET['error'])) {
    $errorCode = $auth ? $_GET['error'] : 0;
    echo '<span style="color: red">';
    switch ($errorCode) {
        case 0:
            echo 'Invalid password.';
            break;
        case 1:
            echo 'Export failed: Database connect error.';
            break;
        case 2:
            echo 'Download failed: Database connect error.';
            break;
        case 3:
            echo 'Download failed: Record not found. Try search with another form_id.';
            break;
        default:
            echo 'Unknown error.';
            break;
    }
    echo '<br></span>';
}
?>
<div>
    <span style="font-weight: lighter;font-size: x-small;">Copyright (C) 2020 Liyang Zhu. All rights reserved.</span>
</div>
</body>
</html>

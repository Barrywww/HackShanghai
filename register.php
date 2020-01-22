<?php
error_reporting(0);
header('Content-Type: text/json;charset=utf-8');
$config = require '.htconfig.php';
$uploadDir = 'upload';
if (!isset($_POST["name"]) || !preg_match('/^.{1,40}$/', $_POST["name"]) ||
    !isset($_POST["birthday"]) || !preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $_POST["birthday"]) ||
    !isset($_POST["country"]) || !preg_match('/^.{1,40}$/', $_POST["country"]) ||
    !isset($_POST["province"]) || !preg_match('/^.{1,40}$/', $_POST["province"]) ||
    !isset($_POST["city"]) || !preg_match('/^.{1,40}$/', $_POST["city"]) ||
    !isset($_POST["district"]) || !preg_match('/^.{1,40}$/', $_POST["district"]) ||
    !isset($_POST["school"]) || !preg_match('/^.{1,40}$/', $_POST["school"]) ||
    !isset($_POST["major"]) || !preg_match('/^.{1,40}$/', $_POST["major"]) ||
    !isset($_POST["grade"]) || !preg_match('/^.{1,40}$/', $_POST["grade"]) ||
    !isset($_POST["id"]) || !preg_match('/^.{8,40}$/', $_POST["id"]) ||
    !isset($_POST["phone"]) || !preg_match('/^\d{8,40}$/', $_POST["phone"]) ||
    !isset($_POST["im"]) || !preg_match('/^.{1,40}$/', $_POST["im"]) ||
    !isset($_POST["github"]) || !preg_match('/^.{0,40}$/', $_POST["github"]) ||
    !isset($_POST["talent"]) || !preg_match('/^.{1,40}$/', $_POST["talent"]) ||
    !isset($_POST["experience"]) || !preg_match('/^.{0,200}$/', $_POST["experience"]) ||
    !isset($_POST["hasteam"]) || !preg_match('/^(0|1)$/', $_POST["hasteam"]) ||
    !isset($_POST["leadername"]) || $_POST["hasteam"] == "1" && !preg_match('/^.{1,40}$/', $_POST["leadername"])
) {
    die(json_encode(array(
        'succ' => false,
        'msg' => 'Invalid Fields'
    )));
}
if (!isset($_FILES["cv"])) {
    die(json_encode(array(
        'succ' => false,
        'msg' => 'No File'
    )));
}
if (!is_dir($uploadDir) && !mkdir($uploadDir)) {
    die(json_encode(array(
        'succ' => false,
        'msg' => 'Cannot Create Directory'
    )));
}
if ($_FILES["cv"]["error"] > 0) {
    die(json_encode(array(
        'succ' => false,
        'msg' => 'Upload Error: ' . (string)($_FILES["cv"]["error"])
    )));
}
if (!preg_match('/.+\.(pdf|doc|docx)$/', $_FILES["cv"]["name"])) {
    die(json_encode(array(
        'succ' => false,
        'msg' => 'Invalid File Type'
    )));
}
if ($_FILES["cv"]["size"] > 8388608) {
    die(json_encode(array(
        'succ' => false,
        'msg' => 'Invalid File Size'
    )));
}
$oldName = $_FILES["cv"]["name"];
$oldPath = $_FILES["cv"]["tmp_name"];
$newName = uniqid("cv_") . "." . substr(strrchr($oldName, '.'), 1);
$newPath = "./" . $uploadDir . "/" . $newName;
if (!move_uploaded_file($oldPath, $newPath)) {
    die(json_encode(array(
        'succ' => false,
        'msg' => 'Cannot Move File'
    )));
}
$conn = new mysqli($config["DB_HOST"], $config["DB_USER"], $config["DB_PWD"], $config["DB_NAME"]);
if ($conn->connect_error) {
    die(json_encode(array(
        'succ' => false,
        'msg' => 'Connect Error: ' . $conn->connect_error
    )));
}
$conn->query("set names 'utf8'");
$stmt = $conn->prepare("INSERT INTO `form` (`name`, `birthday`, `country`, `province`, `city`, `district`, `school`, `major`, `grade`, `id`,
                    `phone`, `im`, `github`, `talent`, `experience`, `has_team`, `leader_name`, `cv_original_filename`,
                    `cv_stored_filename`)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssssssssssss", $_POST["name"], $_POST["birthday"], $_POST["country"], $_POST["province"], $_POST["city"], $_POST["district"], $_POST["school"], $_POST["major"], $_POST["grade"], $_POST["id"], $_POST["phone"], $_POST["im"], $_POST["github"], $_POST["talent"], $_POST["experience"], $_POST["hasteam"], $_POST["leadername"], $oldName, $newName);
$stmt->execute();
$stmt->close();
$conn->close();
die(json_encode(array(
    'succ' => true
)));

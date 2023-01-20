<?php
// добивка данных пользователя
session_start();

require_once("../core.php");

$core = new Core();

$phone = $_SESSION['phone'];
$name = $_POST['name'];
$last_name = $_POST['lname'];
$password = $_POST['password'];
$password_ver = $_POST['password_ver'];

if ($password != $password_ver) {
     echo json_encode(array("response" => false, "description" => "Пароли не совпадают"),JSON_UNESCAPED_UNICODE);
}

$result = json_decode($core->update_user_data($phone,$name,$last_name,$password));
if ($result->response == true) {
    echo json_encode(array("response" => true),JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array("response" => false, "description" => $result->description),JSON_UNESCAPED_UNICODE);
}
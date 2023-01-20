<?php
// функция для верификации email
session_start();

require_once("../core.php");

$core = new Core();

$phone = $_SESSION['phone'];

$email = $_POST["email"];

$result = json_decode($core->reg_email($phone,$email));
if ($result->response == true) {
    echo json_encode(array("response" => true, "description" => "Ссылка успешно выслана на email"),JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array("response" => false, "description" => $result->description),JSON_UNESCAPED_UNICODE);
}
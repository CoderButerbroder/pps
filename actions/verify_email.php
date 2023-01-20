<?php
// верификация email

session_start();

require_once("../core.php");

$core = new Core();

$code = $_GET['code'];

$result = json_decode($core->verify_email($code));

if ($result->response == true) {
    header('Location: https://ivanovpps.full-data.ru/user_reg.php');
    exit;
} else {
    echo json_encode(array("response" => false, "description" => $result->description),JSON_UNESCAPED_UNICODE);
}

?>
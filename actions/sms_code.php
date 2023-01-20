<?php

session_start();

require_once("../core.php");

$core = new Core();

$code = $_POST["q1"].$_POST["q2"].$_POST["q3"].$_POST["q4"];

$phone = $_SESSION['phone'];

//echo $phone;
//
//echo $code;

$result = json_decode($core->verify_phone($phone,$code));

if ($result->response == true) {
    echo json_encode(array("response" => true),JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array("response" => false, "description" => $result->description),JSON_UNESCAPED_UNICODE);
}
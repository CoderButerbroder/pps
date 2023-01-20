<?php
// функция ввода телефона
session_start();

require_once("../core.php");

$core = new Core();

if ((!empty($_POST["q1"])) &&
    (!empty($_POST["q2"])) &&
    (!empty($_POST["q3"])) &&
    (!empty($_POST["q4"])) &&
    (!empty($_POST["q5"])) &&
    (!empty($_POST["q6"])) &&
    (!empty($_POST["q7"])) &&
    (!empty($_POST["q8"])) &&
    (!empty($_POST["q9"])) &&
    (!empty($_POST["q10"]))) {
    // $phone = "+7(" . $_POST["q1"] . $_POST["q2"] . $_POST["q3"] . ")" . $_POST["q4"] . $_POST["q5"] . $_POST["q6"] . "-" . $_POST["q7"] . $_POST["q8"] . "-" . $_POST["q9"] . $_POST["q10"];
    $phone_clean = $_POST["q1"] . $_POST["q2"] . $_POST["q3"]  . $_POST["q4"] . $_POST["q5"] . $_POST["q6"] . $_POST["q7"] . $_POST["q8"] . $_POST["q9"] . $_POST["q10"];

        $_SESSION['phone'] = $phone_clean;

        $result = json_decode($core->reg_phone($phone_clean));
        if ($result->response == true)  {
            echo json_encode(array("response" => true, "description" => 'смс было выслано на телфон'),JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(array("response" => false, "description" => $result->description),JSON_UNESCAPED_UNICODE);
        }

} else {
    echo json_encode(array("response" => false, "description" => 'Не все поля были заполнены'),JSON_UNESCAPED_UNICODE);
}
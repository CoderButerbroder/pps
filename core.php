<?php

include("config.php");
require_once(__DIR__.'/plugins/PHPMailer.php');
require_once(__DIR__.'/plugins/SMTP.php');
require_once(__DIR__.'/plugins/Exception.php');

class Core {

    public function send_email_user(string $user_email, string $tema, string $content) {
        global $database;

            $mail = new PHPMailer\PHPMailer\PHPMailer();

            try {
                $msg = "OK";
                $mail->isSMTP();
                $mail->CharSet = "UTF-8";
                $mail->SMTPAuth   = true;

                $email_host2 = "smtp.yandex.ru";
                $email_username2 = "panel@lpmtech.ru";
                $email_pass2 = "8KJo7MEgjor1";
                $email_secure2 = "ssl";
                $email_port2 = "465";
                $email_name2 = "антикафе";

                $mail->Host       = $email_host2;
                $mail->Username   = $email_username2;
                $mail->Password   = $email_pass2;
                $mail->SMTPSecure = $email_secure2;
                $mail->Port       = $email_port2;
                $mail->setFrom($email_username2,$email_name2);

                // Получатель письма
                $mail->addAddress($user_email);

                    $mail->isHTML(true);

                    $mail->Subject = $tema;
                    $mail->Body    = $content;
                    $mail->IsHTML(true);

                  if ($mail->send()) {
                        return json_encode(array('response' => true, 'data' => NULL, 'description' => 'Письмо успешно отправлено на адрес '.$user_email),JSON_UNESCAPED_UNICODE);
                  } else {
                       return json_encode(array('response' => false, 'data' => NULL, 'description' => 'Ошибка отправки письма на адрес '.$user_email.', пожалуйста, попробуйте позже', 'error_info' =>$mail->ErrorInfo),JSON_UNESCAPED_UNICODE);
                  }

            } catch (Exception $e) {
                  return json_encode(array('response' => false, 'data' => NULL, 'description' => 'Системная ошибка отправки письма, пожалуйста попробуйте позже'),JSON_UNESCAPED_UNICODE);
            }

    }

    public function reg_phone($phone) {
        global $database;

        $code_random = rand(1000,9999);

        $body = file_get_contents("https://sms.ru/sms/send?api_id=E16B91F6-A6FB-616E-1B3E-91D68E56B476&to=7".$phone."&msg=".urlencode(iconv("windows-1251","utf-8",$code_random))."&json=1"); # Если приходят крякозябры, то уберите iconv и оставьте только urlencode("Привет!")

        $json = json_decode($body);
        if ($json) { // Получен ответ от сервера
            // print_r($json); // Для дебага
            if ($json->status == "OK") { // Запрос выполнился

                // проверяем есть ли уже такой пользователь
                      $statement = $database->prepare(" SELECT * FROM `sms_phone` WHERE phone = :phone ");
                      $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
                      $statement->execute();
                      $check = $statement->fetch(PDO::FETCH_OBJ);

                      if ($check) {

                            $upd_profile = $database->prepare("UPDATE `sms_phone` SET sms_last_code = :sms_last_code WHERE phone = :phone");
                            $upd_profile->bindParam(':phone', $phone, PDO::PARAM_STR);
                            $upd_profile->bindParam(':sms_last_code', $code_random, PDO::PARAM_INT);
                            $temp = $upd_profile->execute();
                            $count = $upd_profile->rowCount();

                            if ($count) {
                                return json_encode(array('response' => true, 'description' => 'Успешное обновление кода смс'), JSON_UNESCAPED_UNICODE);
                            }
                            else {
                                return json_encode(array('response' => false, 'description' => 'Ошибка обновления кода смс'), JSON_UNESCAPED_UNICODE);
                            }
                      } else {
                          // добавляем пользователя
                          $hash_key = md5($phone.$code_random.date("Y-m-d H:i:s"));

                          $statement = $database->prepare(" INSERT INTO `sms_phone` (phone,key_user,sms_last_code)
                                           VALUES (:phone,:key_user,:sms_last_code) ");

                         $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
                         $statement->bindParam(':key_user', $hash_key, PDO::PARAM_STR);
                         $statement->bindParam(':sms_last_code', $code_random, PDO::PARAM_INT);
                         $statement->execute();
                         $id_insert = $database->lastInsertId();
                         if ($id_insert) {
                             return json_encode(array('response' => true, 'description' => 'Успешное добавление телефона пользователя'), JSON_UNESCAPED_UNICODE);
                         } else {
                             return json_encode(array('response' => false, 'description' => 'Не удалось добавить телефон пользователя '), JSON_UNESCAPED_UNICODE);
                         }
                      }

            } else { // Запрос не выполнился (возможно ошибка авторизации, параметрах, итд...)
                return json_encode(array('response' => false, 'description' => 'Не удалось отправить смс'), JSON_UNESCAPED_UNICODE);
            }
        } else {
            return json_encode(array('response' => false, 'description' => 'Не получен ответ от смс сервиса'), JSON_UNESCAPED_UNICODE);
        }

    }


    public function verify_phone($phone,$code) {
        global $database;

        $statement = $database->prepare(" SELECT * FROM `sms_phone` WHERE phone = :phone and sms_last_code = :sms_last_code");
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->bindParam(':sms_last_code', $code, PDO::PARAM_INT);
        $statement->execute();
        $check = $statement->fetch(PDO::FETCH_OBJ);

        if ($check) {
                    $verify_phone = 1;
                    $upd_profile = $database->prepare("UPDATE `sms_phone` SET verify_phone = :verify_phone WHERE phone = :phone");
                    $upd_profile->bindParam(':verify_phone', $verify_phone, PDO::PARAM_INT);
                    $upd_profile->bindParam(':phone', $phone, PDO::PARAM_INT);
                    $temp = $upd_profile->execute();
                    $count = $upd_profile->rowCount();
            return json_encode(array('response' => false, 'description' => 'Успешная верификация телефона'), JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(array('response' => false, 'description' => 'Не верный код '), JSON_UNESCAPED_UNICODE);
        }

    }

    public function reg_email($phone,$email) {
        global $database;

        $statement = $database->prepare(" SELECT * FROM `sms_phone` WHERE phone = :phone");
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->execute();
        $check = $statement->fetch(PDO::FETCH_OBJ);

        if ($check) {

            $upd_profile = $database->prepare("UPDATE `sms_phone` SET email = :email WHERE phone = :phone");
            $upd_profile->bindParam(':email', $email, PDO::PARAM_STR);
            $upd_profile->bindParam(':phone', $phone, PDO::PARAM_STR);
            $temp = $upd_profile->execute();
            $count = $upd_profile->rowCount();

            if ($count) {
                $content = '<html><head></head><body><a href="https://ivanovpps.full-data.ru/actions/verify_email.php?code='.$check->key_user.'">Перейдите по ссылке для верификации email</a></body><html>';
                $this->send_email_user( $email,  'Верификация email',  $content);
                return json_encode(array('response' => true, 'description' => 'Успешная запись email'), JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(array('response' => false, 'description' => 'Ошибка записи email'), JSON_UNESCAPED_UNICODE);
            }
        } else {
            return json_encode(array('response' => false, 'description' => 'Пользовтаель не надйен'), JSON_UNESCAPED_UNICODE);
        }

    }


    public function verify_email($code_email) {
        global $database;

        $verify_email = 1;
        $statement = $database->prepare("UPDATE `sms_phone` SET verify_email = :verify_email and key_user = :key_user");
        $statement->bindParam(':verify_email', $verify_email, PDO::PARAM_STR);
        $statement->bindParam(':key_user', $code_email, PDO::PARAM_STR);
        $temp = $statement->execute();
        $count = $statement->rowCount();

        if ($count) {
            return json_encode(array('response' => true, 'description' => 'email верифицирован'), JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(array('response' => false, 'description' => 'email не верифицирован'), JSON_UNESCAPED_UNICODE);
        }

    }

    public function update_user_data($phone,$name,$last_name,$password) {
        global $database;

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $upd_profile = $database->prepare("UPDATE `sms_phone` SET name = :name, password = :password, last_name = :last_name WHERE phone = :phone");
        $upd_profile->bindParam(':password', $password_hash, PDO::PARAM_STR);
        $upd_profile->bindParam(':name', $name, PDO::PARAM_STR);
        $upd_profile->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $upd_profile->bindParam(':phone', $phone, PDO::PARAM_STR);
        $temp = $upd_profile->execute();
        $count = $upd_profile->rowCount();

        if ($count) {
            return json_encode(array('response' => true, 'description' => 'Успешная регистрация пользвоателя'), JSON_UNESCAPED_UNICODE);
        }
        else {
            return json_encode(array('response' => false, 'description' => 'Ошибка регистарции пользоватеоя'), JSON_UNESCAPED_UNICODE);
        }

    }



    public function logout() {
        session_start();
        $_SESSION = array();

        //$token = $_COOKIE["token_auth_user"];
        //$this->delete_token_auth($token);

        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', 1);
                setcookie($name, '', 1, '/');
            }
        }
        session_destroy();
        session_write_close();
        return true;

    }


}
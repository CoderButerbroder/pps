<?php
session_start();

require_once("../core.php");

$core = new Core();

$core->logout();

header('Location: /');
exit;
<?php
require_once '../core.php';

$app = new manz();
$app->logout();
header('Location: loginad.php');
exit;

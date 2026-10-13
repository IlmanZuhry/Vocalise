<?php
require_once __DIR__ . '/core.php';
$app = new manz();
$app->logout();
header('Location: index.php');
exit;

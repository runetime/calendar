<?php

require(__DIR__ . '/../functions.php');

if (isset($_GET['level'])) {
    $level = $_GET['level'];
} else {
    die('A level must be given.');
}

if (isset($_GET['user'])) {
    $userEncrypted = $_GET['user'];
} else {
    die('A user must be given.');
}

$user = encrypter('decrypt', $userEncrypted);
$canEdit = canEdit($level);
$canEditAll = canEditAll($level);

$currentHour = date('H');
$week = date('W');

require(__DIR__ . '/../views/calendar.php');

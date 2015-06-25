<?php

require(__DIR__ . '/../functions.php');

if (isset($_GET['level'])) {
    $levelEncrypted = $_GET['level'];
} else {
    die('A level must be given.');
}

if (isset($_GET['user'])) {
    $userEncrypted = $_GET['user'];
} else {
    die('A user must be given.');
}

$user = encrypter('decrypt', $userEncrypted);
$level = encrypter('decrypt', $levelEncrypted);
$canEdit = canEdit($level);
$canEditAll = canEditAll($level);

$currentHour = date('H');
$currentDay = date('N') - 1;
$week = date('W');

require(__DIR__ . '/../views/calendar.php');

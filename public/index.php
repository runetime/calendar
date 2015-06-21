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

$hours = getHours();

$currentHour = date('H');

$monday = date('jS', strtotime('last monday'));
$mondayMonth = date('M', strtotime('last monday'));
$sunday = date('jS', strtotime('next sunday'));
$sundayMonth = date('M', strtotime('last monday'));

if (date('N') == 1) {
    $monday = date('jS');
    $mondayMonth = date('M');
}

if (date('N') == 7) {
    $sunday = date('jS');
    $sundayMonth = date('M');
}

$year = date('Y');

require(__DIR__ . '/../views/calendar.php');

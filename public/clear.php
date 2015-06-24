<?php

error_reporting(0);

require(__DIR__ . '/../functions.php');

if (isset($_POST['level'])) {
    $level = $_POST['level'];
} else {
    die('A level must be given.');
}

if (isset($_POST['user'])) {
    $userEncrypted = $_POST['user'];
} else {
    die('A user must be given.');
}

$level = encrypter('decrypt', $level);
$user = encrypter('decrypt', $userEncrypted);

if (!canEditAll($level)) {
    die('Sorry, you can not clear the calendar.');
}

$week = (int) $_POST['week'];

if ($week > 52 || $week < 0) {
    die('Sorry, that week is not valid.');
}

// Query error code:
// :) = Hour claimed.
// :| = The person who claimed the hour removed themself.
// :( = Someone else already claimed the hour.
// D: = There was a SQL error.

$link = mysql_connect(env('DB_HOST'), env('DB_USER'), env('DB_PASS'), env('DB_NAME'));
mysql_select_db(env('DB_NAME'));

// Check if the hour has already been claimed.
$query = <<<QUERY
DELETE FROM `radio_history`
WHERE `week`=$week
QUERY;

die(mysql_query($query) ? ':)' : ':(');

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

if (!canEdit($level)) {
    die('Sorry, you can not claim hours.');
}

$day = $_POST['day'];
$hour = $_POST['hour'];
$week = $_POST['week'];

$day = (int) $day;
$hour = (int) $hour;
$week = (int) $week;

if ($day > 6 || $day < 0) {
    die('Sorry, that day is not valid.');
}

if ($hour > 23 || $hour < 0) {
    die('Sorry, that hour is not valid.');
}

if ($week > 52 || $week < 0) {
    die('Sorry, that week is not valid.');
}

// Query error code:
// :) = Hour claimed.
// :| = The person who claimed the hour removed themself.
// :( = Someone else already claimed the hour.
// D: = There was a SQL error.

$link = mysqli_connect(env('DB_HOST'), env('DB_USER'), env('DB_PASS'), env('DB_NAME'));
mysqli_select_db(env('DB_NAME'));

// Check if the hour has already been claimed.
$claimedQuery = '
SELECT `dj_name`
FROM `radio_history`
WHERE `week`=' . $week . ' and `day`=' . $day . ' and `hour`=' . $hour;
$claimed = mysqli_fetch_object(mysqli_query($link, $claimedQuery));

// Check if someone claimed the hour.
if (!empty($claimed)) {
    // If they are the person who claimed it, let's remove them for the hour.
    if ($claimed->dj_name === $user || canEditAll($level)) {
        $query = <<<QUERY
DELETE FROM `radio_history`
WHERE `week`=$week and `day`=$day and `hour`=$hour
QUERY;

        $results = mysqli_query($link, $query);

        die($results ? ':|' : 'D:');
    } else {
        // Looks like they aren't, error!
        die(':(');
    }
}

// Looks like no one has, let's let them claim this hour.
$query = <<<QUERY
INSERT INTO
`radio_history` (`week`, `day`, `hour`, `dj_name`)
VALUES
($week, $day, $hour, '$user')
QUERY;

$results = mysqli_query($link, $query);

die($results ? ':)' : 'D:');

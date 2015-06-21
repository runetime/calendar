<?php

date_default_timezone_set('GMT');

function canEdit($level) {
    $allowedString = env('AUTHORIZED');
    $authorized = explode(',', $allowedString);

    return in_array($level, $authorized);
}

function canEditAll($level) {
	$allowedString = env('AUTHORIZED_ALL');
	$authorized = explode(',', $allowedString);

	return in_array($level, $authorized);
}

function databaseQuery($query) {
    static $link = null;

    if (is_null($link)) {
        $link = mysqli_connect('127.0.0.1', env('DB_USER'), env('DB_PASS'), env('DB_NAME'));
    }

    $results = $link->query($query);

    $resultList = [];

    while ($result = mysqli_fetch_object($results)) {
        $resultList[] = $result;
    }

    return $resultList;
}

function dd($val) {
    die(var_dump($val));
}

function dec($string, $method, $key, $sub) {
    $decrypted = base64_decode($string);

    return openssl_decrypt($decrypted, $method, $key, 0, $sub);
}

function enc($string, $method, $key, $sub) {
    $encrypted = openssl_encrypt($string, $method, $key, 0, $sub);
    $output = base64_encode($encrypted);

    return $output;
}

function encrypter($action, $string) {
    $output = false;

    $method = env('ENCRYPTION_METHOD');
    $key = env('ENCRYPTION_KEY');
    $iv = env('ENCRYPTION_IV');

    // hash
    $key = hash('sha256', $key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $sub = substr(hash('sha256', $iv), 0, 16);

    switch ($action) {
        case 'encrypt':
            $output = enc($string, $method, $key, $sub);

            break;
        case 'decrypt':
            $output = dec($string, $method, $key, $sub);

            break;
    }

    return $output;
}

function env($key) {
    $file = file_get_contents(__DIR__ . '/.env');

    $lines = explode("\n", $file);

    $value = '';

    foreach ($lines as $line) {
        $lineArray = explode('=', $line, 2);

        // Check if this is equal to the $key that's wanted.
        if ($lineArray[0] == $key) {
            $value = rtrim($lineArray[1]);

            // Now let's skip the rest.
            break;
        }
    }

    return $value;
}

function getHours() {
    $hours = [];

    // Set some defaults for those that haven't been claimed.
    for ($i = 0; $i < 24; $i++) {
        $hours[$i] = [];

        for ($j = 0; $j < 7; $j++) {
            $hours[$i][$j] = '--';
        }
    }

    $weekCurrent = date('W');

    $results = databaseQuery('SELECT `day`, `dj_name`, `hour` FROM `radio_history` WHERE `week`=' . $weekCurrent . ' ORDER BY day,hour ASC');

    foreach ($results as $result) {
        $hours[$result->hour][$result->day] = $result->dj_name;
    }

    return $hours;
}

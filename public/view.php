<?php

require(__DIR__ . '/../functions.php');

$hours = getHours();

echo json_encode($hours);

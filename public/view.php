<?php

require(__DIR__ . '/../functions.php');

$timestamp = (int) $_GET['timestamp'];
$week = (int) $_GET['week'];

$hours = [
    'info' => [
        'monday' => date('jS', strtotime('last monday', $timestamp)),
        'monday_month' => date('M', strtotime('last monday', $timestamp)),
        'sunday' => date('jS', strtotime('next sunday', $timestamp)),
        'sunday_month' => date('M', strtotime('last sunday', $timestamp)),
        'week' => $week,
        'year' => date('Y', strtotime('last monday', $timestamp)),
    ],
    'hours' => getHours($week),
];

if (date('N') == 1) {
    $hours['info']['monday'] = date('jS', $timestamp);
    $hours['info']['monday_month'] = date('M', $timestamp);
}

if (date('N') == 7) {
    $hours['info']['sunday'] = date('jS', $timestamp);
    $hours['info']['sunday_month'] = date('M', $timestamp);
    $hours['info']['year'] = date('Y', $timestamp);
}

echo json_encode($hours);

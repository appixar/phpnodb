<?php
session_start();

// Global functions
include __DIR__ . '/functions/helpers.php';
include __DIR__ . '/functions/arrayEditor.php';

// Public data
$fn_conf = 'data/public/config.json';
$fn_data = 'data/public/data.json';
$fn_pass = 'data/private/password_cli.php';
$fn_pass_dev = 'data/private/password_dev.php';

include __DIR__ . '/' . $fn_pass;
include __DIR__ . '/' . $fn_pass_dev;

// GET CONFIG
$conf = json_decode(file_get_contents(__DIR__ . '/' . $fn_conf), true); // php array
$conf_id = getDataById($conf);
$_SESSION['current_conf'] = file_get_contents(__DIR__ . '/' . $fn_conf); // json

// GET DATA
$data = json_decode(file_get_contents(__DIR__ . '/' . $fn_data), true); // php array
$data_id = getDataById($data);
$_SESSION['current_data'] = file_get_contents(__DIR__ . '/' . $fn_data); // json
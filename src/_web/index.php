<?php

$basepath = dirname(__FILE__);

if (isset($_GET['file'])) {
    $file = strval($_GET['file']);
    $file = str_replace('../', '', $file);
}

if (empty($file)) {
    $file = 'index.php';
}

$fullpath = sprintf($basepath . '/../vendor/generatedata/%s', $file);

if (!is_file($fullpath)){
    die('Invalid call.');
}

include_once $fullpath;

<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//
//// create curl resource
//$ch = curl_init();
//
//// set url
//curl_setopt($ch, CURLOPT_URL, "https://www.google.com/");
////curl_setopt($ch, CURLOPT_URL, "https://testcustomersamocrmru.amocrm.ru/api/v4/account");
//
////return the transfer as a string
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//
//// $output contains the output string
//$output = curl_exec($ch);
//
//// close curl resource to free up system resources
//curl_close($ch);
//print_r($output);
require "vendor/autoload.php";
$start = new MyApp\App();
$token = $start->initToken();
$start->start($token);
//$test = new MyApp\Controllers\IndexController();
//$test->run();


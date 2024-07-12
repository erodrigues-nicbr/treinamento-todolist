<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "functions.php";
include_once "configure.php";

$uri = "mysql:host={$config['host']};dbname=$config[database]";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

$pdoMysql = new PDO($uri, $config['user'], $config['password'], $options);
$smtp = $pdoMysql->prepare("select * from tasks;");

try{
    $smtp->execute();
    $result = $smtp->fetchAll();
}catch(Throwable $e){
    pr($e->getMessage());
    exit();
}
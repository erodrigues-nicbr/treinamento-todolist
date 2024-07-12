<?php 

function pr($message){
    echo "<pre>" . print_r($message, true) . "</pre>";
}

function getElement($elementName, $options = [] ){
    extract($options);
    include_once ROOT . DS . "elements" . DS . "$elementName.php";
}

function connectDb( ){

    $config = Config::getAllConfig();
    $uri = "mysql:host={$config['host']};dbname=$config[database]";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    return new PDO($uri, $config['user'], $config['password'], $options);
}
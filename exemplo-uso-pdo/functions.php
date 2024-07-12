<?php 

function pr($message){
    echo "<pre>" . print_r($message, true) . "</pre>";
}

function getElement($elementName){
    include_once ROOT . DS . "partes" . DS . "$elementName.php";
}

pr("oi");
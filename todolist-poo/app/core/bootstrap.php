<?php 
include_once ROOTDIR . DS . 'core' . DS .'functions.php';

// Verifica se o arquivo configure.php existe
$configFullName = ROOTDIR . DS . "confs" . DS . 'configure.php';
if(file_exists($configFullName) ) {
    include_once $configFullName;
}else{
    die('Arquivo configure.php não encontrado. Copie o arquivo confs/configure.php.example e renomeie para "confs/configure.php".');
}

// Apenas para ambiente de desenvolvimento
if( Configure::read('App.ShowErrors', false ) ) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}else{
    error_reporting(E_ALL & ~E_WARNING);
}

// setup do datasource
include_once ROOTDIR . DS . 'core' . DS . 'datasource.php';

// Inclui o arquivo de rotas
include_once ROOTDIR . DS . "confs" . DS . 'routes.php';

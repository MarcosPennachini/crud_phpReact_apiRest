<?php
require 'config.php';

// if (!file_exists('./config.php')) {
//     die('ERROR: No existe el archivo de configuración');
// }

$connection = null;


function connect()
{
    try {
        $GLOBALS['connection'] = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_DATABASE, DB_USER, DB_PASS);
        $GLOBALS['connection']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $GLOBALS['connection'];
    } catch (PDOException $e) {
        echo 'ERROR: No se pudo conectar a la base de datos' . DB_DATABASE, $e->getMessage();
        die();
    }
}

function disconnect()
{
    $GLOBALS['connection'] = null;
}

<?php

    require __DIR__ . '/config.php'; 
    require __DIR__ . '/class/autoloader.php'; 
    Autoloader::register();

    $PDO = function($host, $database, $charset, $username, $password = null, $options = null) { return new \PDO("mysql:host=$host;dbname=$database;charset=$charset", $username, $password, $options); };

?>
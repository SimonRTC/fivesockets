<?php

    require __DIR__ . '/config.php'; 
    require __DIR__ . '/class/autoloader.php'; 
    Autoloader::register();

    $PDO = function() use ($Config) { return new \PDO('mysql:host=' . $Config['host'] . ';dbname=' . $Config['database'] . ';charset=' . $Config['charset'] . '', $Config['username'], $Config['password'], $Config['options']); };

?>
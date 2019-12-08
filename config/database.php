<?php

$name = 'lanik05';
$username = 'lanik05';
$password = 'wt2018';
$host = 'localhost';

return [
    'type' => 'mysql',
    'options' => [
        'PDO::MYSQL_ATTR_INIT_COMMAND' => 'SET NAMES \'UTF8\''
    ],
    'dsn' => 'mysql:dbname=' . $name . ';host=' . $host,
    'host' => $host,
    'name' => $name,
    'username' => $username,
    'password' => $password,
];

?>
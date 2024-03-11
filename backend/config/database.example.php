<?php

return [
    'mysql' => [
        'dbname'    => 'project_name',
        'host'      => 'localhost',
        'user'      => 'root',
        'password'  => 'root',
        'port'      => 3306,
        'charset'   => 'utf8',

        'options' => [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    ],
];
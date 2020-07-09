<?php

$dataenv = parse_ini_file('../.env');

return [
        'type' => $dataenv['DB_TYPE'],
        'host' => $dataenv['DB_HOST'],
        'database' => $dataenv['DB_DATABASE'],
        'username' => $dataenv['DB_USERNAME'],
        'password' => $dataenv['DB_PASSWORD'],
    ];
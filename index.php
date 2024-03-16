<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// TODO: Connect to database
$db_host = $_ENV['DB_HOST'];
$db_user = $_ENV['DB_USER'];
$db_password = $_ENV['DB_PASSWORD'];
$db_name = $_ENV['DB_NAME'];
$connect = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if(!$connect){
    echo "Could not connect to DB. :(";
}else{
    echo "Sucessfully connected to the DB :+1:!";
}

// TODO: Include JS files from dist/ of vite
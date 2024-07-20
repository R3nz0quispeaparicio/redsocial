<?php
$db_host = "bo748bvup13ccw72g9tv-mysql.services.clever-cloud.com";
$db_name = "bo748bvup13ccw72g9tv";
$db_user = "ubtwztmem0uyldhg";
$db_pass = "ubtwztmem0uyldhg";
$socket_io_url = "http://localhost:3000";
try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "";
} catch (PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}

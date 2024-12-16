<?php
$host = "localhost";
$usuari = "user";
$password = "123";
$database = "blog";

$db = mysqli_connect($host, $usuari, $password, $database);

mysqli_query($db, "SET NAMES 'utf8'");

if (!$db) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
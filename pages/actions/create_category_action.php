<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];

    // Validació bàsica del camp
    if (empty($nombre)) {
        echo "El nom de la categoria no pot estar buit.";
        exit;
    }

    // Consulta per afegir una nova categoria
    $sql = "INSERT INTO categories (nombre) VALUES (?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $nombre);

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit;
    } else {
        echo "Error al crear la categoria.";
    }

    $stmt->close();
    $db->close();
}
?>

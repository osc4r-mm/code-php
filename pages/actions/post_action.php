<?php
session_start();
require_once '../config/db.php';
require_once '../config/utility.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $titol = trim($_POST['titol']);
    $descripcio = trim($_POST['descripcio']);
    $categoria = $_POST['categoria'];
    $usuari_id = $_SESSION['user']['usuari_id'];
    
    // Editar
    if ($id) {
        // Consulta per actualitzar un post
        $sql = "UPDATE entrades SET titol = ?, descripcio = ?, categoria_id = ? WHERE id = ? AND usuari_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssiii", $titol, $descripcio, $categoria, $id, $usuari_id);

        if ($stmt->execute()) {
            header("Location: ../index.php?page=post&id=$id");
            exit;
        } else {
            set_message('post', "Error a l'editar l'entrada", 'error');
            header("Location: ../index.php?page=post&id=$id");
            exit;
        }
    
    }
    // Crear 
    else {
        // Consulta per crear un post
        $sql = "INSERT INTO entrades (usuari_id, categoria_id, titol, descripcio, data) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiss", $usuari_id, $categoria, $titol, $descripcio);

        if ($stmt->execute()) {
            $new_post_id = $db->insert_id;
            header("Location: ../index.php?page=post&id=$new_post_id");
            exit;
        } else {
            set_message('post', "Error al crear l'entrada", 'error');
            header("Location: ../index.php");
            exit;
        }

        $db->close();
    }
}
?>

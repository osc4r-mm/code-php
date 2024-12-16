<?php
require_once '../config/db.php'; // Ajusta la ruta según tu estructura de carpetas

if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']); // Sanitizar el ID recibido

    if ($post_id > 0) {
        $sql = "DELETE FROM entrades WHERE id = ?";

        $stmt = $db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $post_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Eliminar exitoso
                header("Location: ../index.php?message=post_deleted");
                exit();
            } else {
                // No se eliminó (posiblemente porque no existe)
                echo "<p>No s'ha pogut eliminar l'entrada. Potser no existeix.</p>";
            }

            $stmt->close();
        } else {
            echo "<p>Error en preparar la consulta: " . $db->error . "</p>";
        }
    } else {
        echo "<p>ID d'entrada no vàlid.</p>";
    }
} else {
    echo "<p>No s'ha especificat cap ID d'entrada.</p>";
}

// Tanca la connexió a la base de dades
$db->close();
?>

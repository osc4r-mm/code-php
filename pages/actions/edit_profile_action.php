<?php
session_start();
require_once '../config/db.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuari_id'])) {
    header("Location: ../index.php");
    exit;
}

$usuari_id = $_SESSION['usuari_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $cognom = trim($_POST['cognom']);
    $email = trim($_POST['email']);

    // Obtén el email actual del usuario
    $sql = "SELECT email FROM usuaris WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $usuari_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifica si el correo es único o si no ha cambiado
    if ($email !== $user['email']) {
        $check_email_sql = "SELECT id FROM usuaris WHERE email = ? AND id != ?";
        $check_stmt = $db->prepare($check_email_sql);
        $check_stmt->bind_param("si", $email, $usuari_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "El correu electrònic ja està en ús per un altre usuari.";
        } else {
            // Actualiza los datos del usuario si el correo es válido
            $sql = "UPDATE usuaris SET nom = ?, cognom = ?, email = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("sssi", $nom, $cognom, $email, $usuari_id);

            if ($stmt->execute()) {
                $_SESSION['usuari_nom'] = $nom;
                $message = "Perfil actualitzat correctament.";
            } else {
                $message = "Error al actualitzar el perfil.";
            }
        }
    } else {
        // Si el correo no cambió, actualiza los demás datos
        $sql = "UPDATE usuaris SET nom = ?, cognom = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssi", $nom, $cognom, $usuari_id);

        if ($stmt->execute()) {
            $_SESSION['usuari_nom'] = $nom;
            $message = "Perfil actualitzat correctament.";
        } else {
            $message = "Error al actualitzar el perfil.";
        }
    }
}

$_SESSION['message'] = $message;
header("Location: ../index.php");
exit;

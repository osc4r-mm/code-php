<?php
session_start();
require_once '../config/db.php';
require_once '../config/utility.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user']['usuari_id'])) {
    header("Location: ../index.php");
    exit;
}

$usuari_id = $_SESSION['user']['usuari_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $cognom = trim($_POST['cognom']);
    $email = trim($_POST['email']);

    // Validar campos vacíos
    if (empty($nom) || empty($cognom) || empty($email)) {
        set_message('profile_edit', 'Tots els camps són obligatoris.', 'error');
        header("Location: ../index.php?page=form_profile");
        exit;
    }

    // Validar formato de nombre y apellido
    $nomCognomPattern = "/^[a-zA-Z\-\_\']+$/";
    if (!preg_match($nomCognomPattern, $nom)) {
        set_message('nom', 'El format del nom no és vàlid.', 'error');
    }

    if (!preg_match($nomCognomPattern, $cognom)) {
        set_message('cogmon', 'El format del cognom no és vàlid.', 'error');
    }

    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_message('email', 'El format del correu electrònic no és vàlid.', 'error');
    }

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
            set_message('profile_edit', 'Aquest correu ja està en ús', 'error');
            header("Location: ../index.php?page=form_profile");
            exit;
        } else {
            // Actualiza los datos del usuario si el correo es válido
            $sql = "UPDATE usuaris SET nom = ?, cognom = ?, email = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("sssi", $nom, $cognom, $email, $usuari_id);

            if ($stmt->execute()) {
                $_SESSION['user']['usuari_nom'] = $nom;
                set_message('profile', 'Perfil actualitzat correctament', 'success');
            } else {
                set_message('profile', "Error a l'actualitzar el perfil", 'error');
            }
        }
    } else {
        // Si el correo no cambió, actualiza los demás datos
        $sql = "UPDATE usuaris SET nom = ?, cognom = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssi", $nom, $cognom, $usuari_id);

        if ($stmt->execute()) {
            $_SESSION['user']['nom'] = $nom;
            set_message('profile', 'Perfil actualitzat correctament', 'success');
        } else {
            set_message('profile', "Error a l'actualitzar el perfil", 'error');
        }
    }
}

header("Location: ../index.php");
exit;

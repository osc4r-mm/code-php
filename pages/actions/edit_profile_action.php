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
    $nom = sanitize_input($_POST['nom']);
    $cognom = sanitize_input($_POST['cognom']);
    $email = sanitize_input($_POST['email']);

    if (!check_required_fields([$nom, $cognom, $email])) {
        set_message('profile_edit', 'Tots els camps són obligatoris.', 'error');
    } 
    if (!validate_name($nom)) {
        set_message('nom', 'El format del nom no és vàlid.', 'error');
    } 
    if (!validate_name($cognom)) {
        set_message('cognom', 'El format del cognom no és vàlid.', 'error');
    } 
    if (!validate_email($email)) {
        set_message('email', 'El format del correu electrònic no és vàlid.', 'error');
    } 
    if (check_email_exists_edit($db, $email, $usuari_id)) {
        set_message('profile_edit', 'Aquest correu ja està en ús', 'error');
    } 
    if (!has_messages() ) {
        if (update_profile($db, $usuari_id, $nom, $cognom, $email)) {
            $_SESSION['user']['nom'] = $nom;
            set_message('profile', 'Perfil actualitzat correctament', 'success');
        } else {
            set_message('profile', "Error a l'actualitzar el perfil", 'error');
        }
    }
}
    
    header("Location: ../index.php");
    exit;
?>
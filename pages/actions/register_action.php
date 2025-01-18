<?php
session_start();
include('../config/db.php');
include('../config/utility.php');

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener la página actual
    $current_page = isset($_POST['current_page']) ? $_POST['current_page'] : 'home';
    $redirect_url = "../index.php" . ($current_page != 'home' ? "?page=" . $current_page : "");

    // Validacio conexio bd
    if ($db->connect_error) {
        set_message('error', "S'ha produit un error al connectar a la base de dades.", 'error');
        header("Location: " . $redirect_url);
        exit();
    }

    // Obtenir dades del formulari
    $nom = sanitize_input($_POST['nom']);
    $cognom = sanitize_input($_POST['cognom']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    if (!check_required_fields([$nom, $cognom, $email, $password])) {
        set_message('register', 'Tots els camps son obligatoris.', 'error');
    } 
    if (!validate_name($nom)) {
        set_message('nom', 'El format del nom no és vàlid.', 'error');
    } 
    if (!validate_name($cognom)) {
        set_message('cognom', 'El format del cognom no és vàlid.', 'error');
    } 
    if (!validate_email($email)) {
        set_message('email', "El format de l'email no es valid.", 'error');
    } 
    if (!validate_password($password)) {
        set_message('password', 'La contrasenya ha de tenir almenys 8 caràcters...', 'error');
    } 
    if (check_email_exists($db, $email)) {
        set_message('email', "Aquest email ja està registrat.", 'error');
    } 
    if (!has_messages()) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        if (register_user($db, $nom, $cognom, $email, $hashed_password)) {
            set_message('register', 'Usuari registrat correctament!', 'success');
        } else {
            set_message('register', "Error al registrar l'usuari.", 'error');
        }
    }

    // Tancar la conexió
    $db->close();
    header("Location: " . $redirect_url);
    exit();
}
?>

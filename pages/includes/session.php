<?php
// Funcio para registrar un error
function set_error($error_key, $error_message) {
    $_SESSION['errors'][$error_key] = $error_message;
}

// Funcio para registrar un missatge
function set_message($key, $message) {
    $_SESSION[$key] = $message;
}

// Funcio per obtenir i netejar els errors
function get_errors() {
    if (isset($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        unset($_SESSION['errors']);
        return $errors;
    }
    return [];
}
// Funcio per obtenir i netejar un missatge
function get_messages($key) {
    if (isset($_SESSION[$key])) {
        $data = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $data;
    }
    return null;
}
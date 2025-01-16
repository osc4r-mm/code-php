<?php

/* 
    Missatges
*/

// Registrar un missatge i el seu tipus
function set_message(string $key, string $message, string $type = 'info'): void {
    $_SESSION['messages'][$key] = [
        'content' => $message,
        'type' => $type,
    ];
}

// Obtenir i netejar tots els missatges
function get_messages(): array {
    $messages = $_SESSION['messages'] ?? [];
    unset($_SESSION['messages']);
    return $messages;
}

// Verificar si hi ha missatges
function has_messages(): bool {
    return !empty($_SESSION['messages']);
}
?>
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

/* 
    Validar
*/

function validate_name($name): bool {
    $pattern = "/^[a-zA-ZàáèéíòóúçñÀÁÈÉÍÒÓÚÇÑ\-\_\']+$/";
    return !empty($name) && preg_match($pattern, trim($name));
}

function validate_email($email): bool {
    return !empty($email) && filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}

function validate_password($password): bool {
    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    return !empty($password) && preg_match($pattern, $password);
}

function check_email_exists($db, $email): bool {
    $stmt = $db->prepare("SELECT id FROM usuaris WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    return false;
}

function validate_register_data($data, &$errors): bool {
    $is_valid = true;

    // Validar camps obligatoris
    if (empty($data['nom']) || empty($data['cognom']) || 
        empty($data['email']) || empty($data['password'])) {
        $errors['register'] = 'Tots els camps son obligatoris.';
        return false;
    }

    // Validar nom
    if (!validate_name($data['nom'])) {
        $errors['nom'] = 'El format del nom no és vàlid.';
        $is_valid = false;
    }

    // Validar cognom
    if (!validate_name($data['cognom'])) {
        $errors['cognom'] = 'El format del cognom no és vàlid.';
        $is_valid = false;
    }

    // Validar email
    if (!validate_email($data['email'])) {
        $errors['email'] = "El format de l'email no es valid.";
        $is_valid = false;
    }

    // Validar contrasenya
    if (!validate_password($data['password'])) {
        $errors['password'] = 'La contrasenya ha de tenir almenys 8 caràcters, ' .
                            'incloent-hi una majúscula, una minúscula, un número ' .
                            'i un caràcter especial.';
        $is_valid = false;
    }

    return $is_valid;
}

// Sanititzar datos
function sanitize_input($data): array {
    $sanitized = [];
    foreach ($data as $key => $value) {
        if ($key === 'email') {
            $sanitized[$key] = filter_var(trim($value), FILTER_SANITIZE_EMAIL);
        } else {
            $sanitized[$key] = trim(htmlspecialchars($value));
        }
    }
    return $sanitized;
}
?>
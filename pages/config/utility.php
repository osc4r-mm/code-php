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

// Validar nom i cognom
function validate_name($name): bool {
    $pattern = "/^[a-zA-ZàáèéíòóúçñÀÁÈÉÍÒÓÚÇÑ\-\_\']+$/";
    return !empty($name) && preg_match($pattern, trim($name));
}

// Validar email
function validate_email($email): bool {
    return !empty($email) && filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}

// Validar contrasenya
function validate_password($password): bool {
    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    return !empty($password) && preg_match($pattern, $password);
}

// Comprovar si l'email existeix (per a nous usuaris)
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

// Comprovar si l'email existeix o es el mateix (per a edició de perfil)
    function check_email_exists_edit($db, $new_email, $usuari_id): bool {
        // Primero obtenemos el email actual del usuario
        $stmt = $db->prepare("SELECT email FROM usuaris WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $usuari_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
    
            // Si el email nuevo es igual al actual, retornamos false (no hay conflicto)
            if ($new_email === $user['email']) {
                return false;
            }
    
            // Si el email es diferente, verificamos si existe en otro usuario
            $stmt = $db->prepare("SELECT id FROM usuaris WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $new_email, $usuari_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $exists = $result->num_rows > 0;
            $stmt->close();
            return $exists;
        }
        return false;
    }
// Validar camps obligatoris
function check_required_fields($fields): bool {
    foreach ($fields as $field) {
        if (empty(trim($field))) {
            return false;
        }
    }
    return true;
}

// Netejar entrada
function sanitize_input($input): string {
    return trim(htmlspecialchars($input));
}

// Validar i actualitzar perfil
function update_profile($db, $usuari_id, $nom, $cognom, $email = null): bool {
    if ($email) {
        $sql = "UPDATE usuaris SET nom = ?, cognom = ?, email = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssi", $nom, $cognom, $email, $usuari_id);
    } else {
        $sql = "UPDATE usuaris SET nom = ?, cognom = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssi", $nom, $cognom, $usuari_id);
    }
    
    $success = $stmt && $stmt->execute();
    if ($stmt) $stmt->close();
    return $success;
}

// Registrar nou usuari
function register_user($db, $nom, $cognom, $email, $hashed_password): bool {
    $sql = "INSERT INTO usuaris (nom, cognom, email, password, data) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssss", $nom, $cognom, $email, $hashed_password);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}
?>
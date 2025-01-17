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
    $nom = trim($_POST['nom']);
    $cognom = trim($_POST['cognom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validar camps buits
    if (empty($nom) || empty($cognom) || empty($email) || empty($password)) {
        set_message('register', 'Tots els camps son obligatoris.', 'error');
        header("Location: " . $redirect_url);
        exit();
    }

    // Validar nom i cognom
    $nomCognomPattern = "/^[a-zA-Z\-\_\']+$/";
    if (!preg_match($nomCognomPattern, $nom)) {
        set_message('nom', 'El format del nom no és vàlid.', 'error');
    }
    if (!preg_match($nomCognomPattern, $cognom)) {
        set_message('cognom', 'El format del cognom no és vàlid.', 'error');
    }

    // Validar el email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_message('email', "El format de l'email no es valid.", 'error');
    }

    // Validar la contrasenya (minim 8 caracters, una majuscula, una minuscula, un numero i un caracter especial)
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    if (!preg_match($passwordPattern, $password)) {
        set_message('password', 'La contrasenya ha de tenir almenys 8 caràcters, incloent-hi una majúscula, una minúscula, un número i un caràcter especial.', 'error');
    }

    // Comprovar si l'email ja existeix
    $check_email = "SELECT id FROM usuaris WHERE email = ?";
    $stmt_check = $db->prepare($check_email);
    if ($stmt_check) {
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        
        if ($result->num_rows > 0) {
            set_message('email', "Aquest email ja està registrat.", 'error');
        }
        $stmt_check->close();
    }

    if (!has_messages()) {
        // Encriptar contrasenya
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Preparar i executar la consulta SQL
        $sql = "INSERT INTO usuaris (nom, cognom, email, password, data) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $nom, $cognom, $email, $hashed_password);

            if ($stmt->execute()) {
                set_message('register', 'Usuari registrat correctament!', 'success');
                header("Location: " . $redirect_url);
                exit();
            } else {
                set_message('register', "Error al registrar l'usuari.", 'error');
                header("Location: " . $redirect_url);
                exit();
            }

            $stmt->close();
        } else {
            set_message('register', "Error al registrar l'usuari.", 'error');
            header("Location: " . $redirect_url);
            exit();
        }
    }

    // Tancar la conexió
    $db->close();
    header("Location: " . $redirect_url);
    exit();
}
?>

<?php
session_start();
include('../config/db.php');
include('../config/utility.php');

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validacio conexio bd
    if ($db->connect_error) {
        set_message('error', "S'ha produit un error al connectar a la base de dades.", 'error');
        header("Location: ../index.php");
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
        header("Location: ../index.php");
        exit();
    }

    // Validar nom i cognom
    $nomCognomPattern = "/^[a-zA-Z\-\_\']+$/";
    if (!preg_match($nomCognomPattern, $nom) || !preg_match($nomCognomPattern, $cognom)) {
        set_message('nomCognom', 'El format del nom o cognom no es valid.', 'error');
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
                header("Location: ../index.php");
                exit();
            } else {
                set_message('register', "Error al registrar l'usuari.", 'error');
                header("Location: ../index.php");
                exit();
            }

            $stmt->close();
        } else {
            set_message('register', "Error al registrar l'usuari.", 'error');
            header("Location: ../index.php");
            exit();
        }
    }

    // Tancar la conexio
    $db->close();
    header("Location: ../index.php");
    exit();
}
?>

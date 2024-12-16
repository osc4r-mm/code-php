<?php
session_start();
include('../config/db.php');
include('../includes/session.php');

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validacio conexio bd
    if ($db->connect_error) {
        set_error('error', "S'ha produit un error");
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
        set_error('error_register', 'Tots els camps son obligatoris.');
        header("Location: ../index.php");
        exit();
    }

    // Validar nom i cognom
    $nomCognomPattern = "/^[a-zA-Z\-\_\']+$/";
    if (!preg_match($nomCognomPattern, $nom) || !preg_match($nomCognomPattern, $cognom)) {
        set_error('invalid_nomCognom', 'El format del nom o cognom no es valid.');
    }

    // Validar el email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_error('invalid_email', "El format de l'email no es valid.");
    }

    // Validar la contrasenya (minim 8 caracters, una majuscula, una minuscula, un numero i un caracter especial)
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    if (!preg_match($passwordPattern, $password)) {
        set_error('invalid_password', 'La contrasenya ha de tenir almenys 8 caràcters, incloent-hi una majúscula, una minúscula, un número i un caràcter especial.');
    }
    if (empty($_SESSION['errors'])) {
        // Encriptar contrasenya
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Preparar i executar la consulta SQL
        $sql = "INSERT INTO usuaris (nom, cognom, email, password, data) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $nom, $cognom, $email, $hashed_password);

            if ($stmt->execute()) {
                set_message('success', 'Usuari registrat correctament!');
                header("Location: ../index.php");
                exit();
            } else {
                set_error('error_register', "Error al registrar l'usuari.");
                header("Location: ../index.php");
                exit();
            }

            $stmt->close();
        } else {
            set_error('error_register', "Error al registrar l'usuari.");
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

<?php
session_start();
include('../config/db.php');
include('../includes/session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consultar l'usuari per email
    $sql = "SELECT * FROM usuaris WHERE email = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verificar  contrasenya
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['usuari_id'] = $user['id'];
            $_SESSION['usuari_nom'] = $user['nom'];
            header("Location: ../index.php");
            exit;
        }
    }
    set_error('invalid_credentials', 'Credencials incorrectes');
    header("Location: ../index.php");
    exit;

}
?>

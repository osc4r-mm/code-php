<?php
session_start();
include('../config/db.php');
include('../config/utility.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener la página actual
    $current_page = isset($_POST['current_page']) ? $_POST['current_page'] : 'home';
    $redirect_url = "../index.php" . ($current_page != 'home' ? "?page=" . $current_page : "");

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
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
            $_SESSION['user']['usuari_id'] = $user['id'];
            $_SESSION['user']['nom'] = $user['nom'];
            header("Location: " . $redirect_url);
            exit;
        }
    }
    set_message('credentials', 'Credencials incorrectes', 'error');
    header("Location: " . $redirect_url);
    exit;

}
?>

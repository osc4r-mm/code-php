<?php
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $surname = filter_var($_POST['surname'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8');

    // Verificar camps
    if (!$name || !$surname || !$email || !$title || empty($description)) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Siusplau, completa tots los camps requerits del formulari.',
        ];
        header('Location: ../index.php?page=contact');
        exit;
    }

    // Crear una instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuració del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'oscar.martin.2112@lacetania.cat';
        $mail->Password = 'Zeroscar0305';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuració del correu
        $mail->setFrom($email, $name);
        $mail->addAddress('oscar.martin.2112@lacetania.cat', 'Oscar Receptor');

        $mail->isHTML(true);
        $mail->Subject = 'Nou missatge del formulari de contacte';
        $mail->Body = "
            <h2>Nou missatge del formulari de contacte</h2>
            <p><strong>Nom:</strong> $name</p>
            <p><strong>Cognoms:</strong> $surname</p>
            <p><strong>Correu electrònic:</strong> $email</p>
            <p><strong>Telèfon:</strong> $phone</p>
            <p><strong>Títol:</strong> $title</p>
            <p><strong>Missatge:</strong><br>$description</p>
        ";

        // Enviar correu
        $mail->send();
        
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Missatge enviat correctament.',
        ];
    } catch (Exception $e) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => "No s'ha pogut enviar. Error: {$mail->ErrorInfo}",
        ];
    }

    header('Location: ../index.php?page=contact');
    exit;
}
?>
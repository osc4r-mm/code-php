<?php
session_start();
require_once '../../config/config.php';
require_once '../functions.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    
    // Sanitize and validate inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (!$name || !$surname || !$email || !$title || !$description) {
        $errors[] = "Tots els camps obligatoris han de ser completats.";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El format del correu electrònic no és vàlid.";
    }

    if (empty($errors)) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
            $mail->CharSet = 'UTF-8';

            // Recipients
            $mail->setFrom(SMTP_USER, 'Contact Form');
            $mail->addAddress(ADMIN_EMAIL);
            $mail->addReplyTo($email, "$name $surname");

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Nou missatge de contacte: " . $title;
            
            $messageBody = "
            <h2>Nou missatge de contacte</h2>
            <p><strong>Nom:</strong> {$name} {$surname}</p>
            <p><strong>Correu electrònic:</strong> {$email}</p>
            <p><strong>Telèfon:</strong> {$phone}</p>
            <p><strong>Títol:</strong> {$title}</p>
            <p><strong>Missatge:</strong><br>" . nl2br($description) . "</p>";

            $mail->Body = $messageBody;
            $mail->AltBody = strip_tags(str_replace('<br>', "\n", $messageBody));

            $mail->send();
            set_message('success', 'El teu missatge s\'ha enviat correctament.');
            
        } catch (Exception $e) {
            error_log("Error sending email: " . $mail->ErrorInfo);
            set_message('errors', 'Hi ha hagut un problema en enviar el missatge. Si us plau, prova-ho més tard.');
        }
    } else {
        set_message('errors', implode('<br>', $errors));
    }
}

header('Location: ../../index.php?page=contact');
exit();

// config/config.php (add these constants)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'blog');
define('SMTP_PASS', 'Zeroscar0305');
define('SMTP_PORT', 587);
define('ADMIN_EMAIL', 'oscar.martin.2112@lacetania.cat');
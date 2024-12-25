<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to = "oscar.martin.2112@lacetania.cat";
    $subject = "Nou missatge des del formulari de contacte";

    // Recull i valida les dades
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']) ?: "No proporcionat";
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);

    // Format correu
    $message = "
    Nom: $name $surname
    Correu electrònic: $email
    Telèfon: $phone

    Títol: $title

    Missatge:
    $description
    ";

    // Enviar correu
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($to, $subject, $message, $headers)) {
        header("Location: ../index.php?page=contact&status=success");
    } else {
        header("Location: ../index.php?page=contact&status=error");
    }
    exit;
} else {
    header("Location: ../index.php");
    exit;
}

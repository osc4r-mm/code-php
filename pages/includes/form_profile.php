<?php
session_start();
require_once 'config/db.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user']['usuari_id'])) {
    header("Location: index.php");
    exit;
}

$usuari_id = $_SESSION['user']['usuari_id'];
$message = '';

// Obtiene los datos actuales del usuario
$sql = "SELECT nom, cognom, email FROM usuaris WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $usuari_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-profile-container">
        <h1>Editar Perfil</h1>
        <span class="error-message"><?php echo $messages['profile_edit']['content']; ?></span>
        <form action="actions/edit_profile_action.php" method="post">
            <div class="form-profile-group">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                <span class="error-message"><?php echo $messages['nom']['content']; ?></span>
            </div>
            <div class="form-profile-group">
                <label for="cognom">Cognom</label>
                <input type="text" name="cognom" id="cognom" value="<?php echo htmlspecialchars($user['cognom']); ?>" required>
                <span class="error-message"><?php echo $messages['cognom']['content']; ?></span>
            </div>
            <div class="form-profile-group">
                <label for="email">Correu electrònic</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <span class="error-message"><?php echo $messages['email']['content']; ?></span>
            </div>
            <div class="form-profile-actions">
                <button type="submit" class="btn">Actualitzar Perfil</button>
                <a href="index.php" class="btn-cancel">Cancel·lar</a>
            </div>
        </form>
    </div>
</body>
</html>
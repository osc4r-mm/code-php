<?php
session_start();
if (!isset($_SESSION['usuari_id'])) {
    header("Location: login.php");
    exit;
}
?>

<main class="form-container">
    <h2 class="form-title">Crea una nova categoria</h2>
    <!-- Formulari per crear una categoria -->
    <form class="form" action="actions/create_category_action.php" method="POST">
        <label class="form-label" for="nombre">Nom de la categoria:</label>
        <input class="form-input" type="text" name="nombre" id="nombre" required>

        <button class="form-button" type="submit">Crear Categoria</button>
    </form>
</main>

<?php
session_start();
if (!isset($_SESSION['usuari_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/db.php';

$post_id = isset($_GET['id']) ? $_GET['id'] : null;
$is_editing = $post_id !== null;
$post_data = [];

if ($is_editing) {
    // Obtener datos del post para rellenar el formulario
    $stmt = $db->prepare("SELECT * FROM entrades WHERE id = ? AND usuari_id = ?");
    $stmt->bind_param("ii", $post_id, $_SESSION['usuari_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $post_data = $result->fetch_assoc();

    if (!$post_data) {
        echo "Entrada no encontrada o no tienes permiso para editarla.";
        exit;
    }
}
?>

<main class="form-container">
    <h2 class="form-title">
        <?= $is_editing ? "Edita la teva entrada" : "Crea una nova entrada" ?>
    </h2>
    <!-- Formulari per crear o editar una entrada-->
    <form class="form" action="actions/post_action.php" method="POST">
        <?php if ($is_editing): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($post_data['id']) ?>">
        <?php endif; ?>

        <label class="form-label" for="titol">Títol:</label>
        <input class="form-input" type="text" name="titol" id="titol" value="<?= htmlspecialchars($post_data['titol'] ?? '') ?>" required>
        
        <label class="form-label" for="descripcio">Descripció:</label>
        <textarea class="form-textarea" name="descripcio" id="descripcio" rows="4" required><?= htmlspecialchars($post_data['descripcio'] ?? '') ?></textarea>
        
        <label class="form-label" for="categoria">Categoria:</label>
        <select class="form-select" name="categoria" id="categoria">
            <?php
            // Consulta les categories
            $sql = "SELECT * FROM categories";
            $result = mysqli_query($db, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $selected = $is_editing && $post_data['categoria_id'] == $row['id'] ? 'selected' : '';
                echo "<option value='".$row['id']."' $selected>".$row['nombre']."</option>";
            }
            ?>
        </select>
        
        <button class="form-button" type="submit">
            <?php echo $is_editing ? "Editar" : "Crear"; ?>
        </button>
    </form>
</main>

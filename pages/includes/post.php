<?php
// Incloure la connexió a la base de dades
include '../config/db.php';

// Verifica si l'ID del post està present
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $post_id = $_GET['id'];
    $current_user_id = $_SESSION['user']['usuari_id'];

    if ($db->connect_error) {
        die("La connexió ha fallat: " . $db->connect_error);
    }

    // Consulta per obtenir el post específic
    $sql = "
        SELECT e.*, u.nom AS autor_nom, u.cognom AS autor_cognom, c.nombre AS categoria_nom 
        FROM entrades e
        JOIN usuaris u ON e.usuari_id = u.id
        JOIN categories c ON e.categoria_id = c.id
        WHERE e.id = ?
    ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica si s'ha trobat el post
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        $post_date = date("d/m/Y H:i:s", strtotime($post['data']));
        
        echo "<div class='post-detail'>
                <h1 class='post-title'>".$post['titol']."</h1>
                <div class='post-meta'>
                    <p>Publicat per: <strong>".$post['autor_nom']." ".$post['autor_cognom']."</strong></p>
                    <p>El: ".$post_date."</p>
                    <p>Categoria: <a href='index.php?categoria=".$post['categoria_nom']."' class='post-category'>#".$post['categoria_nom']."</a></p>
                </div>";

        // Si l'usuari és el creador, mostra les opcions d'edició i eliminació
        if ($post['usuari_id'] == $current_user_id) {
            echo "<div class='post-actions'>
                    <a href='index.php?page=form_post&id=".$post['id']."' class='button-post'>Editar</a> 
                    <a href='actions/delete_post.php?id=".$post['id']."' class='button-post' data-id='".$post['id']."'onclick='return confirm(\"Estàs segur que vols esborrar aquest post?\");'>Esborrar</a>
                  </div>";
        }

        echo "<div class='post-divider'></div>
                <div class='post-content'>
                    <p>".nl2br($post['descripcio'])."</p>
                </div>
            </div>";
    } else {
        echo "<p>El post no s'ha trobat.</p>";
    }

    $stmt->close();
    $db->close();
} else {
    echo "<p>ID invàlid o no proporcionat.</p>";
}
?>
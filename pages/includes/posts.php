<section class="posts">
    <h1>Benvingut al Blog!</h1>
    <?php
    $current_user_id = $_SESSION['usuari_id'];

    if ($db->connect_error) {
        die("La connexió ha fallat: " . $db->connect_error);
    }

    if (isset($_GET['categoria'])) {
        $categoria_nombre = $_GET['categoria'];
        
        // Consulta per obtenir l'id de la categoria
        $categoria_sql = "SELECT id FROM categories WHERE nombre = ?";
        $stmt = $db->prepare($categoria_sql);
        $stmt->bind_param("s", $categoria_nombre); 
        $stmt->execute();
        $categoria_result = $stmt->get_result();

        if ($categoria_result->num_rows > 0) {
            $categoria = $categoria_result->fetch_assoc();
            $categoria_id = $categoria['id'];
            
            // Consulta els posts que corresponguin a l'id
            $sql = "
                SELECT e.*, u.nom AS autor_nom, u.cognom AS autor_cognom, c.nombre AS categoria_nom 
                FROM entrades e
                JOIN usuaris u ON e.usuari_id = u.id
                JOIN categories c ON e.categoria_id = c.id
                WHERE e.categoria_id = ? 
                ORDER BY e.data DESC
            ";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $categoria_id);
            $stmt->execute();
            $result = $stmt->get_result();
        }
    } else {
        // Consulta tots els posts ordenats per data de forma descendent
        $sql = "
            SELECT e.*, u.nom AS autor_nom, u.cognom AS autor_cognom, c.nombre AS categoria_nom 
            FROM entrades e
            JOIN usuaris u ON e.usuari_id = u.id
            JOIN categories c ON e.categoria_id = c.id
            ORDER BY e.data DESC
        ";
        $result = $db->query($sql);
    }
    
    // Comprova si hi ha resultats a la consulta
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $post_date = date("d/m/Y H:i:s", strtotime($row['data']));
            
            echo "<div class='post'>
                    <h2><a href='index.php?page=post&id=".$row['id']."'>".$row['titol']."</a></h2>
                    <p class='post-meta'>
                        Publicat per <strong>".$row['autor_nom']." ".$row['autor_cognom']."</strong> el ".$post_date."</a>.
                    </p>
                    <hr>
                    <p>".$row['descripcio']."</p>";

            // Si l'usuari es el creador dels posts, deixa editar o eliminar-los
            if ($row['usuari_id'] == $current_user_id) {
                echo "<div class='post-actions'>
                        <a href='index.php?page=form_post&id=".$row['id']."' class='button-post'>Editar</a> 
                        <a href='actions/delete_post.php?id=".$row['id']."' class='button-post' data-id='".$row['id']."'>Esborrar</a>
                      </div>";
            }

            echo "<div class='categoria-tag'>
                      <a href='index.php?categoria=".$row['categoria_nom']."'>#".$row['categoria_nom']."</a>
                  </div>
                </div>";
        }
    } else {
        // Si no hi ha entrades
        echo "<p>No hi ha entrades.</p>";
    }

    $db->close();
    ?>
</section>

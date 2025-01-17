<section class="posts">
    <?php
    $current_user_id = $_SESSION['user']['usuari_id'];
    $current_category = isset($_GET['categoria']) ? $_GET['categoria'] : "";
    $posts_per_page = 5;
    $current_page = isset($_GET['pagination']) && is_numeric($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
    $offset = ($current_page - 1) * $posts_per_page;

    if ($db->connect_error) {
        die("La connexió ha fallat: " . $db->connect_error);
    }

    if (isset($_GET['categoria'])) {
        $categoria_nombre = $_GET['categoria'];
        
        // Mostra el títol de la categoria
        echo "<h1>Posts de la categoria: " . htmlspecialchars($categoria_nombre) . "</h1>";

        // Consulta per obtenir l'id de la categoria
        $categoria_sql = "SELECT id FROM categories WHERE nombre = ?";
        $stmt = $db->prepare($categoria_sql);
        $stmt->bind_param("s", $categoria_nombre); 
        $stmt->execute();
        $categoria_result = $stmt->get_result();

        if ($categoria_result->num_rows > 0) {
            $categoria = $categoria_result->fetch_assoc();
            $categoria_id = $categoria['id'];

            // Total de posts a la categoria
            $count_sql = "SELECT COUNT(*) AS total FROM entrades WHERE categoria_id = ?";
            $stmt = $db->prepare($count_sql);
            $stmt->bind_param("i", $categoria_id);
            $stmt->execute();
            $count_result = $stmt->get_result();
            $total_posts = $count_result->fetch_assoc()['total'];
            
            // Consulta els posts que corresponguin a l'id
            $sql = "
                SELECT e.*, u.nom AS autor_nom, u.cognom AS autor_cognom, c.nombre AS categoria_nom 
                FROM entrades e
                JOIN usuaris u ON e.usuari_id = u.id
                JOIN categories c ON e.categoria_id = c.id
                WHERE e.categoria_id = ? 
                ORDER BY e.data DESC
                LIMIT ? OFFSET ?
            ";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("iii", $categoria_id, $posts_per_page, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
        }
    } else {
        echo "<h1>Benvingut al Blog!</h1>";

        // Total de posts
        $count_sql = "SELECT COUNT(*) AS total FROM entrades";
        $count_result = $db->query($count_sql);
        $total_posts = $count_result->fetch_assoc()['total'];

        // Consulta tots els posts ordenats per data de forma descendent
        $sql = "
            SELECT e.*, u.nom AS autor_nom, u.cognom AS autor_cognom, c.nombre AS categoria_nom 
            FROM entrades e
            JOIN usuaris u ON e.usuari_id = u.id
            JOIN categories c ON e.categoria_id = c.id
            ORDER BY e.data DESC
            LIMIT $posts_per_page OFFSET $offset
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
            if ($current_user_id && $row['usuari_id'] == $current_user_id) {
                echo "<div class='post-actions'>
                        <a href='index.php?page=form_post&id=".$row['id']."' class='button-post'>Editar</a> 
                        <a href='actions/delete_post.php?id=".$row['id']."' class='button-post' data-id='".$row['id']."'onclick='return confirm(\"Estàs segur que vols esborrar aquest post?\");'>Esborrar</a>
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

    // Calcula el nombre total de pàgines
    $total_pages = ceil($total_posts / $posts_per_page);

    // Genera la paginació
    echo "<div class='pagination'>";

    // Mostra la fletxa per anar enrere
    if ($current_page > 1) {
        echo "<a href='index.php?pagination=".($current_page - 1)."&categoria=".($current_category)."' class='pagination-arrow'>&lt;--</a>";
    }

    // Mostra el número 1 i punts suspensius si és necessari
    if ($current_page > 2) {
        echo "<a href='index.php?pagination=1&categoria=$current_category'>1</a>";
    }
    if ($current_page > 3) {
        echo "<span class='dots'>...</span>";
    }

    // Mostra els números al voltant de la pàgina actual
    $start = max(1, $current_page - 1);
    $end = min($total_pages, $current_page + 1);

    for ($i = $start; $i <= $end; $i++) {
        if ($i == $current_page) {
            echo "<span class='current-page'>$i</span>";
        } else {
            echo "<a href='index.php?pagination=$i&categoria=$current_category'>$i</a>";
        }
    }

    // Mostra punts suspensius i l'última pàgina si és necessari
    if ($current_page < $total_pages - 2) {
        echo "<span class='dots'>...</span>";
    }
    if ($current_page < $total_pages - 1) {
        echo "<a href='index.php?pagination=$total_pages&categoria=$current_category'>$total_pages</a>";
    }

    // Mostra la fletxa per anar endavant
    if ($current_page < $total_pages) {
        echo "<a href='index.php?pagination=".($current_page + 1)."&categoria=$current_category' class='pagination-arrow'>--&gt;</a>";
    }

    // Form per saltar directament a una pàgina
    echo "<form method='GET' class='pagination-form'>";
    echo "<input type='number' name='pagination' min='1' max='$total_pages' placeholder='Pàgina'>";
    echo "<button type='submit'>Anar</button>";
    echo "</form>";

    echo "</div>";

    $db->close();
    ?>
</section>

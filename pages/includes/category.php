<?php
require_once 'config/db.php';

// Consulta para obtener todas las categorías y contar sus posts
$sql = "
    SELECT c.id, c.nombre, COUNT(e.id) as post_count
    FROM categories c
    LEFT JOIN entrades e ON c.id = e.categoria_id
    GROUP BY c.id, c.nombre
    ORDER BY c.nombre ASC
";

$result = mysqli_query($db, $sql);
$categories = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}
?>

<div class="categories-container">
    <h1>Categories del Blog</h1>
    
    <div class="categories-grid">
        <?php foreach ($categories as $category): ?>
            <div class="category-card">
                <a href="index.php?categoria=<?php echo urlencode($category['nombre']); ?>">
                    <div class="category-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <h2 class="category-name"><?php echo htmlspecialchars($category['nombre']); ?></h2>
                    <span class="post-count"><?php echo $category['post_count']; ?> posts</span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
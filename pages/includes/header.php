<?php
include('config/db.php');

// Consulta per obtenir les 5 categories amb mes posts
$sql = "
    SELECT cat.nombre, COUNT(entry.id) AS post_count
    FROM categories cat
    LEFT JOIN entrades entry ON cat.id = entry.categoria_id
    GROUP BY cat.id
    ORDER BY post_count DESC
    LIMIT 5
";
$result = mysqli_query($db, $sql);

$categories = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/f370f9c555.js" crossorigin="anonymous"></script>
</head>
<body>
<header>
        <nav>
        <span class="menu-btn" onclick="toggleMenu()">&#9776;</span>

        <ul id="menu">
            <li><a href="index.php">Inici</a></li>

            <!-- Mostrems les 5 categorías -->
            <?php
            foreach ($categories as $category) {
                echo "<li><a href='index.php?categoria=".$category['nombre']."'>".$category['nombre']."</a></li>";
            }
            ?>
            <li><a href="index.php?page=categories"><i class="fas fa-tags"></i> Categories</a></li>
        </ul>
        </nav>
        <script>
            function toggleMenu() {
                var menu = document.getElementById('menu');
                menu.classList.toggle('show');
            }
        </script>
    </header>

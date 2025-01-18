<div class="search-container">
    <form action="index.php" method="GET" class="search-form">
        <input type="text" 
               name="search" 
               placeholder="Buscar posts..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
               required>
        <button type="submit">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>
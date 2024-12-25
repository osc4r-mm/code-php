<aside class="sidebar">
    <?php if(!isset($_SESSION['usuari_id'])): ?>
        <div class="forms">
            <div class="content">
                <?php 
                include('includes/login.php'); 
                ?>
            </div>

            <div class="content">
                <?php 
                include('includes/register.php');
                ?>
            </div>
        </div>
    <?php else: ?>
    <div class="profile">
        <div class="profile-container">
            <img src="img/profile_img.png" alt="Profile Avatar" class="profile-avatar">
            <a href="index.php?page=form_profile" class="edit-icon">
                <i class="fas fa-pencil-alt"></i>
            </a>
        </div>
        
        <?php if (!empty($_SESSION['message'])): ?>
            <p class="success-message profile-text"><?php echo htmlspecialchars($_SESSION['message']); ?></p>
            <?php unset($_SESSION['message']);?>
        <?php endif; ?>

        <p class="profile-text">Hola, <?php echo htmlspecialchars($_SESSION['usuari_nom']); ?>!</p>

        <ul class="profile-options">
            <li><a href="index.php?page=form_post"><i class="fas fa-plus"></i> Crear Entrada</a></li>
            <li><a href="index.php?page=category"><i class="fas fa-plus"></i> Crear Categoria</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Tancar Sessió</a></li>
        </ul>
    </div>
    <?php endif; ?>
</aside>
<aside class="sidebar">
    <?php include('includes/search.php'); ?>
    <?php if(!isset($_SESSION['user']['usuari_id'])): ?>
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
        
        <p class="<?php echo $messages['profile']['type']; ?>-message profile-text"><?php echo $messages['profile']['content']; ?></p>

        <p class="profile-text">Hola, <?php echo htmlspecialchars($_SESSION['user']['nom']); ?>!</p>

        <ul class="profile-options">
            <li><a href="index.php?page=form_post"><i class="fas fa-plus"></i> Crear Entrada</a></li>
            <li><a href="index.php?page=category"><i class="fas fa-plus"></i> Crear Categoria</a></li>
            <li><a href="actions/logout_action.php"><i class="fas fa-sign-out-alt"></i> Tancar Sessió</a></li>
        </ul>
    </div>
    <?php endif; ?>
</aside>
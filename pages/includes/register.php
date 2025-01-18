<div class="register">
    <form class="register-form" action="actions/register_action.php" method="POST">
    <input type="hidden" name="current_page" value="<?php echo isset($_GET['page']) ? $_GET['page'] : 'home'; ?>">
        <span class="<?php echo $messages['register']['type']; ?>-message"><?php echo $messages['register']['content'] ?></span>
    
        <h2>Registrat</h2>
        <label for="nom">Nom:</label>
        <input type="text" name="nom" id="nom" required>
        <span class="error-message"><?php echo $messages['nom']['content'] ?? ''; ?></span>
        
        <label for="cognom">Cognom:</label>
        <input type="text" name="cognom" id="cognom" required>
        <span class="error-message"><?php echo $messages['cognom']['content'] ?? ''; ?></span>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <span class="error-message"><?php echo $messages['email']['content'] ?? ''; ?></span>
        
        <label for="password">Contrasenya:</label>
        <input type="password" name="password" id="password" required>
        <span class="error-message"><?php echo $messages['password']['content'] ?? ''; ?></span>
        <br>
        
        <button type="submit">Registrar-se</button>
    </form>
</div>

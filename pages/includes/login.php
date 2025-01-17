<div class="login">
    <form class="login-form" action="actions/login_action.php" method="POST">
        <input type="hidden" name="current_page" value="<?php echo isset($_GET['page']) ? $_GET['page'] : 'home'; ?>">

        <h2>Iniciar Sessió</h2>
        <span class="error-message"><?php echo $messages['credentials']['content']; ?></span>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="password">Contrasenya:</label>
        <input type="password" name="password" id="password" required>
        
        <button type="submit">Entrar</button>
    </form>
</div>
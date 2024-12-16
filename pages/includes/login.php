<div class="login">
    <span class="error-message"><?php echo $errors['invalid_credentials'] ?? ''; ?></span>

    <form class="login-form" action="actions/login_action.php" method="POST">
        <h2>Iniciar Sessió</h2>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="password">Contrasenya:</label>
        <input type="password" name="password" id="password" required>
        
        <button type="submit">Entrar</button>
    </form>
</div>
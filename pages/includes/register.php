<div class="register">
    <form class="register-form" action="actions/register_action.php" method="POST">
        <span class="<?php echo $message['type'] ?>-message"><?php echo $message['register'] ?? ''; ?></span>
    
        <h2>Registrat</h2>
        <label for="nom">Nom:</label>
        <input type="text" name="nom" id="nom" >
        <span class="error-message"><?php echo $message['nomCognom'] ?? ''; ?></span>
        
        <label for="cognom">Cognom:</label>
        <input type="text" name="cognom" id="cognom">
        <span class="error-message"><?php echo $message['nomCognom'] ?? ''; ?></span>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email">
        <span class="error-message"><?php echo $message['email'] ?? ''; ?></span>
        
        <label for="password">Contrasenya:</label>
        <input type="password" name="password" id="password">
        <span class="error-message"><?php echo $message['password'] ?? ''; ?></span>
        <br>
        
        <button type="submit">Registrar-se</button>
    </form>
</div>

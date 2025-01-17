<div class="contact">
    <h1>Contacta'ns</h1>
    <p>Si tens preguntes, suggeriments o qualsevol consulta, no dubtis a posar-te en contacte amb nosaltres mitjançant aquest formulari.</p>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="success-message">El teu missatge s'ha enviat correctament.</div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
        <div class="error-message">Hi ha hagut un problema en enviar el missatge. Si us plau, prova-ho més tard.</div>
    <?php endif; ?>

    <form action="actions/contact_action.php" method="POST" class="contact-form">
        <label for="name">Nom *</label>
        <input type="text" id="name" name="name" required>

        <label for="surname">Cognoms *</label>
        <input type="text" id="surname" name="surname" required>

        <label for="email">Correu electrònic *</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Telèfon (opcional)</label>
        <input type="tel" id="phone" name="phone" placeholder="123-456-789">

        <label for="title">Títol *</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Missatge *</label>
        <textarea id="description" name="description" rows="5" required></textarea>

        <button type="submit">Enviar</button>
    </form>
</div>
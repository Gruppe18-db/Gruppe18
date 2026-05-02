    <?php
    include 'includes/db.inc.php';
    ?>

    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);


    ?>

<!-- Oleksandra Ishmatova -->

    <h1>Startseite</h1>

<h2>Login Rennveranstalter</h2>
<form method="post" action="login_rennveranstalter.php">
    <label>Name:</label><br>
    <input type="text" name="NameRV" required><br><br>
    
    <label>Passwort:</label><br>
    <input type="password" name="Kennwort" required><br><br>
    
    <button type="submit">Anmelden</button>
</form>

<form method="get" action="register_rennveranstalter.php">
    <button type="submit">Registrieren</button>
</form>

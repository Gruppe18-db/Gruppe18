    <?php
    include 'db.php';
    ?>

    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once "query.php";  // Funktionen laden
    ?>



    <h1>Startseite</h1>

<h2>Login Rennveranstalter</h2>
<form method="post" action="login.php">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>
    
    <label>Passwort:</label><br>
    <input type="password" name="password" required><br><br>
    
    <button type="submit">Anmelden</button>
</form>


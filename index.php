    <?php
    include 'db.php';
    ?>

    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once "query.php";  // Funktionen laden 
    ?>

<!-- Oleksandra Ishmatova -->

    <h1>Startseite</h1>

<h2>Login Rennveranstalter</h2>
<form method="post" action="login_rennveranstalter.php">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>
    
    <label>Passwort:</label><br>
    <input type="password" name="password" required><br><br>
    
    <button type="submit">Anmelden</button>
</form>

<form method="get" action="register_rennveranstalter.php">
    <button type="submit">Registrieren</button>
</form>

<!-- Dilara Öztürk -->
<?php
session_start();

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}

if(isset($_GET['login'])) {
    $LoginName = $_POST['LoginName'];
    $passwort = $_POST['passwort'];
    
    $stmt->execute(array('LoginName' => $LoginName, 'passwort' => $passwort));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user !== false) {
        header("Location: Teamchef.php");
        exit();
    } else {
        $errorMessage = "LoginName oder Passwort war ungültig.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teamchef Login</title>
</head>
<body>

<?php
if(isset($errorMessage)) {
    echo $errorMessage;
}
?>
<form action="?login=true" method="post">
   
    <label for="LoginName">LoginName:</label>
    <input type="text" size="10" maxlength="100" name="LoginName" required><br>

    <label for="Passwort">Passwort:</label>
    <input type="password" id="passwort" size="10" maxlength="100" name="passwort" required><br>

    <input type="submit" value="Anmelden">
</form>
</body>
</html>
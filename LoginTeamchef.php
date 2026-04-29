// Autor: Dilara Öztürk
<?php
session_start();
$pdo = new PDO('mysql:host=dbsnk.kirchbergnet.de;dbname=gruppe18', 'gruppe18', 'p{DxGCnEX@s,');

if(isset($_GET['login'])) {
    $LoginName = $_POST['Loginname'];
    $kennwort = $_POST['Kennwort'];
    $stmt = $pdo->prepare("
    SELECT tc.LoginName, tc.Kennwort, tc.Vorname, tc.Nachname, t.Teamname
    FROM teamchef tc, team t
    WHERE tc.LoginName = t.LoginName AND 
    tc.LoginName = ?"
    );

    $stmt->execute(array('LoginName' => $LoginName, 'kennwort' => $kennwort));
    $teamchef = $stmt->fetch();

    if($teamchef !== false && password_verify($kennwort, $teamchef['Kennwort'])) {
        
        $_SESSION['LoginName'] = $teamchef['LoginName'];
        die('Login erfolgreich! Weiter zu <a href="FahrerVerwalten.php"> Fahrer verwalten</a>');
        $_SESSION['Vorname'] = $teamchef['Vorname'];
        $_SESSION['Nachname'] = $teamchef['Nachname'];
        $_SESSION['Teamname'] = $teamchef['Teamname'];

    } else {
        $errorMessage = "Loginname oder Passwort war ungültig. Bitte überprüfen Sie Ihre Eingaben.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Teamchef</title>
</head>
<body>

<h1>Login Teamchef</h1>
<p><a href="index.html">Zurück zur Startseite</a></p>

<?php if(isset($errorMessage)) { 
    echo $errorMessage;
    } ?>

<form action="?login=true" method="post">
    Loginname: <input type="text" name="loginname" required><br><br>
    Kennwort: <input type="password" name="kennwort" required><br><br>
    <button type="submit">Anmelden</button>
</form>
<?php if (!empty($errorMessage)) 
    echo "<p style='color:red;'>".htmlspecialchars($errorMessage)."</p>"; ?>
</body>
</html>
<?php 
ini_set('display_errors', 1); error_reporting(E_ALL);
// Autor: Dilara Öztürk
session_start();
include 'includes/db.inc.php';

if (!empty($_POST['LoginnameTC']) && !empty($_POST['Kennwort'])) { // Kontrolle ob ie Felder ausgefüllt sind
    // Die eingeegebenen Daten werden gespeichert
    $LoginnameTC = $_POST['LoginnameTC'];
    $Kennwort = $_POST['Kennwort'];
    // Datenbankabfrage zur Kontrolle der Logindaten
    $stmt = $pdo->prepare("SELECT tc.Loginname, 
    tc.Kennwort,
    t.TeamName
    FROM Teamchef tc, Team t 
    WHERE tc.Loginname = t.LoginnameTC AND tc.Loginname = :Loginname" // :Loginname als Platzhalter zum Schutz vor SQL Injection
    );
    
    $stmt->execute(['Loginname' => $LoginnameTC]); // Der Platzhalter :Loginname wird mit dem eingegebenen Loginnamen ersetzt
    $teamchef = $stmt->fetch();

    if($teamchef && password_verify($Kennwort, $teamchef['Kennwort'])) { // Das Passwort wird geprüft
        
        $_SESSION['teamchef_logged_in'] = true;
        $_SESSION['LoginnameTC'] = $teamchef['Loginname'];
        $_SESSION['TeamName'] = $teamchef['TeamName'];

        header("Location: ManageCyclist.php");
        exit;
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

<form method="post">
    Loginname: <input type="text" name="LoginnameTC" required><br><br>
    Kennwort: <input type="password" name="Kennwort" required><br><br>
    <button type="submit">Anmelden</button>
</form>
<?php if (!empty($errorMessage)) 
    echo "<p style='color:red;'>".htmlspecialchars($errorMessage)."</p>"; ?>

<p><a href="index.php">Zurück zur Startseite</a></p>

</body>
</html>

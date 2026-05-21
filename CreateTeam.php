<?php
// Autor: Dilara Öztürk
include 'includes/db.inc.php';
include 'includes/Functions.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Hier werden die Eingaben in dem Formular mit dem Befehl "POST" abgeschickt
    
    // Diese Eingaben werden aus dem Formular geholt und in der Datenbank gespeichert
                                            
    $TeamName  = trim($_POST['TeamName']); 
    $VornameTC   = trim($_POST['VornameTC']);
    $NachnameTC  = trim($_POST['NachnameTC']);
    $LoginnameTC = trim($_POST['LoginnameTC']);
    $Kennwort  = $_POST['Kennwort'];
                                            
    if (teamExists($pdo, $TeamName)) {
        $error = "Dieses Team existiert bereits.";
        
    } else {
        // Team und Teamchef werden anlgelegt, die Funktionen stehen in der Functions.inc.php Datei
        createTeam($pdo, $TeamName, $LoginnameTC);
        createTeamchef($pdo, $LoginnameTC, $Kennwort, $VornameTC, $NachnameTC, $TeamName);

        $message = "Team und Teamchef wurden erfolgreich angelegt.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Neues Team anlegen</title>
</head>
<body>  
<h1>Neues Team anlegen</h1>
    
<form method="post">
    Teamname: <input name="TeamName" required><br><br>
    Vorname Teamchef: <input name="VornameTC" required><br><br>
    Nachname Teamchef: <input name="NachnameTC" required><br><br>
    Loginname Teamchef: <input name="LoginnameTC" required><br><br>
    Kennwort Teamchef: <input type="password" name="Kennwort" required><br><br>
    <button type="submit">Anlegen</button>
</form>

<p><a href="index.php">Zurück zur Startseite</a></p>

<?php 
if (!empty($message)) echo "<p style='color:green;'>".htmlspecialchars($message)."</p>"; 
?>

</body>
</html>

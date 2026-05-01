<?php 
ini_set('display_errors', 1); error_reporting(E_ALL);
// Autor: Dilara Öztürk
session_start();
require_once __DIR__ . '/includes/db.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $Loginname = $_POST['Loginname'];
    $Kennwort = $_POST['Kennwort'];

    $stmt = $pdo->prepare("SELECT tc.Loginname, 
    tc.Kennwort, 
    tc.VornameTC, 
    tc.NachnameTC, 
    tc.TeamName,
    t.TeamName
    FROM Teamchef tc, Team t 
    WHERE tc.Loginname = t.LoginnameTC AND tc.Loginname = ?"
    );

    $stmt->execute([$Loginname]);
    $teamchef = $stmt->fetch();

    if($teamchef && password_verify($Kennwort, $teamchef['Kennwort'])) {
        
        $_SESSION['teamchef_logged_in'] = true;
        
        $_SESSION['Loginname'] = $teamchef['Loginname'];
        $_SESSION['VornameTC'] = $teamchef['VornameTC'];
        $_SESSION['NachnameTC'] = $teamchef['NachnameTC'];
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
    Loginname: <input type="text" name="Loginname" required><br><br>
    Kennwort: <input type="password" name="Kennwort" required><br><br>
    <button type="submit">Anmelden</button>
</form>
<?php if (!empty($errorMessage)) 
    echo "<p style='color:red;'>".htmlspecialchars($errorMessage)."</p>"; ?>

<p><a href="index.html">Zurück zur Startseite</a></p>

</body>
</html>
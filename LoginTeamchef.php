// Dilara Öztürk
<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=football', 'root', '');

if(isset($_GET['login'])) {
    $LoginName = $_POST['LoginName'];
    $passwort = $_POST['passwort'];
    $stmt = $pdo->prepare("SELECT * FROM teamchef WHERE LoginName = :LoginName AND passwort = :passwort");
    $stmt->execute(array('LoginName' => $LoginName, 'passwort' => $passwort));
    $user = $stmt->fetch();

    if($user !== false && password_verify($passwort, $user['passwort'])) {
        $_SESSION['LoginName'] = $user['LoginName'];
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
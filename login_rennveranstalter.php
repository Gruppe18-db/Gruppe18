<!-- Oleksandra Ishmatova -->

<!-- Ermöglicht Rennveranstaltern das Anmelden mit Session-Prüfung und Passwort-Hashing -->

<?php
session_start();
include 'includes/db.inc.php';
include 'includes/functions.inc.php';

    if (!empty($_POST['NameRV']) && !empty($_POST['Kennwort'])) {

    $NameRV = trim($_POST['NameRV']);
    $Kennwort = $_POST['Kennwort'];

    $user = loginRennveranstalter($pdo, $NameRV, $Kennwort);

    if ($user) {
        $_SESSION['NameRV'] = $NameRV;
        header("Location: dashboard_rennveranstalter.php");
        exit;
    } else {
        $error = "Ungültiger Name oder Passwort!";
    }
}
    
?>

<?php if (!empty($error)) : ?>
    <p><?php echo $error; ?></p>

    <a href="index.php">
        <button type="button">Zurück zur Startseite</button>
    </a>

<?php endif; ?>







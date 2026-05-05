<!-- Oleksandra Ishmatova -->

<?php
session_start();
include 'includes/db.inc.php';

    if (!empty($_POST['NameRV']) && !empty($_POST['Kennwort'])) {

    $NameRV = trim($_POST['NameRV']);
    $Kennwort = $_POST['Kennwort'];

    $statement = $pdo->prepare("SELECT NameRV, Kennwort FROM Rennveranstalter WHERE NameRV = :name"); 

    $statement->execute(['name' => $NameRV]);

    $user = $statement->fetch();

    if ($user && password_verify($Kennwort, $user['Kennwort'])) {
        $_SESSION['NameRV'] = $NameRV;

        header("Location: dashboard_rennveranstalter.php");
        exit;
    } else {
        $error = "Ungültige Daten!";

    }
}
    
?>

<?php if (!empty($error)) : ?>
    <p><?php echo $error; ?></p>

    <a href="index.php">
        <button type="button">Zurück zur Startseite</button>
    </a>

<?php endif; ?>







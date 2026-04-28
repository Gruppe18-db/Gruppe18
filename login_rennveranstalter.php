<!-- Oleksandra Ishmatova -->

<?php
session_start();
require_once "db.php";
?>


<?php

    if (!empty($_POST['NameRV']) && !empty($_POST['Kennwort'])) {

    $NameRV = $_POST['NameRV'];
    $Kennwort = $_POST['Kennwort'];

    $query = "SELECT * FROM Rennveranstalter WHERE NameRV = '$NameRV' AND Kennwort = '$Kennwort'";

    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['NameRV'] = $NameRV;

        header("Location: dashboard_rennveranstalter.php");
        exit;
    } else {
        echo "Ungültige Daten!";
    }
}
    
?>

<h2>Anmeldung Rennveranstalter</h2>

<form method="post">
    <input type="text" name="NameRV" placeholder="Name" required><br><br>
    <input type="password" name="Kennwort" placeholder="Passwort" required><br><br>

<button type="submit">Anmelden</button>

</form>




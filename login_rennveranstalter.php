<h2>Anmelden Rennveranstalter</h2>

<form method="post">
    <input type="text" name="NameRV" placeholder="Name" required><br><br>
    <input type="password" name="Kennwort" placeholder="Passwort" required><br><br>

<button type="submit">Anmelden</button>

</form>

<?php
require_once "db.php";

    if (!empty($_POST['NameRV']) && !empty($_POST['Kennwort'])) {

    $NameRV = $_POST['NameRV'];
    $Kennwort = $_POST['Kennwort'];

    $query = "SELECT * FROM Rennveranstalter WHERE NameRV = '$NameRV' AND Kennwort = '$Kennwort'";

    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 1) {
        echo "Login erfolgreich!";
    } else {
        echo "Ungültige Daten!";
    }
}
    
    
?>


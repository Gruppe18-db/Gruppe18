<h2>Registrierung Rennveranstalter</h2>

<form method="post">
    <input type="text" name="NameRV" placeholder="Name" required><br><br>
    <input type="password" name="Kennwort" placeholder="Passwort" required><br><br>

<button type="submit">Registrieren</button>

</form>

<?php
require_once "db.php";

    if (!empty($_POST['NameRV']) && !empty($_POST['Kennwort'])) {

    $NameRV = $_POST['NameRV'];
    $Kennwort = $_POST['Kennwort'];

    $query = "INSERT INTO Rennveranstalter (NameRV, Kennwort) VALUES ('$NameRV', '$Kennwort')";

    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "Registrierung erfolgreich!";
    } else {
        echo "Fehler: " . mysqli_error($connection);
    }
}
    
    


?>





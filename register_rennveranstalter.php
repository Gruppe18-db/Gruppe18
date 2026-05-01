<!-- Oleksandra Ishmatova -->

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

    $check = "SELECT * FROM Rennveranstalter WHERE NameRV = '$NameRV' ";
    $check_result = mysqli_query($connection, $check);

    if (mysqli_num_rows($check_result) > 0) {
        echo "Name bereits vergeben!";

    } else {

        $query = "INSERT INTO Rennveranstalter (NameRV, Kennwort) VALUES ('$NameRV', '$Kennwort')";

        if (mysqli_query($connection, $query)) {
            echo "Registrierung erfolgreich!";
            echo '<a href="index.php"><button>Zurück zur Startseite</button></a>';
            
         } else {
            echo "Fehler: " . mysqli_error($connection);
        }
    }
    }


?>





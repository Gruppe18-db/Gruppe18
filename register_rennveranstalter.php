<!-- Oleksandra Ishmatova -->

<!-- Ermöglicht die Registrierung neuer Rennveranstalter inkl. Passwort-Hashing, Validierung und Prüfung auf doppelte benutzernamen -->

<h2>Registrierung Rennveranstalter</h2>

<form method="post">
    <input type="text" name="NameRV" placeholder="Name" required><br><br>
    <input type="password" name="Kennwort" placeholder="Passwort" required><br><br>

<button type="submit">Registrieren</button>

</form>

<?php
include 'includes/db.inc.php';

    if (!empty($_POST['NameRV']) && !empty($_POST['Kennwort'])) {

        $NameRV = trim($_POST['NameRV']);
        $Kennwort = $_POST['Kennwort'];

        if (strlen($Kennwort) < 6) {
            echo "Passwort muss mindestens 6 Zeichen lang sein";
            return;
        }

        $hash = password_hash($Kennwort, PASSWORD_DEFAULT);

        $statement = $pdo->prepare("SELECT * FROM Rennveranstalter WHERE NameRV = :name"); //Keine SQL-Injection, da nur Name überprüft wird
        $statement->execute([ ':name' => $NameRV]);

        if ($statement->fetch()) {
            echo "Name bereits vergeben!";

        } else {

            $statement = $pdo->prepare("INSERT INTO Rennveranstalter (NameRV, Kennwort) VALUES (:name, :password)");
        
            if ($statement->execute([
                ':name' => $NameRV,
                ':password' => $hash
            ])) {
                echo "Registrierung erfolgreich!";
                echo '<a href="index.php"><button>Zurück zur Startseite</button></a>';
                
            } else {
                echo "Fehler beim Speichern ";
            }
        }
}


?>





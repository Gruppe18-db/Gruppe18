<!-- Oleksandra Ishmatova -->

<!-- Ermöglicht Rennveranstaltern das Erstellen neuer Rennen inkl. Session-Prüfung und Eingabevalidierung -->

<?php
session_start();
include 'includes/db.inc.php';

if (!isset($_SESSION['NameRV'])) {
    header("Location: index.php");
    exit;
}

$NameRV = $_SESSION['NameRV'];
$meldung = "";

if (!empty($_POST['Datum'])) {

    $Datum = $_POST['Datum'];
    $Startort = trim($_POST['Startort']); // führende und nachgestellte Leerzeichen entfernen
    $Km =  (int) $_POST['Km'];
    $Hoehenmeter = (int) $_POST['Hoehenmeter'];
    $Steigung = (int) $_POST['Steigung'];

    if (empty($Startort)) {
        $meldung = "Startort darf nicht leer sein!";
        return;
    }

    if ($Km <= 0 || $Hoehenmeter < 0 || $Steigung < 0 || $Steigung > 100) {
        $meldung = "Kilometer, Höhenmeter und Steigung müssen positive Werte sein!";
        return;
    }

    try {
    $statement = $pdo->prepare( "INSERT INTO Rennen 
    (Datum, Startort, AnzahlGefahreneKilometer, Hoehenmeter, MaxSteigung, NameRV)
    VALUES 
    (:datum, :startort, :km, :hoehenmeter, :steigung, :namerv)");

     $statement->execute([ // Eingaben sind nur Werte, nicht Teil der SQL-Anweisung, daher keine SQL-Injection möglich
        ':datum' => $Datum,
        ':startort' => $Startort,
        ':km' => $Km,
        ':hoehenmeter' => $Hoehenmeter,
        ':steigung' => $Steigung,
        ':namerv' => $NameRV
    ]);

        $meldung = "Rennen erfolgreich erstellt!";
    } catch (PDOException $e)  {
        $meldung = "Fehler beim Speichern ";
    }
}
?>

<h1>Dashboard Rennveranstalter</h1>
<p>Willkommen <?php echo $NameRV; ?>!</p>

<h2>Rennen erstellen</h2>

<?php if (!empty($meldung)) { 
    echo "<p>$meldung</p>";
} 
?>

<form method="post">
    <input type="date" name="Datum" required><br><br>
    <input type="text" name="Startort" placeholder="Startort" required><br><br>
    <input type="number" name="Km" placeholder="Kilometer" required><br><br>
    <input type="number" name="Hoehenmeter" placeholder="Höhenmeter" required><br><br>
    <input type="number" name="Steigung" placeholder="Max. Steigung (in %)" required><br><br>

<button type="submit">Rennen erstellen</button>

</form>

<br><br>
<hr>

<form action="ergebnisse_erfassen.php" method="get">
    <button type="submit">Ergebnisse der Rennen erfassen</button>
</form>


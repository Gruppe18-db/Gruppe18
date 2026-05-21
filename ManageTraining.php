<?php
// Autor: Dilara Öztürk
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'includes/db.inc.php';

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION['teamchef_logged_in'])) {
    header("Location: LoginTeamchef.php");
    exit;
}

$TeamNameSession = $_SESSION['TeamName'];
$message = '';
$success = '';

try { // Trigger für den Fall, dass für einen Tag bereits ein Training angelegt wurde, da immer nur ein Training pro Tag gespeichert werden darf
    $pdo->exec("DROP TRIGGER IF EXISTS trg_training_no_double_day");
    $pdo->exec("
        CREATE TRIGGER trg_training_no_double_day
        BEFORE INSERT ON Training
        FOR EACH ROW
        BEGIN
            IF EXISTS (
                SELECT 1
                FROM Training
                WHERE MitarbeiterID = NEW.MitarbeiterID
                  AND Datum = NEW.Datum
            ) THEN
                SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Für diesen Fahrer wurde an diesem Tag bereits ein Training gespeichert.';
            END IF;
        END
    ");
} catch (PDOException $e) {
    $message = "Trigger konnte nicht angelegt werden: " . $e->getMessage();
}

$stmt = $pdo->query("
    SELECT Ziel
    FROM Trainingsziel
    ORDER BY Ziel
");
$trainingsziele = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER["REQUEST_METHOD"] === "POST") { // Prüft ob das Formular abgesendet wurde und liest danach die übermittelten Formulardaten aus
    
    $mitarbeiterID = !empty($_POST["MitarbeiterID"]) ? (int)$_POST["MitarbeiterID"] : null;
    
    $teamName = trim($_POST["TeamName"] ?? '');
    $datum = trim($_POST["Datum"] ?? '');
    $kilometer = trim($_POST["GefahreneKilometer"] ?? '');
    $ziel = trim($_POST["Ziel"] ?? '');

    if (!$mitarbeiterID || !$teamName || !$datum || $kilometer === '' || !$ziel) { // Prüft ob alle Pflichtfelder ausgefüllt wurden
        $message = "Bitte füllen Sie alle Felder aus.";
    } else {
        try { // Trainingsdaten werden in die Tabelle Training eingefügt, hier wird der Schritt erst vorbereitet
            $stmt = $pdo->prepare("
                INSERT INTO Training
                    (MitarbeiterID, TeamName, Datum, GefahreneKilometer, Ziel)
                VALUES
                    (:MitarbeiterID, :TeamName, :Datum, :GefahreneKilometer, :Ziel)
            ");
            $stmt->execute([ // Hier wird der BEfehl ausgeführt, die Daten werden in die Datenbank überführt
                ':MitarbeiterID' => $mitarbeiterID,
                ':TeamName' => $teamName,
                ':Datum' => $datum,
                ':GefahreneKilometer' => (float)$kilometer,
                ':Ziel' => $ziel
            ]);

            $success = "Training wurde erfolgreich gespeichert.";
        } catch (PDOException $e) { // Wenn nicht alle Eingaben korrekt und ordnungsgemäß sind kommt es zu einer dieser Meldungen
            if (strpos($e->getMessage(), 'bereits ein Training') !== false || $e->getCode() === '45000') {
                $message = "Für diesen Fahrer wurde an diesem Tag bereits ein Training gespeichert.";
            } else {
                $message = "Training konnte nicht gespeichert werden: " . $e->getMessage();
            }
        }
    }
}
// Hier werden die Datensätze aus der Datenbank ausgelesen, also die Anfrage vorbereitet
$stmt = $pdo->prepare(" 
    SELECT MitarbeiterID, TeamName, Datum, GefahreneKilometer, Ziel
    FROM Training
    WHERE TeamName = ?
    ORDER BY Datum DESC, MitarbeiterID ASC
");
$stmt->execute([$TeamNameSession]); // Hier wird die Anfrage ausgeführt
$trainingsListe = $stmt->fetchAll(PDO::FETCH_ASSOC); // Die Rückgaben werden als assoziatives Array gespeichert
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Training verwalten</title>
</head>
<body>

<h1>Training verwalten</h1>

<?php if (!empty($message)): ?>
    <p style="color: red;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="post" action="ManageTraining.php">
    Fahrer ID:<br>
    <input type="number" name="MitarbeiterID" min="1" required><br><br>

    Teamname:<br>
    <input type="text" name="TeamName" value="<?= htmlspecialchars($TeamNameSession) ?>" required><br><br>

    Datum:<br>
    <input type="date" name="Datum" required><br><br>

    Gefahrene Kilometer:<br>
    <input type="number" name="GefahreneKilometer" min="0" step="0.1" required><br><br>

    Trainingsziel:<br>
    <select name="Ziel" required>
        <option value="">Bitte auswählen</option>
        <?php foreach ($trainingsziele as $z): ?>
            <option value="<?= htmlspecialchars($z) ?>"><?= htmlspecialchars($z) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Training speichern</button>
</form>

<h2>Gespeicherte Trainings</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>Fahrer ID</th>
        <th>Teamname</th>
        <th>Datum</th>
        <th>Gefahrene Kilometer</th>
        <th>Trainingsziel</th>
    </tr>
    <?php foreach ($trainingsListe as $training): ?>
        <tr>
            <td><?= htmlspecialchars($training['MitarbeiterID']) ?></td>
            <td><?= htmlspecialchars($training['TeamName']) ?></td>
            <td><?= htmlspecialchars($training['Datum']) ?></td>
            <td><?= htmlspecialchars($training['GefahreneKilometer']) ?></td>
            <td><?= htmlspecialchars($training['Ziel']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p><a href="ManageCyclist.php">Zurück zur Fahrerverwaltung</a></p>
</body>
</html>

<?php
session_start();
include 'includes/db.inc.php';

if (!isset($_SESSION['NameRV'])) {
    header("Location: index.php");
    exit;
}

if (isset($_SESSION['success'])) {
    echo "<p>Ergebnisse erfolgreich erfasst!</p>";
    unset($_SESSION['success']);
}

if (isset($_POST['speichern'])) {
    $rennen_id = (int) $_POST['rennen_id'];

    foreach ($_POST['platzierung'] as $mitarbeiterID => $platzierung) { // platzierung[5] = 1 => MitarbeiterID 5 hat Platzierung 1

        if ($platzierung < 1) {
            echo "Platzierung muss mindestens 1 sein!";
            return;
        }
        
    }

    $statement = $pdo->prepare("UPDATE NimmtTeil SET Platzierung = ?, Fahrtzeit = ? WHERE RID = ? AND MitarbeiterID = ?");

    $platzierungen = $_POST['platzierung'];

    if (count($platzierungen) != count(array_unique($platzierungen))) {
        echo "<p>Fehler: Platzierungen dürfen nicht doppelt vorkommen!</p>";
        return;
    }

    foreach ($_POST['platzierung'] as $mitarbeiterID => $platzierung) {
        $fahrtzeit = $_POST['fahrtzeit'][$mitarbeiterID];
        $statement->execute([(int)$platzierung, $fahrtzeit, $rennen_id, (int)$mitarbeiterID]);
    }

    $_SESSION['success'] = true;
    header("Location: ergebnisse_erfassen.php");
    exit;
    }

$statement = $pdo->prepare("SELECT DISTINCT r.RID, r.Datum, r.Startort
    FROM Rennen r
    JOIN NimmtTeil n ON r.RID = n.RID
    ORDER BY r.Datum ");

$statement->execute();
$rennen = $statement->fetchAll(PDO::FETCH_ASSOC);


?>

<form method="post">
    <select name="rennen_id" required>
        <option value="">-- Rennen wählen --</option>

        <?php foreach ($rennen as $r) { ?>
            <option value="<?= $r['RID'] ?>"

            <?php if (isset($_POST['rennen_id']) && $_POST['rennen_id'] == $r['RID']) echo 'selected'; ?>> 
                <?= $r['Datum'] . " - " . $r['Startort'] ?>

            </option>
        <?php } ?>
    </select>

    <button type="submit" name="anzeigen">Anzeigen</button>
</form>

<?php
if (isset($_POST['anzeigen']) || isset($_POST['speichern'])) {

    $rennen_id = (int) $_POST['rennen_id'];
    $statement = $pdo->prepare("SELECT MitarbeiterID, Startnummer, Platzierung, Fahrtzeit FROM NimmtTeil WHERE RID = ? ORDER BY Startnummer");
    $statement->execute([$rennen_id]);
    $fahrer = $statement->fetchAll(PDO::FETCH_ASSOC);
    $erfasst = false;

foreach ($fahrer as $f) {

    if ($f['Platzierung'] !== null) { //Verhinderung mehrfaches Eintragen
        $erfasst = true;
        break;
    }

}

if ($erfasst) {
    echo "<p>Ergebnisse bereits erfasst!</p>";
} else {


?>

<form method="post">

<input type="hidden" name="rennen_id" value="<?php echo $rennen_id; ?>">

<table border="1">
<tr>
    <th>Startnummer</th>
    <th>Platzierung</th>
    <th>Fahrtzeit</th>
</tr>

<?php foreach ($fahrer as $f) { ?>
<tr>
    <td><?php echo $f['Startnummer']; ?></td>

    <td>
       <input type="number" min="1" name="platzierung[<?php echo $f['MitarbeiterID']; ?>]" required> //dynamisches Formular; Speichern der Platzierung unter MID
    </td>

    <td>
        <input type="time" name="fahrtzeit[<?php echo $f['MitarbeiterID']; ?>]" required>
    </td>
</tr>
<?php } ?>

</table>

    <button type="submit" name="speichern">Speichern</button>

</form>

<?php 
}
}
?>



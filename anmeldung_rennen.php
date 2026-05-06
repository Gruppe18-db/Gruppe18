<!-- Oleksandra Ishmatova -->

<?php  
include 'includes/db.inc.php';


if (isset($_GET['success'])) {
    echo "<p>Fahrer erfolgreich angemeldet!</p>";
}

if (isset($_GET['copied'])) {
    echo "<p>Anmeldungen erfolgreich kopiert!</p>";
}

if (isset($_POST['kopieren'])) {

    if (empty($_POST['rennen_id'])) {
    echo "<p>Bitte ein Zielrennen auswählen!</p>";
    return;
    }

    if (empty($_POST['quelle_rid'])) {
        echo "<p>Bitte ein Quellrennen auswählen!</p>";
        return;
    }  

    $zielrennen = (int) $_POST['rennen_id'];
    $quellrennen = (int) $_POST['quelle_rid'];

    if ($zielrennen == $quellrennen) {
        echo "<p>Fehler: Zielrennen und Quellrennen dürfen nicht gleich sein!</p>";
        return;
    }

    $statement = $pdo->prepare(" SELECT MitarbeiterID, Teamname FROM NimmtTeil WHERE RID = ?");

    $statement->execute([$quellrennen]);

    $fahrerListe = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (empty($fahrerListe)) {
        echo "<p>Keine Anmeldungen im Quellrennen vorhanden!</p>";
        return;
    }

      $insert = $pdo->prepare("INSERT INTO NimmtTeil (MitarbeiterID, Teamname, RID) VALUES (?, ?, ?)");

    foreach ($fahrerListe as $f) {

        try {

        $insert->execute([$f['MitarbeiterID'], $f['Teamname'], $zielrennen]);

        } catch (PDOException $e) {
            
        }
    }
    header("Location: anmeldung_rennen.php?copied=1");
    exit;

}

$statement = $pdo->prepare("SELECT MitarbeiterID, TeamName, CONCAT(VornameF, ' ', NachnameF) AS Name FROM Fahrer");
$statement->execute();
$fahrer = $statement->fetchAll(PDO::FETCH_ASSOC);

$fahrerMap = [];

foreach ($fahrer as $f) {
    $fahrerMap[$f['MitarbeiterID']] = $f['TeamName'];
}

if (isset($_POST['speichern']) && isset($_POST['fahrer'])) {

    $rennen_id = (int) $_POST['rennen_id'];
    $fahrerListe = $_POST['fahrer'];

        $statementInsert = $pdo->prepare("INSERT INTO NimmtTeil (MitarbeiterID, Teamname, RID) VALUES (?, ?, ?)");

        $fehler = false;

        foreach ($fahrerListe as $fahrerID) {

             $team = $fahrerMap[$fahrerID]; 

            try {

            $statementInsert->execute([$fahrerID, $team, $rennen_id]); 

            } catch (PDOException $e) {
        
                $fehler = true; // Fehler gemerkt, um Erfolgsmeldung zu unterdrücken
            }
        }

        if ($fehler) {
            echo "<p>Fehler: Fahrer ist bereits angemeldet!</p>";
        } else {
            header("Location: anmeldung_rennen.php?success=1");
            exit;
        }

    }

$statement = $pdo->prepare("SELECT RID, Datum, Startort FROM Rennen WHERE Datum >=CURDATE()");
$statement->execute();
$rennen = $statement->fetchAll(PDO::FETCH_ASSOC);


?>

<h2>Rennen auswählen</h2>

<form method="post">
    <label>Rennen:</label><br>

    <select name="rennen_id" required>
        <option value="">-- Bitte wählen --</option>

        <?php
    $statement = $pdo->prepare("
        SELECT DISTINCT r.RID, r.Datum, r.Startort
        FROM Rennen r
        JOIN NimmtTeil n ON r.RID = n.RID
    ");
    $statement->execute();
    $rennenMitFahrern = $statement->fetchAll(PDO::FETCH_ASSOC);
    ?>

        <?php foreach ($rennen as $r) { ?>
            <option value="<?php echo $r['RID']; ?>"
            <?php if(isset($_POST['rennen_id']) && $_POST['rennen_id'] == $r['RID']) echo 'selected'; ?>>
            <?php echo $r['Datum'] . " - " . $r['Startort']; ?>
            </option>

        <?php
        }
        ?>

    </select>
    <br><br>

<label>Anzahl Fahrer:</label><br>
<input type="number" name="anzahl" min="1"><br><br>

<button type="submit" name="weiter">Weiter</button>


<h3>Oder: Anmeldungen kopieren</h3>

<label>Von Rennen kopieren:</label><br>
<select name="quelle_rid">
    <option value="">-- wählen --</option>
    <?php foreach ($rennenMitFahrern as $r) { ?>
        <option value="<?= $r['RID'] ?>">
            <?= $r['Datum'] . " - " . $r['Startort'] ?>
        </option>
    <?php } ?>
</select>
<br><br>

<button type="submit" name="kopieren">Anmeldungen kopieren</button>

</form>

<?php 
if (isset($_POST['weiter'])) {

    $anzahl = $_POST['anzahl'];

?>

<h3>Fahrer auswählen</h3>

<form method="post">

<input type="hidden" name="rennen_id" value="<?php echo $_POST['rennen_id']; ?>"> <!-- Verstecktes Feld, um RID an das nächste Formular zu übergeben -->
<input type="hidden" name="anzahl" value="<?php echo $anzahl; ?>"> <!-- Verstecktes Feld, um Anzahl an das nächste Formular zu übergeben -->


<?php 
for ($i = 0; $i < $anzahl; $i++) { 
?>

    <label>Fahrer <?php echo $i + 1; ?>:</label><br>

    <select name="fahrer[]" required>
        <option value="">-- Fahrer wählen --</option>

        <?php
        foreach ($fahrer as $f) {
        ?>

            <option value="<?php echo $f['MitarbeiterID']; ?>">
                <?php echo $f['Name']; ?>
            </option>

        <?php
        }
        ?>
    </select>
    <br><br>
<?php
}
?>

<button type="submit" name="speichern">Speichern</button>

</form>

<?php
}
?>

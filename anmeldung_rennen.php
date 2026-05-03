<!-- Oleksandra Ishmatova -->

<?php  
include 'includes/db.inc.php';



$statement = $pdo->prepare("SELECT RID, Datum, Startort FROM Rennen");
$statement->execute();
$rennen = $statement->fetchAll(PDO::FETCH_ASSOC);

$statement = $pdo->prepare("SELECT MitarbeiterID, CONCAT(VornameF, ' ', NachnameF) AS Name FROM Fahrer");
$statement->execute();
$fahrer = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Rennen auswählen</h2>

<form method="post">
    <label>Rennen:</label><br>

    <select name="rennen_id" required>
        <option value="">-- Bitte wählen --</option>

        <?php foreach ($rennen as $r) {
        ?>
            <option value="<?php echo $r['RID']; ?>">
                <?php echo $r['Datum'] . " - " . $r['Startort']; ?>
            </option>

        <?php
        }
        ?>

    </select>
    <br><br>

    <label>Anzahl Fahrer:</label><br>
    <input type="number" name="anzahl" min="1" required>
    <br><br>

    <button type="submit" name="weiter">Weiter</button>

</form>

<?php 
if (isset($_POST['weiter'])) {

    $anzahl = $_POST['anzahl'];

?>

<h3>Fahrer auswählen</h3>

<form method="post">

<input type="hidden" name="rennen_id" value="<?= $_POST['rennen_id'] ?>"> // Verstecktes Feld, um RID an das nächste Formular zu übergeben

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

<?php
// Autor: Dilara Öztürk
session_start();
include 'includes/db.inc.php';
include 'includes/Functions.inc.php';

if (!isset($_SESSION['teamchef_logged_in'])) {
    header("Location: LoginTeamchef.php");
    exit;
}

$TeamName = $_SESSION['TeamName'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['speichern'])) {

    $MitarbeiterID = !empty($_POST['MitarbeiterID']) ? (int)$_POST['MitarbeiterID'] : null;
    
    $VornameF       = trim($_POST['VornameF'] ?? '');
    $NachnameF      = trim($_POST['NachnameF'] ?? '');
    $Strasse        = trim($_POST['Strasse'] ?? '');
    $Hausnummer     = trim($_POST['Hausnummer'] ?? '');
    $PLZ            = trim($_POST['PLZ'] ?? '');
    $Ort            = trim($_POST['Ort'] ?? '');
    $Telefonnummer  = trim($_POST['Telefonnummer'] ?? '');

    if ($VornameF && $NachnameF) {
        saveCyclist($pdo, $TeamName, $MitarbeiterID, $VornameF, $NachnameF, $Strasse, $Hausnummer, $PLZ, $Ort, $Telefonnummer);
        $success = "Fahrer wurde erfolgreich gespeichert.";
        header("Location: ManageCyclist.php");
    } else {
        $error = "Vorname und Nachname sind erforderlich.";
    }
}

if (isset($_GET['delete'])) {
    $MitarbeiterID = (int)$_GET['delete'];
    deleteCyclist($pdo, $MitarbeiterID, $TeamName);
    header("Location: ManageCyclist.php");
    exit;
}

$editCyclist = null;

if (isset($_GET['edit'])) {
    $MitarbeiterID = (int)$_GET['edit'];
    $stmt = $pdo->prepare("
        SELECT *
        FROM Fahrer
        WHERE MitarbeiterID = ? AND TeamName = ?
    ");
    $stmt->execute([$MitarbeiterID, $TeamName]);
    $editCyclist = $stmt->fetch(PDO::FETCH_ASSOC);
}

$CyclistList = getCyclist($pdo, $TeamName);
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fahrer verwalten</title>
</head>
<body>

<h1>Fahrer verwalten</h1>

<p><a href="LogoutTeamchef.php">Logout</a></p>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<hr>

<h2><?= $editCyclist ? "Fahrer bearbeiten" : "Neuen Fahrer anlegen" ?></h2>

<form method="post">
    <input type="hidden" name="MitarbeiterID" value="<?= htmlspecialchars($editCyclist['MitarbeiterID'] ?? '') ?>">

    Vorname:
    <input name="VornameF" value="<?= htmlspecialchars($editCyclist['VornameF'] ?? '') ?>" required><br><br>

    Nachname:
    <input name="NachnameF" value="<?= htmlspecialchars($editCyclist['NachnameF'] ?? '') ?>" required><br><br>

    Straße:
    <input name="Strasse" value="<?= htmlspecialchars($editCyclist['Strasse'] ?? '') ?>"><br><br>

    Hausnummer:
    <input name="Hausnummer" value="<?= htmlspecialchars($editCyclist['Hausnummer'] ?? '') ?>"><br><br>

    PLZ:
    <input name="PLZ" value="<?= htmlspecialchars($editCyclist['PLZ'] ?? '') ?>"><br><br>

    Ort:
    <input name="Ort" value="<?= htmlspecialchars($editCyclist['Ort'] ?? '') ?>"><br><br>

    Telefonnummer:
    <input name="Telefonnummer" value="<?= htmlspecialchars($editCyclist['Telefonnummer'] ?? '') ?>"><br><br>

    <button type="submit" name="speichern">Speichern</button>
</form>

<hr>

<h2>Fahrer im Team <?= htmlspecialchars($TeamName) ?></h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Vorname</th>
        <th>Nachname</th>
        <th>Straße</th>
        <th>Hausnummer</th>
        <th>PLZ</th>
        <th>Ort</th>
        <th>Telefonnummer</th>
        <th>Aktionen</th>
    </tr>

    <?php foreach ($CyclistList as $cyclist): ?>
        <tr>
            <td><?= htmlspecialchars($cyclist['MitarbeiterID']) ?></td>
            <td><?= htmlspecialchars($cyclist['VornameF']) ?></td>
            <td><?= htmlspecialchars($cyclist['NachnameF']) ?></td>
            <td><?= htmlspecialchars($cyclist['Strasse']) ?></td>
            <td><?= htmlspecialchars($cyclist['Hausnummer']) ?></td>
            <td><?= htmlspecialchars($cyclist['PLZ']) ?></td>
            <td><?= htmlspecialchars($cyclist['Ort']) ?></td>
            <td><?= htmlspecialchars($cyclist['Telefonnummer']) ?></td>
            <td>
                <a href="?edit=<?= urlencode($cyclist['MitarbeiterID']) ?>">Bearbeiten</a> |
                <a href="?delete=<?= urlencode($cyclist['MitarbeiterID']) ?>" onclick="return confirm('Sind Sie sicher?')">Löschen</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<p>
    <a href="ManageTraining.php">
        <button type="button">Trainings verwalten</button>
    </a>
</p>

<!-- Oleksandra Ishmatova -->

<p>
    <a href="anmeldung_rennen.php">
        <button type="button">Fahrer zu Rennen anmelden</button>
    </a>
</p>

<!-- Max Boger -->
<p>
    <a href="Auswertungsbereich.php">
        <button>Auswertung</button>
    </a>
</p>

</body>
</html>

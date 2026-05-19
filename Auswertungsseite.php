<!-- Max Boger -->

<?php
require_once 'Auswertung.php';
require_once 'includes/db.inc.php';
session_start();

$ziel = $_GET['ziel'];
$start = $_GET['startdatum'] ?? null;
$ende = $_GET['enddatum'] ?? null;
$teamName = $_SESSION['TeamName'];

if (empty($_SESSION['TeamName'])) {
    header("Location: login.php");
    exit;
}

$auswertung = new Auswertung($pdo);
$fahrerDaten = $auswertung->getDaten($teamName, $ziel, $start, $ende);
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Auswertung</title>
    </head>
    <body>
        <h1>Auswertung</h1>
            <?php foreach ($fahrerDaten as $mid => $fahrer): ?>

                <table border="1">
                    <tr>
                        <th colspan="7">
                            <?php echo $mid . " " . $fahrer['Name']; ?>
                        </th>
                    </tr>

                    <tr>
                        <th>Monat</th>
                        <th>Summe</th>
                        <th>Durchschnitt</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Median</th>
                        <th>Standardabw</th>
                    </tr>

                    <?php
                    foreach ($fahrer['trainings'] as $monat => $werte):
                    ?>
                    <tr>
                        <td><?php echo $monat; ?></td>
                        <td><?php echo $werte['summe']; ?></td>
                        <td><?php echo $werte['durchschnitt']; ?></td>
                        <td><?php echo $werte['min']; ?></td>
                        <td><?php echo $werte['max']; ?></td>
                        <td><?php echo $werte['median']; ?></td>
                        <td><?php echo $werte['stdabw']; ?></td>
                    </tr>
                    <?php endforeach; ?>

                </table>
                <br><br>
            <?php endforeach; ?>
                
    </body>
</html>

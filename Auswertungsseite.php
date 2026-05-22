<!-- Max Boger -->

<?php
require_once 'Auswertung.php';
require_once 'includes/db.inc.php';
session_start();

$ziel = $_GET['ziel'];
$start = $_GET['startdatum'];
$ende = $_GET['enddatum'];

// falls kein TeamName -> user nicht eingeloggt -> weiterleitung auf Login Seite
if (empty($_SESSION['TeamName'])) {
    header("Location: LoginTeamchef.php");
    exit;
}
$teamName = $_SESSION['TeamName'];

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

        <p>Trainingsziel: <?php echo htmlspecialchars($ziel); ?></p>
        <p>
            Zeitraum:
            <?php echo $start ? htmlspecialchars($start) : "-"; ?>
            bis
            <?php echo $ende ? htmlspecialchars($ende) : "-"; ?>
        </p>
            <!-- jeweils eine Tabelle für jeden Fahrer-->
            <?php foreach ($fahrerDaten as $mid => $fahrer): ?>

                <table border="1">
                    <tr>
                        <th colspan="7">
                            <?php echo $mid . " " . htmlspecialchars($fahrer['Name']); ?>
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
                    // in der Tabelle ein Datensatz pro Monat
                    foreach ($fahrer['trainings'] as $monat => $werte):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($monat); ?></td>
                        <td><?php echo $werte['summe']; ?></td>
                        <td><?php echo number_format($werte['durchschnitt'], 2); ?></td>
                        <td><?php echo $werte['min']; ?></td>
                        <td><?php echo $werte['max']; ?></td>
                        <td><?php echo number_format($werte['median'], 2); ?></td>
                        <td><?php echo number_format($werte['stdabw'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>

                </table>
                <br><br>
            <?php endforeach; ?>

            <p>
                <a href="Auswertungsbereich.php">
                    <button>neue Suche</button>
                </a>
            </p>
    </body>
</html>

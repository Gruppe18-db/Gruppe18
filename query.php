<?php
require_once "db.php";

$sql = "SELECT * FROM Fahrer";

$result = mysqli_query($connection, $sql);
if (!$result) {
    die("Query fehlgeschlagen: " . mysqli_error($connection));
}

function getFahrer($result) {
    $daten = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $daten[] = $row;
    }

    return $daten;
}
?>
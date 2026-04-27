<?php
// config einbinden
require_once "config.php";

// Verbindung herstellen
$connection = mysqli_connect($host, $user, $password, $db);


// Fehler prüfen
if (!$connection) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}
?>
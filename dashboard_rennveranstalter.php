<!-- Oleksandra Ishmatova -->

<?php
require_once "db.php";
session_start();

$NameRV = $_SESSION['NameRV']; 

if (!empty($_POST['Datum'])) {

    $Datum = $_POST['Datum'];
    $Startort = $_POST['Startort'];
    $Km = $_POST['Km'];
    $Hoehenmeter = $_POST['Hoehenmeter'];
    $Steigung = $_POST['Steigung'];

    $query = "INSERT INTO Rennen 
    (Datum, Startort, AnzahlGefahreneKilometer, Hoehenmeter, MaxSteigung, NameRV)
    VALUES 
    ('$Datum', '$Startort', '$Km', '$Hoehenmeter', '$Steigung', '$NameRV')";

    if (mysqli_query($connection, $query)) {
        echo "Rennen erfolgreich erstellt!";
    } else {
        echo "Fehler: " . mysqli_error($connection);
    }
}
?>

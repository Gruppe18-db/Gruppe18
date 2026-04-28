<?php
require_once "db.php";

    if (!empty($_POST['NameRV']) and !empty($_POST['Kenwort'])) {

    $NameRV = $_POST['NameRV'];
    $Kennwort = $_POST['Kennwort'];

    $query = "INSERT INTO Rennveranstalter SET NameRV='$NameRV', Kennwort='Kennwort' ";

    }
?>
<?php
require_once "db.php";

    if (!empty($_POST['NameRV']) && !empty($_POST['Kennwort'])) {

    $NameRV = $_POST['NameRV'];
    $Kennwort = $_POST['Kennwort'];

    $query = "SELECT * FROM Rennveranstalter WHERE NameRV = '$NameRV' AND Kennwort = '$Kennwort'";

    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 1) {
        echo "Login erfolgreich!";
    } else {
        echo "Ungültige Daten!";
    }
}
    
    
?>


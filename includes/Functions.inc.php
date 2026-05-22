<?php
// Autor: Dilara Öztürk

function createTeamchef($pdo, $LoginnameTC, $Kennwort, $VornameTC, $NachnameTC, $TeamName) {
    $hash = password_hash($Kennwort, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
    INSERT INTO Teamchef (Loginname, VornameTC, NachnameTC, Kennwort, TeamName)
    VALUES (:Loginname, :VornameTC, :NachnameTC, :Kennwort, :TeamName)"
    );
    $stmt->execute([
        'Loginname' => $LoginnameTC,
        'VornameTC' => $VornameTC,
        'NachnameTC' => $NachnameTC,
        'Kennwort' => $hash,
        'TeamName' => $TeamName
    ]);
}

function createTeam($pdo, $TeamName, $LoginnameTC) {
    $stmt = $pdo->prepare("INSERT INTO Team (TeamName, LoginnameTC) VALUES (:TeamName, :LoginnameTC)"
    );
    $stmt->execute(['TeamName' => $TeamName, 'LoginnameTC' => $LoginnameTC]);
}

function teamExists($pdo, $TeamName) {
    $stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM Team 
    WHERE TeamName = ?"
    );
    $stmt->execute([$TeamName]);
    return $stmt->fetchColumn() > 0;
}

function saveCyclist($pdo, $TeamName, $MitarbeiterID, $VornameF, $NachnameF, $Strasse, $Hausnummer, $PLZ, $Ort, $Telefonnummer) {
    if ($MitarbeiterID !== null) {
        $stmt = $pdo->prepare("
            UPDATE Fahrer
            SET VornameF = ?, NachnameF = ?, Strasse = ?, Hausnummer = ?, PLZ = ?, Ort = ?, Telefonnummer = ?
            WHERE MitarbeiterID = ? AND TeamName = ?
        ");
        $stmt->execute([$VornameF, $NachnameF, $Strasse, $Hausnummer, $PLZ, $Ort, $Telefonnummer, $MitarbeiterID, $TeamName]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO Fahrer (TeamName, VornameF, NachnameF, Strasse, Hausnummer, PLZ, Ort, Telefonnummer)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$TeamName, $VornameF, $NachnameF, $Strasse, $Hausnummer, $PLZ, $Ort, $Telefonnummer]);
    }
}

function getCyclist($pdo, $TeamName) {
    $stmt = $pdo->prepare("
    SELECT *
    FROM Fahrer
    WHERE TeamName = ?"
    );
    $stmt->execute([$TeamName]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteCyclist($pdo, $MitarbeiterID, $TeamName) {
    $stmt = $pdo->prepare("
    DELETE FROM Fahrer
    WHERE MitarbeiterID = ? AND TeamName = ?"
    );
    $stmt->execute([$MitarbeiterID, $TeamName]);
}

// Oleksandra Ishmatova

function rennveranstalterExists($pdo, $NameRV) {

    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM Rennveranstalter
        WHERE NameRV = ?
    ");

    $stmt->execute([$NameRV]);

    return $stmt->fetchColumn() > 0;
}

function loginRennveranstalter($pdo, $NameRV, $Kennwort) {

    $stmt = $pdo->prepare("
        SELECT NameRV, Kennwort
        FROM Rennveranstalter
        WHERE NameRV = ?
    ");

    $stmt->execute([$NameRV]);
    $user = $stmt->fetch();

    if ($user && password_verify($Kennwort, $user['Kennwort'])) {
        return $user;
    }
    return false;
}

function resultsExist($pdo, $rennen_id) {

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM NimmtTeil
        WHERE RID = ?
        AND (Platzierung IS NOT NULL OR Fahrtzeit IS NOT NULL)
    ");

    $stmt->execute([$rennen_id]);
    return $stmt->fetchColumn() > 0;
}

function getRaceParticipants($pdo, $rennen_id) {

    $stmt = $pdo->prepare("
        SELECT MitarbeiterID, Startnummer, Platzierung, Fahrtzeit
        FROM NimmtTeil
        WHERE RID = ?
        ORDER BY Startnummer
    ");

    $stmt->execute([$rennen_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createRace($pdo, $Datum, $Startort, $Km, $Hoehenmeter, $Steigung, $NameRV) {

    $stmt = $pdo->prepare("
        INSERT INTO Rennen
        (Datum, Startort, AnzahlGefahreneKilometer, Hoehenmeter, MaxSteigung, NameRV)
        VALUES
        (?, ?, ?, ?, ?, ?)
    ");

    return $stmt->execute([
        $Datum,
        $Startort,
        $Km,
        $Hoehenmeter,
        $Steigung,
        $NameRV
    ]);
}

function getTeamDrivers($pdo, $teamName) {

    $stmt = $pdo->prepare("
        SELECT MitarbeiterID,
               TeamName,
               CONCAT(VornameF, ' ', NachnameF) AS Name
        FROM Fahrer
        WHERE TeamName = ?
    ");

    $stmt->execute([$teamName]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

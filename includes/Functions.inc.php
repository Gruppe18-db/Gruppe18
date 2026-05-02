<?php
// Autor: Dilara Öztürk

function createTeamchef($pdo, $Loginname, $Kennwort, $VornameTC, $NachnameTC) {
    $stmt = $pdo->prepare("
    INSERT INTO Teamchef (Loginname, Kennwort, VornameTC, NachnameTC)
    VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$Loginname, password_hash($Kennwort, PASSWORD_DEFAULT), $VornameTC, $NachnameTC]);
}

function createTeam($pdo, $TeamName) {
    $stmt = $pdo->prepare("INSERT INTO Team (TeamName) VALUES (?)"
    );
    $stmt->execute([$TeamName]);
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
    if ($MitarbeiterID) {
        
        $stmt = $pdo->prepare("
        UPDATE Fahrer
        SET VornameF = ?, NachnameF = ?, Strasse = ?, Hausnummer = ?, PLZ = ?, Ort = ?, Telefonnummer = ?
        WHERE MitarbeiterID = ? AND TeamName = ?"
        );
        $stmt->execute([$VornameF, $NachnameF, $Strasse, $Hausnummer, $PLZ, $Ort, $Telefonnummer, $MitarbeiterID, $TeamName]);
    } else {
        $stmt = $pdo->prepare("
        INSERT INTO Fahrer (TeamName, VornameF, NachnameF, Strasse, Hausnummer, PLZ, Ort, Telefonnummer)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
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

?>
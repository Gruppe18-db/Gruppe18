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
    $stmt1 = $pdo->prepare("INSERT INTO Team (TeamName, LoginnameTC) VALUES (:TeamName, :LoginnameTC)"
    );
    $stmt1->execute(['TeamName' => $TeamName, 'LoginnameTC' => $LoginnameTC]);
    /* $stmt2 = $pdo->prepare("UPDATE Teamchef SET TeamName = :TeamName WHERE LoginnameTC = :LoginnameTC"
    );
    $stmt2->execute(['TeamName' => $TeamName, 'LoginnameTC' => $LoginnameTC]); */
    
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

?>

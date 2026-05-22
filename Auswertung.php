<!-- Max Boger -->
<?php

// Klasse zur Berechnung der Daten für die Auswertung der Trainings
class Auswertung
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // holt die gewünschten Fahrerdaten -> speichert die strukturiert in einem Array
    public function getDaten(string $team, string $ziel, $start, $ende): array
    {
        // die stored procedure erwartet null falls kein Datum angegeben ist
        $start = $start ?: null;
        $ende  = $ende ?: null;

        $stmt = $this->pdo->prepare("CALL get_training_stats_per_month(:team, :ziel, :start, :ende)");
        $stmt->execute([
            ':team' => $team,
            ':ziel' => $ziel,
            ':start' => $start,
            ':ende' => $ende
        ]);

        $fahrerDaten = [];

        // durchläuft alle Datensätze, die vn der Stored Procedure geliefert wurden
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $mid = $row['MitarbeiterID'];
            $monat = $row['Monat'];

            // in $fahrerDaten ein neues Array für einen Fahrer anlegen, falls für diesen noch keins existiert
            if(!isset($fahrerDaten[$mid])) {
                $fahrerDaten[$mid] = [
                    'Name' => $row['NachnameF'] . ', ' . $row['VornameF'],
                    'trainings' => []
                ];
            }

            // die Rohdaten, die als String gespeichert sind, werden in ein Array umgewandelt
            $rohdaten = json_decode($row['Rohdaten'], true) ?? [];

            // die Werte für die Auswertung zum Monat zugehörig speichern
            $fahrerDaten[$mid]['trainings'][$monat] = [
                'summe' => $row['Summe'],
                'durchschnitt' => $row['Durchschnitt'],
                'min' => $row['MinKm'],
                'max' => $row['MaxKm'],
                'median' => $this->getMedian($rohdaten),
                'stdabw' => $this->getStandardabweichung($rohdaten, $row['Durchschnitt'])
            ];
        }
        $stmt->closeCursor();
        return $fahrerDaten;
    }


    private function getMedian(array $rohdaten): float
    {
        if(count($rohdaten) === 0) {
            return 0.0;
        }
        $sorted = $rohdaten;
        sort($sorted);
        $n = count($sorted);

        if($n % 2 === 0) {
            $median = ($sorted[$n / 2 - 1] + $sorted[$n / 2]) / 2;
        } else {
            $median = $sorted[floor($n / 2)];
        }
        return $median;
    }

    private function getStandardabweichung(array $rohdaten, $durchschnitt): float
    {
        if (count($rohdaten) === 0) {
            return 0.0;
        }
        $summeDiffQ = 0;
        foreach ($rohdaten as $wert) {
            $summeDiffQ += pow($wert - $durchschnitt, 2);
        }
        $varianz = $summeDiffQ / count($rohdaten);
        $stdabw = sqrt($varianz);

        return $stdabw;
    }
}

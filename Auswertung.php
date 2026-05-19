<!-- Max Boger -->
<?php

class Auswertung
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getDaten(string $team, string $ziel, $start, $ende): array
    {
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

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $mid = $row['MitarbeiterID'];
            $monat = $row['Monat'];

            if(!isset($fahrerDaten[$mid])) {
                $fahrerDaten[$mid] = [
                    'Name' => $row['NachnameF'] . ', ' . $row['VornameF'],
                    'trainings' => []
                ];
            }

            $rohdaten = json_decode($row['Rohdaten'], true) ?? [];

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
<?php
class ZahteviZaOdmor
{
    public static function prebroj(int $radnik_id, int $godina): int
    {
        $broj_iskoricenih_dana = 0;
        try {
            $database = Database::getInstance();
            $iskorisceni_odmori = (array) $database->get()['iskorisceni_odmori'];
            $zahtevi_za_odmor = [];
            if (isset($iskorisceni_odmori[$godina]) && isset($iskorisceni_odmori[$godina][$radnik_id])) {
                $zahtevi_za_odmor = $iskorisceni_odmori[$godina][$radnik_id];
            }
            foreach ($zahtevi_za_odmor as $unos) {
                $broj_iskoricenih_dana += (int) $unos['broj_dana'];
            }
        } catch (Exception $e) {
        }
        return $broj_iskoricenih_dana;
    }
    public static function proveri_preklapanje(string $pozicija, string $datum_pocetka, int $broj_dana): bool
    {
        $unix_pocetak = strtotime($datum_pocetka . ' 03:00:00');
        $ima_preklapanja = false;
        try {
            $database = Database::getInstance();
            $iskorisceni_odmori = (array) $database->get()['iskorisceni_odmori'];
            if ($iskorisceni_odmori[date('Y')]) {
                $iskorisceni_odmori = $iskorisceni_odmori[date('Y')];
            }
            foreach ($iskorisceni_odmori as $radnik_id => $radnikovi_odmori) {
                $radnik = new Radnik($radnik_id);
                if ($radnik->pozicija == $pozicija) {
                    foreach ($radnikovi_odmori as $radnikov_odmor) {
                        $radnikov_odmor_unix = strtotime($radnikov_odmor['datum_pocetka'] . ' 03:00:00');
                        $radnikov_odmor_unix_kraj = strtotime($radnikov_odmor['datum_pocetka'] . ' 03:00:00 +' . $radnikov_odmor['broj_dana'] . ' weekdays'); // uvecava datum pocetka za $broj_dana RADNIH dana
                        if ($unix_pocetak >= $radnikov_odmor_unix && $unix_pocetak <= $radnikov_odmor_unix_kraj) {
                            $ima_preklapanja = true;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $ima_preklapanja = true; // fail safe, zato je true
        }
        return $ima_preklapanja;
    }
}

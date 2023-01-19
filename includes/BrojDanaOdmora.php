<?php
class BrojDanaOdmora
{
    public static function iskoriscenih_dana(Radnik $radnik, int $godina): int
    {
        $broj_dana = 0;

        return $broj_dana;
    }
    public static function izracunaj(Radnik $radnik): int
    {
        // bool $is_neodredjeno = false, string $datum_pocetka_rada
        $is_neodredjeno = (bool) $radnik->is_ugovor_na_neodredjeno;
        $datum_pocetka_rada = (string) $radnik->datum_pocetka_rada;
        if ($is_neodredjeno) {
            // zaposleni na neodredjeno ima 20 dana odmora od dana pocetka rada ili od pocetka godine
            // takodje, prenosi mu se odmor iz prethodne godine do 30.06.
            $broj_dana = 20;
            // ako je poceo da radi pre ove godine
            if ((int) date('Y', strtotime($datum_pocetka_rada . ' 03:00:00')) < (int) date('Y')) {
                // ako je danasnji dan manji od 30.06. ove godine
                if (time() < strtotime(date('Y') . '-06-30 03:00:00')) {
                    $broj_dana_od_ranije = 20;
                    $iskoriceni_broj_dana_prosle_godine = ZahteviZaOdmor::prebroj($radnik->id, ((int) date('Y')) - 1);
                    $broj_dana += ($broj_dana_od_ranije - $iskoriceni_broj_dana_prosle_godine);
                }
            }
            return $broj_dana;
        } else {
            return floor(20 / 12 * broj_punih_meseci($datum_pocetka_rada, date('Y-m-d')));
        }
    }
}

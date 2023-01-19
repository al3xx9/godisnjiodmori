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
}

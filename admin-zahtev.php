<?php
require 'config.php';

check_is_logged_in();

$database = Database::getInstance();

if ($_SESSION['username'] != 'admin') {
    redirect('index.php');
}

$radnik_id = $_GET['radnik_id'];
$datum_pocetka = $_GET['datum_pocetka'];
$broj_dana = $_GET['broj_dana'];

$iskorisceni_odmori = (array) $database->get()['iskorisceni_odmori'];
$zahtevi_za_odmor = (array) $database->get()['zahtevi_za_odmor'];
$odbijeni_zahtevi = (array) $database->get()['odbijeni_zahtevi'];

if ($_GET['action'] == 'prihvati') {
    if (!isset($zahtevi_za_odmor[$radnik_id])) {
        redirect('index.php');
    }
    foreach ($zahtevi_za_odmor[$radnik_id] as $_f => $_zahtev) {
        if ($_zahtev['datum_pocetka'] == $datum_pocetka && $_zahtev['broj_dana'] == $broj_dana) {
            if (!isset($iskorisceni_odmori[date('Y', strtotime($datum_pocetka))])) {
                $iskorisceni_odmori[date('Y', strtotime($datum_pocetka))] = [];
            }
            if (!isset($iskorisceni_odmori[date('Y', strtotime($datum_pocetka))][$radnik_id])) {
                $iskorisceni_odmori[date('Y', strtotime($datum_pocetka))][$radnik_id] = [];
            }
            $iskorisceni_odmori[date('Y', strtotime($datum_pocetka))][$radnik_id][] = [
                'datum_pocetka' => $datum_pocetka,
                'broj_dana' => $broj_dana
            ];
            unset($zahtevi_za_odmor[$radnik_id][$_f]);
            $database->setOne('zahtevi_za_odmor', $zahtevi_za_odmor);
            $database->setOne('iskorisceni_odmori', $iskorisceni_odmori);
            redirect('index.php');
        }
    }
    redirect('index.php');
}

if ($_GET['action'] == 'odbij') {
    if (!isset($zahtevi_za_odmor[$radnik_id])) {
        redirect('index.php');
    }
    foreach ($zahtevi_za_odmor[$radnik_id] as $_f => $_zahtev) {
        if ($_zahtev['datum_pocetka'] == $datum_pocetka && $_zahtev['broj_dana'] == $broj_dana) {
            if (!isset($odbijeni_zahtevi[$radnik_id])) {
                $odbijeni_zahtevi[$radnik_id] = [];
            }
            $odbijeni_zahtevi[$radnik_id][] = [
                'datum_pocetka' => $datum_pocetka,
                'broj_dana' => $broj_dana
            ];
            unset($zahtevi_za_odmor[$radnik_id][$_f]);
            $database->setOne('zahtevi_za_odmor', $zahtevi_za_odmor);
            $database->setOne('odbijeni_zahtevi', $odbijeni_zahtevi);
            redirect('index.php');
        }
    }
}

redirect('index.php');

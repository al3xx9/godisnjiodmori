<?php
require 'config.php';

check_is_logged_in();

$database = Database::getInstance();

$iskorisceni_odmori = (array) $database->get()['iskorisceni_odmori'];
$zahtevi_za_odmor = (array) $database->get()['zahtevi_za_odmor'];
$odbijeni_zahtevi = (array) $database->get()['odbijeni_zahtevi'];

if ($_SESSION['username'] != 'admin') {
    $radnik = get_radnik_by_username($_SESSION['username']);
    $rasplozivo_dana_odmora = BrojDanaOdmora::izracunaj($radnik);
    $odobreni_zahtevi = [];
    foreach ($iskorisceni_odmori as $godina => $iskorisceni_odmori_po_godini) {
        if (isset($iskorisceni_odmori_po_godini[$radnik->id])) {
            foreach ((array) $iskorisceni_odmori_po_godini[$radnik->id] as $odmor_item) {
                $odobreni_zahtevi[] = $odmor_item;
            }
        }
    }

    $zahtevi_na_cekanju = [];
    if (isset($zahtevi_za_odmor[$radnik->id])) {
        $zahtevi_na_cekanju = $zahtevi_za_odmor[$radnik->id];
    }

    $moji_odbijeni_zahtevi = [];
    if (isset($odbijeni_zahtevi[$radnik->id])) {
        $moji_odbijeni_zahtevi = $odbijeni_zahtevi[$radnik->id];
    }
}
?>
<h1>Sistem za elektronsku evidenciju godisnjih odmora</h1>
<p>Ulogovani ste kao <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>. <a href="<?php echo htmlspecialchars($www); ?>login.php?logout=1">Logout</a></p>
<?php
if ($_SESSION['username'] == 'admin') {
?>
    <h3>Zahtevi koji cekaju odobrenje</h3>
    <table style="width:100%" cellpadding="0" cellspacing="0" border="1">
        <tr>
            <th>Radnik</th>
            <th>Datum pocetka</th>
            <th>Broj dana</th>
            <th>Akcije</th>
        </tr>
        <?php foreach ($zahtevi_za_odmor as $radnik_id => $zahtevi_za_odmor_radnika) {
            $_radnik = new Radnik($radnik_id); ?>
            <?php foreach ($zahtevi_za_odmor_radnika as $zahtev) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($_radnik->ime); ?></td>
                    <td><?php echo htmlspecialchars($zahtev['datum_pocetka']); ?></td>
                    <td><?php echo htmlspecialchars($zahtev['broj_dana']); ?></td>
                    <td>
                        <a href="<?php echo htmlspecialchars($www); ?>admin-zahtev.php?action=prihvati&radnik_id=<?php echo htmlspecialchars($radnik_id); ?>&datum_pocetka=<?php echo htmlspecialchars($zahtev['datum_pocetka']); ?>&broj_dana=<?php echo htmlspecialchars($zahtev['broj_dana']); ?>">Prihvati</a>
                        &middot;
                        <a href="<?php echo htmlspecialchars($www); ?>admin-zahtev.php?action=odbij&radnik_id=<?php echo htmlspecialchars($radnik_id); ?>&datum_pocetka=<?php echo htmlspecialchars($zahtev['datum_pocetka']); ?>&broj_dana=<?php echo htmlspecialchars($zahtev['broj_dana']); ?>">Odbij</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>
    <h3>Odobreni zahtevi</h3>
    <table style="width:100%" cellpadding="0" cellspacing="0" border="1">
        <tr>
            <th>Radnik</th>
            <th>Datum pocetka</th>
            <th>Broj dana</th>
        </tr>
        <?php foreach ($iskorisceni_odmori as $godina => $iskorisceni_odmori_po_godini) { ?>
            <?php foreach ($iskorisceni_odmori_po_godini as $radnik_id => $iskorisceni_odmori_radnika) {
                $_radnik = new Radnik($radnik_id); ?>
                <?php foreach ($iskorisceni_odmori_radnika as $zahtev) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($_radnik->ime); ?></td>
                        <td><?php echo htmlspecialchars($zahtev['datum_pocetka']); ?></td>
                        <td><?php echo htmlspecialchars($zahtev['broj_dana']); ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </table>
    <h3>Odbijeni zahtevi</h3>
    <table style="width:100%" cellpadding="0" cellspacing="0" border="1">
        <tr>
            <th>Radnik</th>
            <th>Datum pocetka</th>
            <th>Broj dana</th>
        </tr>
        <?php foreach ($odbijeni_zahtevi as $radnik_id => $odbijeni_zahtevi_radnika) {
            $_radnik = new Radnik($radnik_id); ?>
            <?php foreach ($odbijeni_zahtevi_radnika as $zahtev) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($_radnik->ime); ?></td>
                    <td><?php echo htmlspecialchars($zahtev['datum_pocetka']); ?></td>
                    <td><?php echo htmlspecialchars($zahtev['broj_dana']); ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>
<?php
} else {
?>
    <p>Vase radno mesto: <?php echo htmlspecialchars($radnik->pozicija); ?></p>
    <p><b>Raspoloziv broj dana odmora: <?php echo htmlspecialchars($rasplozivo_dana_odmora); ?></p>
    <p><?php if ($radnik->is_ugovor_na_neodredjeno) {
            echo 'Zaposleni ste na neodredjeno vreme';
        } else {
            echo 'Zaposleni ste na odredjeno vreme';
        } ?></p>
    <p><a href="unesi-zahtev.php">Unesite novi zahtev</a></p>
    <h3>Vasi odobreni zahtevi</h3>
    <table style="width:100%" cellpadding="0" cellspacing="0" border="1">
        <tr>
            <th>Datum pocetka</th>
            <th>Broj dana</th>
        </tr>
        <?php foreach ($odobreni_zahtevi as $odobren_zahtev) { ?>
            <tr>
                <td><?php echo htmlspecialchars($odobren_zahtev['datum_pocetka']); ?></td>
                <td><?php echo htmlspecialchars($odobren_zahtev['broj_dana']); ?></td>
            </tr>
        <?php } ?>
    </table>
    <h3>Vasi odbijeni zahtevi</h3>
    <table style="width:100%" cellpadding="0" cellspacing="0" border="1">
        <tr>
            <th>Datum pocetka</th>
            <th>Broj dana</th>
        </tr>
        <?php foreach ($moji_odbijeni_zahtevi as $moj_odbijen_zahtev) { ?>
            <tr>
                <td><?php echo htmlspecialchars($moj_odbijen_zahtev['datum_pocetka']); ?></td>
                <td><?php echo htmlspecialchars($moj_odbijen_zahtev['broj_dana']); ?></td>
            </tr>
        <?php } ?>
    </table>
    <h3>Vasi zahtevi na cekanju</h3>
    <table style="width:100%" cellpadding="0" cellspacing="0" border="1">
        <tr>
            <th>Datum pocetka</th>
            <th>Broj dana</th>
        </tr>
        <?php foreach ($zahtevi_na_cekanju as $zahtev_na_cekanju) { ?>
            <tr>
                <td><?php echo htmlspecialchars($zahtev_na_cekanju['datum_pocetka']); ?></td>
                <td><?php echo htmlspecialchars($zahtev_na_cekanju['broj_dana']); ?></td>
            </tr>
        <?php } ?>
    </table>
<?php
}
?>
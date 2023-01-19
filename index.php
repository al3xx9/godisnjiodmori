<?php
require 'config.php';

check_is_logged_in();

$database = Database::getInstance();

if ($_SESSION['username'] != 'admin') {
    $radnik = get_radnik_by_username($_SESSION['username']);
    $rasplozivo_dana_odmora = BrojDanaOdmora::izracunaj($radnik);
}
?>
<h1>Sistem za elektronsku evidenciju godisnjih odmora</h1>
<p>Ulogovani ste kao <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>. <a href="<?php echo htmlspecialchars($www); ?>login.php?logout=1">Logout</a></p>
<?php
if ($_SESSION['username'] == 'admin') {
?>
    <h3>Zahtevi koji cekaju odobrenje</h3>
    <h3>Odobreni zahtevi</h3>
    <h3>Odbijeni zahtevi</h3>
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
    <h3>Vasi odbijeni zahtevi</h3>
    <h3>Vasi zahtevi na cekanju</h3>
<?php
}
?>
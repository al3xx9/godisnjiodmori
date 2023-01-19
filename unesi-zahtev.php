<?php
require 'config.php';

check_is_logged_in();

$error = false;

$database = Database::getInstance();

if ($_SESSION['username'] == 'admin') {
    redirect('index.php');
}

$radnik = get_radnik_by_username($_SESSION['username']);
$rasplozivo_dana_odmora = BrojDanaOdmora::izracunaj($radnik);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
}
?>
<h1>Sistem za elektronsku evidenciju godisnjih odmora</h1>
<?php if ($error) { ?>
    <p style="color:red"><?php echo $error; ?></p>
<?php } ?>
<h3>Unosenje novog zahteva za godisnji odmor</h3>
<p><b>Raspoloziv broj dana odmora: <?php echo htmlspecialchars($rasplozivo_dana_odmora); ?></p>
<form method="post" action="<?php echo htmlspecialchars($www); ?>unesi-zahtev.php">
    <p><b>Datum pocetka</b></p>
    <input type="text" name="datum_pocetka" value="" placeholder="YYYY-MM-DD" />
    <br />
    <p><b>Broj dana</b></p>
    <input type="number" name="broj_dana" value="" placeholder="" />
    <br />
    <button type="submit">Save</button>
</form>
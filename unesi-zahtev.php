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
    $datum_pocetka = $_POST['datum_pocetka'];
    $broj_dana = (int) $_POST['broj_dana'];

    if (!$error && !$datum_pocetka) {
        $error = 'Niste uneli datum!';
    }

    if (!$error && date('Y', strtotime($datum_pocetka)) == '1970') {
        $error = 'Uneti datum je pogresnog formata!';
    }

    if (!$error && strtotime($datum_pocetka) < time()) {
        $error = 'Ne mozete uneti odmor unazad/retroaktivno!';
    }

    if (!$error && $broj_dana < 1) {
        $error = 'Uneli ste nevalidan broj dana';
    }

    if (!$error && $broj_dana > $rasplozivo_dana_odmora) {
        $error = 'Uneli ste vise dana odmora nego sto vam je na raspolaganju';
    }

    // @todo provera da li neko na istoj poziciji ima preklapajuci odmor

    $zahtevi_za_odmor = (array) $database->get()['zahtevi_za_odmor'];
    if (!isset($zahtevi_za_odmor[$radnik->id])) {
        $zahtevi_za_odmor[$radnik->id] = [];
    }

    $zahtevi_za_odmor[$radnik->id][] = [
        'datum_pocetka' => $datum_pocetka,
        'broj_dana' => $broj_dana
    ];

    $database->setOne('zahtevi_za_odmor', $zahtevi_za_odmor);

    echo '<h1>Zahtev je uspesno unet!</h1><a href="' . htmlspecialchars($www) . 'index.php">Povratak na pocetnu</a>';
    exit;
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
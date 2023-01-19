<?php
require 'config.php';

check_is_logged_in();

$database = Database::getInstance();
?>
<h1>Sistem za elektronsku evidenciju godisnjih odmora</h1>
<?php
$radnik = new Radnik('1');
var_dump($radnik->ime);
$brojDana = BrojDanaOdmora::izracunaj($radnik);
var_dump($brojDana);
?>
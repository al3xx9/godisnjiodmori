<?php
require 'config.php';

check_is_logged_in();

$database = Database::getInstance();

if ($_SESSION['username'] == 'admin') {
    redirect('index.php');
}
?>
<h1>Sistem za elektronsku evidenciju godisnjih odmora</h1>
<h3>Unosenje novog zahteva za godisnji odmor</h3>
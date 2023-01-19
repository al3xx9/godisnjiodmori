<?php
require 'config.php';

check_is_logged_in();

$database = Database::getInstance();

if ($_SESSION['username'] != 'admin') {
    redirect('index.php');
}

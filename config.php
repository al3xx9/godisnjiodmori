<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('log_errors', true);
ini_set('error_log', 'php_errors.log');

session_start();

require 'includes/functions.php';
require 'includes/Database.php';
require 'includes/EntityInterface.php';
require 'includes/AbstractEntity.php';
require 'includes/Radnik.php';
require 'includes/Uloge.php';
require 'includes/ZahteviZaOdmor.php';
require 'includes/BrojDanaOdmora.php';

$www = 'http://localhost/godisnjiodmori/';

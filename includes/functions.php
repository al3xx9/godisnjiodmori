<?php
function redirect(string $location): void
{
    global $www;
    header('Location: ' . $www . $location, true, 302);
    exit;
}

function check_is_logged_in(): void
{
    if (!isset($_SESSION['username']) || !$_SESSION['username']) {
        redirect('login.php');
    }
}

function login_user(string $username): void
{
    $_SESSION['username'] = $username;
}


function logout_user(): void
{
    unset($_SESSION['username']);
}

function broj_punih_meseci(string $start_date, string $end_date): int
{
    $date1 = strtotime($start_date);
    $date2 = strtotime($end_date);
    $months = 0;

    while (($date1 = strtotime('+1 month', $date1)) <= $date2) {
        $months++;
    }

    return $months;
}

function get_radnik_by_username(string $username): ?Radnik
{
    $database = Database::getInstance();
    $users_radnici_mapping = (array) $database->get()['users_radnici_mapping'];
    $mapping = (int) $users_radnici_mapping[$username];
    if (!$mapping) {
        return null;
    }
    return new Radnik($mapping);
}

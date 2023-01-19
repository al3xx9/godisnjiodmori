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

<?php
function check_is_logged_in(): void
{
    global $www;
    if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
        header('Location: ' . $www . 'login.php', true, 302);
        exit;
    }
}

function login_user(int $user_id): void
{
    $_SESSION['user_id'] = $user_id;
}


function logout_user(): void
{
    unset($_SESSION['user_id']);
}

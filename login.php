<?php
require 'config.php';

if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    logout_user();
    redirect('login.php');
}

$error = false;

$database = Database::getInstance();

$users = (array) $database->get()['users'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (
        isset($users[$username]) && !empty($users[$username]) &&
        password_verify($password, (string) $users[$username])
    ) {
        login_user($username);
        redirect('index.php');
    } else {
        $error = true;
    }
}
?>
<h1>Login</h1>
<?php if ($error) { ?>
    <p style="color:red">Pogresno korisnicko ime i/ili lozinka</p>
<?php } ?>
<form method="post" action="<?php echo htmlspecialchars($www); ?>login.php">
    <b>Username: </b><br />
    <input type="text" name="username" value="" />
    <br />
    <b>Password: </b><br />
    <input type="password" name="password" value="" />
    <br />
    <button type="submit">Login</button>
</form>
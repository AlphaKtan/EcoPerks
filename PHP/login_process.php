
<?php
session_start();

$providedPassword = isset($_POST['password']) ? $_POST['password'] : '';


$correctPassword = 'eco2024';

if ($providedPassword === $correctPassword) {
    $_SESSION['authenticated'] = true;
    header('Location: admin.php');
    exit;
} else {
    header('Location: auth.php?error=1');
    exit;
}




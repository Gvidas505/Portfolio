<?php
session_start();
require_once 'includes/DatabaseConnection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['Email'] ?? '');
    $password = $_POST['Password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter both email and password.';
    } else {
        $stmt = $pdo->prepare("SELECT UserID, Name, Email, PasswordHash FROM user WHERE Email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['PasswordHash'])) {
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['user_name'] = $user['Name'];
            $_SESSION['user_email'] = $user['Email'];

            /* Success popup message */
            $_SESSION['success_message'] = 'Welcome, ' . $user['Name'] . '!';

            if ($user['Email'] === 'admin@gmail.com') {
                header('Location: admin/home.php');
                exit;
            }

            header('Location: home.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

$title = 'Login';
$stylesheets = ['css/style.css'];

ob_start();
include 'templates/login.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
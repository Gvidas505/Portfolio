<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Form State
$error = '';
$success = '';

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['Name'] ?? '');
    $email = trim($_POST['Email'] ?? '');
    $password = $_POST['Password'] ?? '';
    $confirmPassword = $_POST['ConfirmPassword'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        // check existing email
        $stmt = $pdo->prepare("SELECT UserID FROM user WHERE Email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $error = 'An account with that email already exists.';
        } else {
            // hash password and insert user
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO user (Name, Email, PasswordHash)
                VALUES (:name, :email, :hash)
            ");

            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':hash' => $hash
            ]);

            header('Location: login.php');
            exit;
        }
    }
}


$title = 'Register';
$stylesheets = ['css/style.css'];


ob_start();
include 'templates/register.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
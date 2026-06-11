<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$title = 'Contact Us';
$stylesheets = ['css/style.css'];


$success = '';
$error = '';

$name = '';
$email = '';
$message = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['Name'] ?? '');
    $email = trim($_POST['Email'] ?? '');
    $message = trim($_POST['Message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // insert message
        $stmt = $pdo->prepare("
            INSERT INTO ContactMessage (Name, Email, Message, CreatedAt)
            VALUES (:name, :email, :message, NOW())
        ");

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message
        ]);

        $success = 'Your message has been sent successfully.';

        // clear form
        $name = '';
        $email = '';
        $message = '';
    }
}

ob_start();
include 'templates/contact.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
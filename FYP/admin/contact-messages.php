<?php
session_start();
require_once '../includes/DatabaseConnection.php';

// check user is logged in
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// check user is admin
if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header('Location: ../home.php');
    exit;
}

// fetch all contact messages
$stmt = $pdo->query("
    SELECT ContactID, Name, Email, Message, CreatedAt, ReplyMessage, ReplyAt
    FROM ContactMessage
    ORDER BY CreatedAt DESC
");

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$title = 'Contact Messages';
$stylesheets = ['../css/style.css'];

ob_start();
include '../templates/contact-messages.html.php';
$output = ob_get_clean();

include '../templates/layout.html.php';
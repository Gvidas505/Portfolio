<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get User Email
$userEmail = $_SESSION['user_email'] ?? null;

if (!$userEmail) {
    die('You must be logged in.');
}

// Fetch Messages
$stmt = $pdo->prepare("
    SELECT ContactID, Name, Email, Message, CreatedAt, ReplyMessage, ReplyAt
    FROM ContactMessage
    WHERE Email = :email
    ORDER BY CreatedAt DESC
");

$stmt->execute([':email' => $userEmail]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);


$title = 'My Messages';
$stylesheets = ['css/style.css'];


ob_start();
include 'templates/my-messages.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Input & Validation
$userEmail = $_SESSION['user_email'] ?? null;
$contactId = filter_input(INPUT_POST, 'ContactID', FILTER_VALIDATE_INT);

if (!$userEmail || !$contactId) {
    header('Location: my-messages.php');
    exit;
}

// Delete Message
$stmt = $pdo->prepare("
    DELETE FROM ContactMessage
    WHERE ContactID = :contactId
      AND Email = :email
    LIMIT 1
");

$stmt->execute([
    ':contactId' => $contactId,
    ':email' => $userEmail
]);

header('Location: my-messages.php'); 
exit;
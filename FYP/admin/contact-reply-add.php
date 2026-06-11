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

// get form data
$contactId = filter_input(INPUT_POST, 'ContactID', FILTER_VALIDATE_INT);
$replyMessage = trim($_POST['ReplyMessage'] ?? '');

// validate input
if (!$contactId || $replyMessage === '') {
    die('Invalid form submission.');
}

// update reply message
$stmt = $pdo->prepare("
    UPDATE ContactMessage
    SET ReplyMessage = :replyMessage,
        ReplyAt = NOW()
    WHERE ContactID = :contactId
");

$stmt->execute([
    ':replyMessage' => $replyMessage,
    ':contactId' => $contactId
]);

header('Location: contact-messages.php#message-' . $contactId);
exit;
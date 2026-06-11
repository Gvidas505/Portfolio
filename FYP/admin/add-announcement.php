<?php
session_start();
require_once '../includes/DatabaseConnection.php';

// Auth & Admin Check
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header('Location: ../home.php');
    exit;
}

// Input & Validation
$moduleId = filter_input(INPUT_POST, 'ModuleID', FILTER_VALIDATE_INT);
$title = trim($_POST['Title'] ?? '');
$message = trim($_POST['Message'] ?? '');

if (!$moduleId || $title === '' || $message === '') {
    header('Location: announcements-admin.php');
    exit;
}

// Insert Announcement
$stmt = $pdo->prepare("
    INSERT INTO announcement (ModuleID, Title, Message)
    VALUES (:moduleId, :title, :message)
");
$stmt->execute([
    ':moduleId' => $moduleId,
    ':title' => $title,
    ':message' => $message
]);

header('Location: announcements-admin.php');
exit;
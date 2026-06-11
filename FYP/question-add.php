<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Input & Validation
$userId = $_SESSION['user_id'] ?? null;
$moduleId = filter_input(INPUT_POST, 'ModuleID', FILTER_VALIDATE_INT);
$query = trim($_POST['Query'] ?? '');

if (!$userId) {
    die('You must be logged in to post a question.');
}

if (!$moduleId || $query === '') {
    die('Invalid form submission');
}

// Insert Question
$stmt = $pdo->prepare("
    INSERT INTO Question (Query, UserID, ModuleID, CreatedAt)
    VALUES (:q, :u, :m, NOW())
");

$stmt->execute([
    ':q' => $query,
    ':u' => $userId,
    ':m' => $moduleId,
]);

header('Location: questions.php'); 
exit;
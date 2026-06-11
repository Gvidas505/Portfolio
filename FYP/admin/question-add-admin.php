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
$userId = $_SESSION['user_id'];
$moduleId = filter_input(INPUT_POST, 'ModuleID', FILTER_VALIDATE_INT);
$query = trim($_POST['Query'] ?? '');

// validate input
if (!$moduleId || $query === '') {
    die('Invalid form submission');
}

// insert question
$stmt = $pdo->prepare("
    INSERT INTO Question (Query, UserID, ModuleID, CreatedAt)
    VALUES (:q, :u, :m, NOW())
");

$stmt->execute([
    ':q' => $query,
    ':u' => $userId,
    ':m' => $moduleId,
]);

header('Location: questions-admin.php');
exit;
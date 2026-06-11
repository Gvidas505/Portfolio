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
$userId = $_SESSION['user_id'] ?? null;
$questionId = filter_input(INPUT_POST, 'QuestionID', FILTER_VALIDATE_INT);
$body = trim($_POST['Body'] ?? '');

// check user ID exists
if (!$userId) {
    die('You must be logged in to post an answer.');
}

// validate input
if (!$questionId || $body === '') {
    die('Invalid form submission');
}

// insert answer
$stmt = $pdo->prepare("
    INSERT INTO Answer (QuestionID, UserID, Body, CreatedAt)
    VALUES (:questionId, :userId, :body, NOW())
");

$stmt->execute([
    ':questionId' => $questionId,
    ':userId' => $userId,
    ':body' => $body
]);

header('Location: question-admin.php?id=' . $questionId);
exit;
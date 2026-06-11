<?php
session_start();
require_once 'includes/DatabaseConnection.php';

$qid = filter_input(INPUT_POST, 'QuestionID', FILTER_VALIDATE_INT);
$userId = $_SESSION['user_id'] ?? null;
$body = trim($_POST['Body'] ?? '');

if (!$qid || !$userId || $body === '') {
    die('Invalid form submission');
}

$stmt = $pdo->prepare("
    INSERT INTO Answer (QuestionID, UserID, Body, CreatedAt)
    VALUES (:q, :u, :b, NOW())
");

$stmt->execute([
    ':q' => $qid,
    ':u' => $userId,
    ':b' => $body,
]);

header('Location: question.php?id=' . $qid);
exit;
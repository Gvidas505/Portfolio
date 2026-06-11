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
$answerId = filter_input(INPUT_POST, 'AnswerID', FILTER_VALIDATE_INT);
$questionId = filter_input(INPUT_POST, 'QuestionID', FILTER_VALIDATE_INT);

// validate input
if (!$answerId || !$questionId) {
    die('Invalid request.');
}

// delete answer
$stmt = $pdo->prepare("DELETE FROM Answer WHERE AnswerID = :answerId");
$stmt->execute([':answerId' => $answerId]);


header('Location: question-admin.php?id=' . $questionId);
exit;
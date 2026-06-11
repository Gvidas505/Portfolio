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

// get question id
$questionId = filter_input(INPUT_POST, 'QuestionID', FILTER_VALIDATE_INT);

// validate id
if (!$questionId) {
    die('Invalid question id.');
}

// delete related answers
$stmt = $pdo->prepare("DELETE FROM Answer WHERE QuestionID = :questionId");
$stmt->execute([':questionId' => $questionId]);

// delete question
$stmt = $pdo->prepare("DELETE FROM Question WHERE QuestionID = :questionId");
$stmt->execute([':questionId' => $questionId]);

header('Location: questions-admin.php');
exit;
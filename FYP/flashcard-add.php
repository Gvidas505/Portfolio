<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Input & Validation
$userId = $_SESSION['user_id'] ?? null;
$moduleId = filter_input(INPUT_POST, 'ModuleID', FILTER_VALIDATE_INT);
$front = trim($_POST['Front'] ?? '');
$back = trim($_POST['Back'] ?? '');

if (!$userId) {
    die('You must be logged in to create flashcards.');
}

if (!$moduleId || $front === '' || $back === '') {
    die('Invalid form submission');
}

// Insert Flashcard 
$stmt = $pdo->prepare("
    INSERT INTO StudentFlashcard (UserID, ModuleID, Front, Back, CreatedAt)
    VALUES (:userId, :moduleId, :front, :back, NOW())
");

$stmt->execute([
    ':userId' => $userId,
    ':moduleId' => $moduleId,
    ':front' => $front,
    ':back' => $back,
]);

header('Location: student-flashcards.php'); 
exit;
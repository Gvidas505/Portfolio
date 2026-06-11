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
$flashcardId = filter_input(INPUT_POST, 'StudentFlashcardID', FILTER_VALIDATE_INT);

if (!$userId) {
    die('You must be logged in to delete flashcards.');
}

if (!$flashcardId) {
    die('Invalid flashcard id.');
}

// Delete Flashcard
$stmt = $pdo->prepare("
    DELETE FROM StudentFlashcard
    WHERE StudentFlashcardID = :flashcardId
      AND UserID = :userId
");

$stmt->execute([
    ':flashcardId' => $flashcardId,
    ':userId' => $userId
]);

header('Location: student-flashcards.php');
exit;
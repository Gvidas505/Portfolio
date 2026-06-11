<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    die('You must be logged in to view flashcards.');
}

// Fetch Flashcards
$stmt = $pdo->prepare("
    SELECT sf.StudentFlashcardID, sf.Front, sf.Back, sf.CreatedAt, m.ModuleName
    FROM StudentFlashcard sf
    JOIN Module m ON sf.ModuleID = m.ModuleID
    WHERE sf.UserID = :userId
    ORDER BY sf.CreatedAt DESC
");
$stmt->execute([':userId' => $userId]);
$flashcards = $stmt->fetchAll();

$title = 'My Flashcards';
$stylesheets = ['css/style.css'];

ob_start();
include 'templates/student-flashcards.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
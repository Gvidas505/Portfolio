<?php
session_start();
require_once 'includes/DatabaseConnection.php';
require_once 'includes/flashcard-functions.php';

// Auth Check 
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get Source
$sourceId = filter_input(INPUT_GET, 'source_id', FILTER_VALIDATE_INT);
if (!$sourceId) {
    die('Invalid source id');
}

$source = getSourceSummary($pdo, $sourceId);
if (!$source) {
    die('Source not found');
}

$title = 'Flashcards';
$stylesheets = ['css/style.css'];

// Generate Flashcards
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'generate') {
    $pdo->prepare("
        DELETE FROM flashcard
        WHERE SourceID = :id AND GeneratedBy = 'RULES'
    ")->execute([':id' => $sourceId]);

    generateFlashcardsForSource($pdo, $sourceId);

    header('Location: flashcards.php?source_id=' . $sourceId);
    exit;
}


$flashcards = getFlashcardsForSource($pdo, $sourceId);


$currentUserId = 1; 

$stmt = $pdo->prepare("
    SELECT ItemID
    FROM saveditem
    WHERE UserID = :uid AND ItemType = 'flashcard'
");
$stmt->execute([':uid' => $currentUserId]);

$savedFlashcardIds = array_fill_keys($stmt->fetchAll(PDO::FETCH_COLUMN), true);

ob_start();
include 'templates/flashcards.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
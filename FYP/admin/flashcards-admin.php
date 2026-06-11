<?php
session_start();
require_once '../includes/DatabaseConnection.php';
require_once '../includes/flashcard-functions.php';

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

// get source id
$sourceId = filter_input(INPUT_GET, 'source_id', FILTER_VALIDATE_INT);
if (!$sourceId) {
    die('Invalid source id');
}

// get source details
$source = getSourceSummary($pdo, $sourceId);
if (!$source) {
    die('Source not found');
}

// page settings
$title = 'Admin Flashcards';
$stylesheets = ['../css/style.css'];

// handle flashcard generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'generate') {
    $pdo->prepare("
        DELETE FROM flashcard
        WHERE SourceID = :id AND GeneratedBy = 'RULES'
    ")->execute([':id' => $sourceId]);

    generateFlashcardsForSource($pdo, $sourceId);

    header('Location: flashcards-admin.php?source_id=' . $sourceId);
    exit;
}

// get flashcards for source
$flashcards = getFlashcardsForSource($pdo, $sourceId);

// temp user id
$currentUserId = 1;

// build set of saved flashcard ids
$stmt = $pdo->prepare("
    SELECT ItemID
    FROM saveditem
    WHERE UserID = :uid AND ItemType = 'flashcard'
");
$stmt->execute([':uid' => $currentUserId]);
$savedFlashcardIds = array_fill_keys($stmt->fetchAll(PDO::FETCH_COLUMN), true);

ob_start();
include '../templates/flashcards-admin.html.php';
$output = ob_get_clean();

include '../templates/layout.html.php';
<?php
require_once 'includes/DatabaseConnection.php';

// Input Validation
$flashcardId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$sourceId    = filter_input(INPUT_GET, 'source_id', FILTER_VALIDATE_INT);

if (!$flashcardId || !$sourceId) {
    die('Invalid request');
}

// TEMP User
$currentUserId = 1;

// Remove Bookmark
$stmt = $pdo->prepare("
    DELETE FROM saveditem
    WHERE UserID = :uid AND ItemType = 'flashcard' AND ItemID = :fid
");
$stmt->execute([
    ':uid' => $currentUserId,
    ':fid' => $flashcardId
]);

header('Location: flashcards.php?source_id=' . $sourceId); 
exit;
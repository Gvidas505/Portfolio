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

// Ensure Item Exists
$itemSql = "
INSERT INTO item (ItemID, ItemType)
VALUES (:itemId, 'flashcard')
ON DUPLICATE KEY UPDATE ItemType = ItemType
";
$itemStmt = $pdo->prepare($itemSql);
$itemStmt->execute([
    ':itemId' => $flashcardId
]);

// Save Bookmark
$sql = "
INSERT INTO saveditem (UserID, ItemType, ItemID)
VALUES (:uid, 'flashcard', :fid)
ON DUPLICATE KEY UPDATE CreatedAt = CreatedAt
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':uid' => $currentUserId,
    ':fid' => $flashcardId
]);

header('Location: flashcards.php?source_id=' . $sourceId); 
exit;
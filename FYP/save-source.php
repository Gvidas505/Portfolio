<?php
require_once 'includes/DatabaseConnection.php';

// Input Validation
$sourceId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$sourceId) die('Invalid source id');

// TEMP User
$currentUserId = 1;

// Ensure Item Exists
$itemSql = "
INSERT INTO item (ItemID, ItemType)
VALUES (:itemId, 'source')
ON DUPLICATE KEY UPDATE ItemType = ItemType
";
$itemStmt = $pdo->prepare($itemSql);
$itemStmt->execute([
    ':itemId' => $sourceId
]);

// Save Bookmark
$sql = "
INSERT INTO saveditem (UserID, ItemType, ItemID)
VALUES (:uid, 'source', :sid)
ON DUPLICATE KEY UPDATE CreatedAt = CreatedAt
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':uid' => $currentUserId,
    ':sid' => $sourceId
]);

header('Location: source.php?id=' . $sourceId); 
exit;
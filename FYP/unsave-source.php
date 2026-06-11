<?php
require_once 'includes/DatabaseConnection.php';

// Input Validation
$sourceId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$sourceId) die('Invalid source id');

// TEMP User
$currentUserId = 1;

// Remove Bookmark
$stmt = $pdo->prepare("
    DELETE FROM saveditem
    WHERE UserID = :uid AND ItemType = 'source' AND ItemID = :sid
");
$stmt->execute([
    ':uid' => $currentUserId,
    ':sid' => $sourceId
]);

header('Location: source.php?id=' . $sourceId);
exit;
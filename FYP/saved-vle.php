<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$title = 'Saved';
$stylesheets = ['css/style.css'];

// TEMP User
$currentUserId = 1;

// Fetch Saved Sources
$sqlSources = "
SELECT 
  s.SourceID, s.Title, s.Year, s.Venue,
  GROUP_CONCAT(DISTINCT a.FullName ORDER BY sa.AuthorOrder SEPARATOR ', ') AS Authors
FROM saveditem si
JOIN source s ON s.SourceID = si.ItemID
LEFT JOIN sourceauthor sa ON sa.SourceID = s.SourceID
LEFT JOIN author a ON a.AuthorID = sa.AuthorID
WHERE si.UserID = :uid AND si.ItemType = 'source'
GROUP BY s.SourceID
ORDER BY si.CreatedAt DESC;
";
$stmt = $pdo->prepare($sqlSources);
$stmt->execute([':uid' => $currentUserId]);
$savedSources = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Saved Flashcards
$sqlFlashcards = "
SELECT 
  f.FlashcardID, f.Front, f.Back, f.SourceID,
  s.Title AS SourceTitle
FROM saveditem si
JOIN flashcard f ON f.FlashcardID = si.ItemID
LEFT JOIN source s ON s.SourceID = f.SourceID
WHERE si.UserID = :uid AND si.ItemType = 'flashcard'
ORDER BY si.CreatedAt DESC;
";
$stmt = $pdo->prepare($sqlFlashcards);
$stmt->execute([':uid' => $currentUserId]);
$savedFlashcards = $stmt->fetchAll(PDO::FETCH_ASSOC);


ob_start();
include 'templates/saved-vle.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
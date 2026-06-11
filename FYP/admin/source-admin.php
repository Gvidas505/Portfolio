<?php
require_once '../includes/DatabaseConnection.php';

// get source id
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('Invalid source id');
}

// temp user id 
$currentUserId = 1;

// get source with authors and keywords
$sql = "
SELECT 
  s.SourceID, s.Title, s.Year, s.Venue, s.Publisher, s.Abstract, s.URL, s.PDFUrl,
  GROUP_CONCAT(DISTINCT a.FullName ORDER BY sa.AuthorOrder SEPARATOR ', ') AS Authors,
  GROUP_CONCAT(DISTINCT k.Word ORDER BY k.Word SEPARATOR ', ') AS Keywords
FROM Source s
LEFT JOIN SourceAuthor sa ON sa.SourceID = s.SourceID
LEFT JOIN Author a ON a.AuthorID = sa.AuthorID
LEFT JOIN SourceKeyword sk ON sk.SourceID = s.SourceID
LEFT JOIN Keyword k ON k.KeywordID = sk.KeywordID
WHERE s.SourceID = :id
GROUP BY s.SourceID
LIMIT 1;
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$source = $stmt->fetch(PDO::FETCH_ASSOC);

// check source exists
if (!$source) {
    die('Source not found');
}

// check if source is saved by current user
$stmt = $pdo->prepare("
  SELECT 1
  FROM saveditem
  WHERE UserID = :uid
    AND ItemType = 'source'
    AND ItemID = :sid
  LIMIT 1
");
$stmt->execute([
    ':uid' => $currentUserId,
    ':sid' => (int)$source['SourceID']
]);
$isSavedSource = (bool)$stmt->fetchColumn();

$title = $source['Title'] ?? 'Source';
$stylesheets = ['/FYP/css/style.css']; // popup styling

ob_start();
include '../templates/source-view-admin.html.php';
$output = ob_get_clean();

include '../templates/layout.html.php';
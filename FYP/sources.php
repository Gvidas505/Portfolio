<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$title = 'Sources';
$stylesheets = [
    'css/style.css'
];

// Fetch Sources
$sql = "
SELECT 
  s.SourceID,
  s.Title,
  s.Year,
  s.Venue,
  s.Abstract,
  s.PDFUrl,
  GROUP_CONCAT(DISTINCT a.FullName ORDER BY sa.AuthorOrder SEPARATOR ', ') AS Authors,
  GROUP_CONCAT(DISTINCT k.Word ORDER BY k.Word SEPARATOR ', ') AS Keywords
FROM Source s
LEFT JOIN SourceAuthor sa ON sa.SourceID = s.SourceID
LEFT JOIN Author a ON a.AuthorID = sa.AuthorID
LEFT JOIN SourceKeyword sk ON sk.SourceID = s.SourceID
LEFT JOIN Keyword k ON k.KeywordID = sk.KeywordID
GROUP BY s.SourceID
ORDER BY s.SourceID DESC;
";

$stmt = $pdo->query($sql);
$sources = $stmt->fetchAll(PDO::FETCH_ASSOC);


ob_start();
include 'templates/sources.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$title = 'Question';
$stylesheets = ['css/style.css'];

// Get Question ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('Invalid question id');
}

// Fetch Question
$qSql = "
SELECT
  q.QuestionID,
  q.Query,
  q.CreatedAt,
  u.Name AS UserName,
  m.ModuleName
FROM Question q
LEFT JOIN User u ON u.UserID = q.UserID
LEFT JOIN Module m ON m.ModuleID = q.ModuleID
WHERE q.QuestionID = :id
LIMIT 1;
";

$qStmt = $pdo->prepare($qSql);
$qStmt->execute([':id' => $id]);
$question = $qStmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    die('Question not found');
}

// Fetch Answers
$aSql = "
SELECT
  a.AnswerID,
  a.Body,
  a.CreatedAt,
  u.Name AS UserName
FROM Answer a
LEFT JOIN User u ON u.UserID = a.UserID
WHERE a.QuestionID = :id
ORDER BY a.AnswerID ASC;
";

$aStmt = $pdo->prepare($aSql);
$aStmt->execute([':id' => $id]);
$answers = $aStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Users
$uStmt = $pdo->query("SELECT UserID, Name FROM User ORDER BY Name ASC");
$users = $uStmt->fetchAll(PDO::FETCH_ASSOC);


ob_start();
include 'templates/question-view.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
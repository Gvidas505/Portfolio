<?php
session_start();
require_once '../includes/DatabaseConnection.php';

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

$title = 'Admin Questions';
$stylesheets = ['../css/style.css'];

// get all questions
$sql = "
SELECT
  q.QuestionID,
  q.Query,
  q.CreatedAt,
  u.Name AS UserName,
  m.ModuleName,
  (SELECT COUNT(*) FROM Answer a WHERE a.QuestionID = q.QuestionID) AS AnswerCount
FROM Question q
LEFT JOIN User u ON u.UserID = q.UserID
LEFT JOIN Module m ON m.ModuleID = q.ModuleID
ORDER BY q.QuestionID DESC
";

$stmt = $pdo->query($sql);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
include '../templates/questions-admin.html.php';
$output = ob_get_clean();

include '../templates/layout.html.php';
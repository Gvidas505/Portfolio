<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$title = 'Search';
$stylesheets = ['css/style.css'];

// Search State
$q = trim($_GET['q'] ?? '');
$results = [
    'sources' => [],
    'flashcards' => [],
    'questions' => []
];

// Helper Function
function tableExists(PDO $pdo, string $table): bool {
    $stmt = $pdo->prepare("SHOW TABLES LIKE :t");
    $stmt->execute([':t' => $table]);
    return (bool)$stmt->fetchColumn();
}

// Run Search
if ($q !== '') {
    $like = '%' . $q . '%';

    // search sources
    if (tableExists($pdo, 'Source')) {
        $sql = "
            SELECT SourceID, Title, Year, Venue
            FROM Source
            WHERE Title LIKE :q OR Abstract LIKE :q OR DOI LIKE :q
            ORDER BY SourceID DESC
            LIMIT 20;
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':q' => $like]);
        $results['sources'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // search flashcards
    if (tableExists($pdo, 'Flashcard')) {
        $sql = "
            SELECT FlashcardID, Front, Back
            FROM Flashcard
            WHERE Front LIKE :q OR Back LIKE :q
            ORDER BY FlashcardID DESC
            LIMIT 20;
        ";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':q' => $like]);
            $results['flashcards'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $results['flashcards'] = [];
        }
    }

    // search questions
    if (tableExists($pdo, 'Question')) {
        $sql = "
            SELECT QuestionID, Query, CreatedAt
            FROM Question
            WHERE Query LIKE :q
            ORDER BY QuestionID DESC
            LIMIT 20;
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':q' => $like]);
        $results['questions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

ob_start();
include 'templates/search.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
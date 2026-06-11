<?php
session_start();
require_once 'includes/DatabaseConnection.php';
require_once 'includes/home-functions.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$title = 'Home';
$stylesheets = [
    'css/style.css',
];


$stats = [
    'sources'    => getCount($pdo, 'Source'),
    'flashcards' => getCount($pdo, 'Flashcard'),
    'questions'  => getCount($pdo, 'Question'),
    'modules'    => getCount($pdo, 'Module'),
];

// Recent Data 
$recentSources = getRecent($pdo, 'Source', 'SourceID', ['SourceID', 'Title'], 5);
$recentQuestions = getRecent($pdo, 'Question', 'QuestionID', ['QuestionID', 'Query'], 5);

ob_start();

// Welcome Popup 
if (!empty($_SESSION['success_message'])) : ?>
    <script>
        alert("<?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES) ?>");
    </script>
<?php
    unset($_SESSION['success_message']);
endif;


include 'templates/db-status.html.php';
include 'templates/home.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
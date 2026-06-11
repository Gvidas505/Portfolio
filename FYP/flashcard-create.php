<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check 
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    die('You must be logged in to create flashcards.');
}

// Fetch Modules
$stmt = $pdo->query("SELECT ModuleID, ModuleName FROM Module ORDER BY ModuleName");
$modules = $stmt->fetchAll();

$title = 'Create Flashcards';
$stylesheets = ['css/style.css'];

ob_start();
include 'templates/flashcard-create.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
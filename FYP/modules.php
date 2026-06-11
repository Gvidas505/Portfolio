<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$title = 'Modules';
$stylesheets = ['css/style.css'];

// Fetch Modules
$stmt = $pdo->query("
    SELECT ModuleID, ModuleName, Code
    FROM module
    ORDER BY ModuleName ASC
");

$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);


ob_start();
include 'templates/modules.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
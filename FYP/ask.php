<?php
require_once 'includes/DatabaseConnection.php';


$title = 'Ask a Question';
$stylesheets = ['css/style.css'];

// Fetch Modules
$modules = $pdo->query("
    SELECT ModuleID, ModuleName
    FROM Module
    ORDER BY ModuleName ASC
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
include 'templates/ask.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
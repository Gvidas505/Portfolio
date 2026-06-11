<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$title = 'VLE';
$stylesheets = ['css/style.css'];

// Fetch Modules
$modulesStmt = $pdo->query("
    SELECT ModuleID, ModuleName, Code
    FROM module
    ORDER BY ModuleName ASC
");
$modules = $modulesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Announcements
$annStmt = $pdo->query("
    SELECT a.AnnouncementID, a.Title, a.Message, a.CreatedAt, m.ModuleName
    FROM announcement a
    LEFT JOIN module m ON m.ModuleID = a.ModuleID
    ORDER BY a.CreatedAt DESC
    LIMIT 5
");
$announcements = $annStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Materials
$matStmt = $pdo->query("
    SELECT mat.MaterialID, mat.Title, mat.Type, mat.FilePath, m.ModuleName
    FROM material mat
    LEFT JOIN module m ON m.ModuleID = mat.ModuleID
    ORDER BY mat.CreatedAt DESC
    LIMIT 6
");
$materials = $matStmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
include 'templates/vle.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
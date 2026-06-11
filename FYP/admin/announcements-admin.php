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

// page settings
$title = 'Manage Announcements';
$stylesheets = ['/FYP/css/style.css'];

// get modules for the form
$modulesStmt = $pdo->query("
    SELECT ModuleID, ModuleName, Code
    FROM module
    ORDER BY ModuleName ASC
");
$modules = $modulesStmt->fetchAll(PDO::FETCH_ASSOC);

// get all announcements
$annStmt = $pdo->query("
    SELECT a.AnnouncementID, a.Title, a.Message, a.CreatedAt, a.ModuleID, m.ModuleName
    FROM announcement a
    LEFT JOIN module m ON m.ModuleID = a.ModuleID
    ORDER BY a.CreatedAt DESC
");
$announcements = $annStmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
include '../templates/announcements-admin.html.php';
$output = ob_get_clean();

include '../templates/layout.html.php';
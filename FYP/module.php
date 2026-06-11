<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get Module ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die('Invalid module id');
}

// Fetch Module
$stmt = $pdo->prepare("
    SELECT ModuleID, ModuleName, Code
    FROM module
    WHERE ModuleID = :id
");
$stmt->execute([':id' => $id]);
$module = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$module) {
    die('Module not found');
}

// Fetch Materials
$stmt = $pdo->prepare("
    SELECT Title, Type, FilePath, CreatedAt
    FROM material
    WHERE ModuleID = :id
    ORDER BY CreatedAt DESC
");

$stmt->execute([':id' => $id]);
$materials = $stmt->fetchAll(PDO::FETCH_ASSOC);


$title = $module['ModuleName'];
$stylesheets = ['css/style.css'];


ob_start();
include 'templates/module.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';
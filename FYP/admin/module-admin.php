<?php
session_start();
require_once '../includes/DatabaseConnection.php';

// check user is logged in
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// get module id
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die('Invalid module id');
}

// get module
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

// get materials
$stmt = $pdo->prepare("
    SELECT MaterialID, Title, Type, FilePath, CreatedAt
    FROM material
    WHERE ModuleID = :id
    ORDER BY CreatedAt DESC
");

$stmt->execute([':id' => $id]);
$materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

$title = $module['ModuleName'];
$stylesheets = ['/FYP/css/style.css'];

ob_start();
include '../templates/module-admin.html.php';
$output = ob_get_clean();

include '../templates/layout.html.php';
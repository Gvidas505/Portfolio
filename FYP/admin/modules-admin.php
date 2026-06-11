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

$title = 'Manage Modules';
$stylesheets = ['/FYP/css/style.css'];

// get all modules
$stmt = $pdo->query("
    SELECT ModuleID, ModuleName, Code
    FROM module
    ORDER BY ModuleName ASC
");

$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
include '../templates/modules-admin.html.php';
$output = ob_get_clean();

include '../templates/layout.html.php';
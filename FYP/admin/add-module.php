<?php
session_start();
require_once '../includes/DatabaseConnection.php';

// check user logged in
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// check admin access
if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header('Location: ../home.php');
    exit;
}

// get form data
$moduleName = trim($_POST['ModuleName'] ?? '');
$code = trim($_POST['Code'] ?? '');

// validate input
if ($moduleName === '' || $code === '') {
    header('Location: modules-admin.php');
    exit;
}

// insert module
$stmt = $pdo->prepare("
    INSERT INTO module (ModuleName, Code)
    VALUES (:name, :code)
");
$stmt->execute([
    ':name' => $moduleName,
    ':code' => $code
]);

header('Location: modules-admin.php');
exit;
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

// get material and module ids
$materialId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$moduleId   = filter_input(INPUT_GET, 'module_id', FILTER_VALIDATE_INT);

// validate ids
if (!$materialId || !$moduleId) {
    header('Location: modules-admin.php');
    exit;
}

// get file path before deleting
$stmt = $pdo->prepare("SELECT FilePath FROM material WHERE MaterialID = :id LIMIT 1");
$stmt->execute([':id' => $materialId]);
$material = $stmt->fetch(PDO::FETCH_ASSOC);

if ($material) {
    $filePath = '../' . $material['FilePath'];

    // delete database record
    $stmt = $pdo->prepare("DELETE FROM material WHERE MaterialID = :id");
    $stmt->execute([':id' => $materialId]);

    // delete file from server if it exists
    if (!empty($material['FilePath']) && file_exists($filePath)) {
        unlink($filePath);
    }
}

header('Location: module-admin.php?id=' . $moduleId);
exit;
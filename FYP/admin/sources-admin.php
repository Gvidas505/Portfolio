<?php
session_start();
require_once '../includes/DatabaseConnection.php';

if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header('Location: ../home.php');
    exit;
}

$title = 'Manage Sources';
$stylesheets = ['/FYP/css/style.css'];

$stmt = $pdo->query("
    SELECT SourceID, Title, Year, Venue, Publisher, DOI, URL, PDFUrl
    FROM source
    ORDER BY SourceID DESC
");
$sources = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Flash messages + old form values */
$formError = $_SESSION['form_error'] ?? '';
$formSuccess = $_SESSION['form_success'] ?? '';
$old = $_SESSION['old_source_form'] ?? [];

unset($_SESSION['form_error'], $_SESSION['form_success'], $_SESSION['old_source_form']);

ob_start();
include '../templates/sources-admin.html.php';
$output = ob_get_clean();

include '../templates/layout.html.php';
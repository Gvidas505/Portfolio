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

// get announcement id
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// validate id
if (!$id) {
    header('Location: announcements-admin.php');
    exit;
}

// delete announcement
$stmt = $pdo->prepare("DELETE FROM announcement WHERE AnnouncementID = :id");
$stmt->execute([':id' => $id]);

header('Location: announcements-admin.php');
exit;
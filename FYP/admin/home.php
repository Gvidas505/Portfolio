<?php
session_start();

// check user is logged in
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// page settings
$title = 'Admin Dashboard';
$stylesheets = ['../css/style.css'];

ob_start();
?>

<div class="container">

<h1>Admin Dashboard</h1>

<p>Welcome Admin.</p>

<div class="actions">

<a href="/FYP/admin/modules-admin.php" class="btn">Manage Modules</a>
<a href="../upload-material.php" class="btn">Upload Materials</a>
<a href="../admin/sources-admin.php" class="btn">Manage Sources</a>
<a href="../admin/questions-admin.php" class="btn">View Questions</a>
<a href="../admin/announcements-admin.php" class="btn">Create Announcement</a>
<a href="../admin/contact-messages.php" class="btn">View Contact Messages</a>
<a href="../home.php" class="btn">Student Hub</a>
<a href="../vle.php" class="btn">VLE</a>
<a href="../logout.php" class="btn">Logout</a>

</div>

</div>

<?php

$output = ob_get_clean();
include '../templates/layout.html.php';
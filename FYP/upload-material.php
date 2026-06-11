<?php
session_start();
require_once 'includes/DatabaseConnection.php';

// Auth Check
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Admin Check
if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header('Location: home.php');
    exit;
}

$title = 'Upload Material';
$stylesheets = ['css/style.css'];

$error = '';
$success = '';

// Fetch Modules
$stmt = $pdo->query("SELECT ModuleID, ModuleName, Code FROM module ORDER BY ModuleName ASC");
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduleId = filter_input(INPUT_POST, 'ModuleID', FILTER_VALIDATE_INT);
    $materialTitle = trim($_POST['Title'] ?? '');
    $type = trim($_POST['Type'] ?? '');

    if (!$moduleId || $materialTitle === '' || $type === '') {
        $error = 'Please fill in all fields.';
    } elseif (!isset($_FILES['Resource']) || $_FILES['Resource']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please choose a file to upload.';
    } else {
        $file = $_FILES['Resource'];

        $allowedExtensions = ['pdf', 'ppt', 'pptx'];
        $originalName = $file['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            $error = 'Only PDF, PPT, and PPTX files are allowed.';
        } else {
            // create safe file path
            $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            $uploadDir = 'uploads/materials/';
            $targetPath = $uploadDir . $safeName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // save file info
                $stmt = $pdo->prepare("
                    INSERT INTO material (ModuleID, Title, Type, FilePath)
                    VALUES (:moduleId, :title, :type, :filePath)
                ");
                $stmt->execute([
                    ':moduleId' => $moduleId,
                    ':title' => $materialTitle,
                    ':type' => $type,
                    ':filePath' => $targetPath
                ]);

                $success = 'Resource uploaded successfully.';
            } else {
                $error = 'Failed to upload file.';
            }
        }
    }
}


ob_start();
?>

<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Upload Learning Material</h1>
        <p class="subtitle">
            Upload PDFs or PowerPoint files and attach them to a module.
        </p>

        <div class="actions">
            <a href="admin/home.php" class="btn">Back to Admin Dashboard</a>
        </div>
    </header>

    <section class="section upload-wrap">
        <div class="card">
            <?php if (!empty($error)) : ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="form">
                <div class="field">
                    <label class="label" for="ModuleID">Module</label>
                    <select class="select" name="ModuleID" id="ModuleID" required>
                        <option value="">Select a module</option>
                        <?php foreach ($modules as $m) : ?>
                            <option value="<?= (int)$m['ModuleID'] ?>">
                                <?= htmlspecialchars($m['ModuleName']) ?> (<?= htmlspecialchars($m['Code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label class="label" for="Title">Title</label>
                    <input class="input" type="text" name="Title" id="Title" required>
                </div>

                <div class="field">
                    <label class="label" for="Type">Type</label>
                    <select class="select" name="Type" id="Type" required>
                        <option value="">Select type</option>
                        <option value="PDF">PDF</option>
                        <option value="PowerPoint">PowerPoint</option>
                    </select>
                </div>

                <div class="field">
                    <label class="label" for="Resource">File</label>
                    <input class="input file-input" type="file" name="Resource" id="Resource" accept=".pdf,.ppt,.pptx" required>
                    <p class="small">Allowed formats: PDF, PPT, PPTX</p>
                </div>

                <button type="submit" class="btn btn-accent">Upload Resource</button>
            </form>
        </div>
    </section>
</main>

<?php
$output = ob_get_clean();
include 'templates/layout.html.php';
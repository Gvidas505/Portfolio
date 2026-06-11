<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'App') ?></title>

    <?php if (!empty($stylesheets)) : ?>
        <?php foreach ($stylesheets as $css) : ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>

<?= $output ?>

</body>
</html> 

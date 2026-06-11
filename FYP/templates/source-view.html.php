<?php
$websiteUrl = $source['URL'] ?? '';
$pdfUrl = $source['PDFUrl'] ?? '';
$abstract = $source['Abstract'] ?? '';

$isHttpWebsite = preg_match('/^https?:\/\//i', $websiteUrl);
$isHttpPdf = preg_match('/^https?:\/\//i', $pdfUrl);
?>

<div class="modal-backdrop" onclick="window.location.href='sources.php';">
    <div class="modal" onclick="event.stopPropagation();">

        <div class="modal-header">
            <div>
                <h2 class="title title-md"><?= htmlspecialchars($source['Title']) ?></h2>

                <div class="meta" style="margin-top: 6px;">
                    <?= htmlspecialchars($source['Authors'] ?? '') ?>
                    <?php if (!empty($source['Venue']) || !empty($source['Year'])) : ?>
                        — <?= htmlspecialchars($source['Venue'] ?? '') ?>
                        <?= !empty($source['Year']) ? '(' . (int)$source['Year'] . ')' : '' ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($source['Keywords'])) : ?>
                    <div class="meta" style="margin-top: 6px;">
                        <strong>Keywords:</strong> <?= htmlspecialchars($source['Keywords']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <a class="modal-close" href="sources.php" aria-label="Close source">✕</a>
        </div>

        <div class="modal-body">
            <div class="source-actions">
                <?php if ($isHttpWebsite) : ?>
                    <a class="btn btn-sm" href="<?= htmlspecialchars($websiteUrl) ?>" target="_blank" rel="noopener noreferrer">
                        Open website
                    </a>
                <?php endif; ?>

                <?php if ($isHttpPdf) : ?>
                    <a class="btn btn-sm" href="<?= htmlspecialchars($pdfUrl) ?>" target="_blank" rel="noopener noreferrer">
                        Open PDF
                    </a>
                <?php endif; ?>

                <a class="btn btn-sm" href="flashcards.php?source_id=<?= (int)$source['SourceID'] ?>">
                    View Flashcards
                </a>

                <?php if (!empty($isSavedSource)) : ?>
                    <a class="btn btn-outline btn-sm saved-state" href="unsave-source.php?id=<?= (int)$source['SourceID'] ?>">
                        Saved ✅
                    </a>
                <?php else : ?>
                    <a class="btn btn-outline btn-sm" href="save-source.php?id=<?= (int)$source['SourceID'] ?>">
                        Save Source
                    </a>
                <?php endif; ?>
            </div>

            <div class="source-block">
                <h3 class="title title-sm">Abstract</h3>

                <?php if (!empty($abstract)) : ?>
                    <p class="source-text">
                        <?= nl2br(htmlspecialchars($abstract)) ?>
                    </p>
                <?php else : ?>
                    <p class="muted source-text">
                        No abstract saved yet for this source.
                        <?php if ($isHttpWebsite) : ?>
                            You can open the website link above to view it.
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if ($isHttpPdf) : ?>
                <div class="source-block">
                    <h3 class="title title-sm">PDF Preview</h3>
                    <iframe class="embed" src="<?= htmlspecialchars($pdfUrl) ?>" title="PDF viewer"></iframe>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
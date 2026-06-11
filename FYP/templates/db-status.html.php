<?php if (isset($dbMessage)) : ?>
<div
    class="popup <?php echo $dbError ? 'popup-error' : 'popup-success'; ?>"
    onclick="this.style.display='none';"
>
    <?php echo htmlspecialchars($dbMessage); ?>
</div>
<?php endif; ?>

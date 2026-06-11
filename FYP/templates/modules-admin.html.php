<main class="page">

  <div class="section">
    <h1 class="title title-lg">Manage Modules</h1>
    <a href="home.php" class="btn btn-sm">Back to Admin Dashboard</a>
  </div>

  <!-- ADD MODULE -->
  <div class="section card">
    <h2 class="title title-md">Add Module</h2>

    <form method="post" action="add-module.php" class="form">
      
      <div class="field">
        <label class="label" for="ModuleName">Module Name</label>
        <input class="input" type="text" name="ModuleName" id="ModuleName" required>
      </div>

      <div class="field">
        <label class="label" for="Code">Module Code</label>
        <input class="input" type="text" name="Code" id="Code" required>
      </div>

      <button type="submit" class="btn">Add Module</button>
    </form>
  </div>

  <!-- MODULE LIST -->
  <div class="section grid grid-2">

    <?php foreach ($modules as $m) : ?>
      <div class="card">

        <h2 class="title title-sm">
          <?= htmlspecialchars($m['ModuleName']) ?>
        </h2>

        <p class="meta"><?= htmlspecialchars($m['Code']) ?></p>

        <div class="actions">
          <a href="module-admin.php?id=<?= (int)$m['ModuleID'] ?>" class="btn btn-sm">
            View Resources
          </a>

          <a href="delete-module.php?id=<?= (int)$m['ModuleID'] ?>"
             class="btn btn-sm btn-outline"
             onclick="return confirm('Are you sure you want to delete this module?');">
            Delete
          </a>
        </div>

      </div>
    <?php endforeach; ?>

  </div>

</main>
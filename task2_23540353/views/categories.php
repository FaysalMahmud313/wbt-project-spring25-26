<?php  render_header("Categories"); ?>

<h1>Category Management</h1>

<?php if ($message !== ""): ?><div class="alert success"><?= h($message) ?></div><?php endif; ?>
<?php if ($error   !== ""): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>

<form class="card form" method="post" action="<?= url('admin_categories') ?>"
      onsubmit="return validateCategory(this)">
    <input type="hidden" name="action" value="<?= $editing ? 'update' : 'create' ?>">
    <?php if ($editing): ?>
        <input type="hidden" name="id" value="<?= h($editing['id']) ?>">
    <?php endif; ?>
    <h3><?= $editing ? 'Edit Category #' . h($editing['id']) : 'Add Category' ?></h3>
    <div class="form-row">
        <label>Name (genre)</label>
        <input type="text" name="name" required value="<?= h($editing['name'] ?? '') ?>">
    </div>
    <div class="form-row">
        <label>Type</label>
        <select name="category_type">
            <option value="solid"  <?= ($editing['category_type'] ?? '') === 'solid'  ? 'selected' : '' ?>>solid</option>
            <option value="liquid" <?= ($editing['category_type'] ?? '') === 'liquid' ? 'selected' : '' ?>>liquid</option>
        </select>
    </div>
    <div class="form-actions">
        <button class="btn btn-blue"><?= $editing ? 'Update' : 'Add' ?></button>
        <?php if ($editing): ?><a class="btn btn-light" href="<?= url('admin_categories') ?>">Cancel</a><?php endif; ?>
    </div>
</form>

<div class="searchbar card">
    <input type="text" class="table-search" data-table="catTable"
           data-count="catCount" data-noun="category"
           placeholder="Search categories...">
    <span class="muted result-count" id="catCount"></span>
</div>

<table class="table" id="catTable">
    <thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($categories as $c): ?>
        <tr>
            <td><?= h($c['id']) ?></td>
            <td><?= h($c['name']) ?></td>
            <td><span class="tag <?= h($c['category_type']) ?>"><?= h($c['category_type']) ?></span></td>
            <td><?= h($c['created_at']) ?></td>
            <td>
                <a class="btn btn-light btn-sm" href="<?= url('admin_categories', ['action'=>'edit','id'=>$c['id']]) ?>">Edit</a>
                <a class="btn btn-danger btn-sm" href="<?= url('admin_categories', ['action'=>'delete','id'=>$c['id']]) ?>"
                   onclick="return confirm('Delete this category?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script src="<?= asset('task2_23540353/assets/js/task2.js') ?>"></script>
<?php render_footer(); ?>

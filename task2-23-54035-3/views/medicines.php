<?php  render_header("Medicines"); ?>

<h1>Medicine Management</h1>

<?php if ($message !== ""): ?><div class="alert success"><?= h($message) ?></div><?php endif; ?>
<?php if ($error   !== ""): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>

<form class="card form" method="post" action="<?= url('admin_medicines') ?>"
      enctype="multipart/form-data" onsubmit="return validateMedicine(this)">
    <input type="hidden" name="action" value="<?= $editing ? 'update' : 'create' ?>">
    <?php if ($editing): ?>
        <input type="hidden" name="id" value="<?= h($editing['id']) ?>">
    <?php endif; ?>
    <h3><?= $editing ? 'Edit Medicine #' . h($editing['id']) : 'Add Medicine' ?></h3>

    <div class="form-row">
        <label>Name</label>
        <input type="text" name="name" required value="<?= h($editing['name'] ?? '') ?>">
    </div>
    <div class="form-row">
        <label>Category</label>
        <select name="category_id" required>
            <option value="">-- select --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= h($c['id']) ?>"
                    <?= (($editing['category_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                    <?= h($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-row">
        <label>Vendor Name</label>
        <input type="text" name="vendor_name" required value="<?= h($editing['vendor_name'] ?? '') ?>">
    </div>
    <div class="form-row">
        <label>Price</label>
        <input type="number" step="0.01" min="0.01" name="price" required
               value="<?= h($editing['price'] ?? '') ?>">
    </div>
    <div class="form-row">
        <label>Availability (stock)</label>
        <input type="number" min="0" name="availability" required
               value="<?= h($editing['availability'] ?? '0') ?>">
    </div>
    <div class="form-row">
        <label>Description</label>
        <textarea name="description" rows="3"><?= h($editing['description'] ?? '') ?></textarea>
    </div>
    <div class="form-row">
        <label>Image (JPEG/PNG, max 2MB)</label>
        <input type="file" name="image" accept="image/jpeg,image/png">
        <?php if (!empty($editing['image_path'])): ?>
            <img class="thumb" src="<?= asset($editing['image_path']) ?>" alt="">
        <?php endif; ?>
    </div>
    <div class="form-actions">
        <button class="btn btn-blue"><?= $editing ? 'Update' : 'Add' ?></button>
        <?php if ($editing): ?><a class="btn btn-light" href="<?= url('admin_medicines') ?>">Cancel</a><?php endif; ?>
    </div>
</form>

<div class="searchbar card">
    <input type="text" class="table-search" data-table="medTable"
           data-count="medCount" data-noun="medicine"
           placeholder="Search medicines by name, category, vendor...">
    <span class="muted result-count" id="medCount"></span>
</div>

<table class="table" id="medTable">
    <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Vendor</th>
        <th>Price</th><th>Stock</th><th>Image</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($medicines as $m): ?>
        <tr>
            <td><?= h($m['id']) ?></td>
            <td><?= h($m['name']) ?></td>
            <td><?= h($m['category_name']) ?></td>
            <td><?= h($m['vendor_name']) ?></td>
            <td>&#2547;<?= h(number_format($m['price'], 2)) ?></td>
            <td><?= h($m['availability']) ?></td>
            <td>
                <?php if (!empty($m['image_path'])): ?>
                    <img class="thumb" src="<?= asset($m['image_path']) ?>" alt="">
                <?php else: ?> &mdash; <?php endif; ?>
            </td>
            <td>
                <a class="btn btn-light btn-sm" href="<?= url('admin_medicines', ['action'=>'edit','id'=>$m['id']]) ?>">Edit</a>
                <a class="btn btn-danger btn-sm" href="<?= url('admin_medicines', ['action'=>'delete','id'=>$m['id']]) ?>"
                   onclick="return confirm('Delete this medicine?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script src="<?= asset('task2_23540353/assets/js/task2.js') ?>"></script>
<?php render_footer(); ?>

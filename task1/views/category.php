<?php /* Task 1 view: medicines under one category, with liquid/solid filter */ render_header("Category"); ?>

<h1><?= $category ? h($category['name']) : 'Category' ?></h1>

<div class="card filterbar">
    <span>Filter:</span>
    <a class="btn btn-light" href="<?= url('category', ['id' => $categoryId]) ?>">All</a>
    <a class="btn btn-light" href="<?= url('category', ['id' => $categoryId, 'type' => 'liquid']) ?>">Liquid</a>
    <a class="btn btn-light" href="<?= url('category', ['id' => $categoryId, 'type' => 'solid']) ?>">Solid</a>
    <input type="text" class="card-search" data-grid="catGrid" data-count="catCount"
           placeholder="Search in this category...">
</div>

<p class="muted result-count" id="catCount">
    <?= count($medicines) ?> medicine<?= count($medicines) === 1 ? '' : 's' ?> found
</p>

<div class="grid" id="catGrid">
    <?php if (!$medicines): ?>
        <p class="muted">No medicines found in this category.</p>
    <?php endif; ?>
    <?php foreach ($medicines as $m): ?>
        <div class="med-card card">
            <div class="med-img">
                <?php if (!empty($m['image_path'])): ?>
                    <img src="<?= asset($m['image_path']) ?>" alt="<?= h($m['name']) ?>">
                <?php else: ?>
                    <div class="img-placeholder">No image</div>
                <?php endif; ?>
            </div>
            <h4><?= h($m['name']) ?></h4>
            <p class="muted"><?= h($m['vendor_name']) ?> &middot; <?= h($m['category_type']) ?></p>
            <p class="price">&#2547;<?= h(number_format($m['price'], 2)) ?></p>
            <p class="muted">Stock: <?= h($m['availability']) ?></p>
            <button class="btn btn-green"
                    onclick="addToCart(<?= (int)$m['id'] ?>, 1)">Add to Cart</button>
        </div>
    <?php endforeach; ?>
</div>

<script src="<?= asset('task1_23540323/assets/js/task1.js') ?>"></script>
<script src="<?= asset('task3_23544053/assets/js/task3.js') ?>"></script>
<?php render_footer(); ?>

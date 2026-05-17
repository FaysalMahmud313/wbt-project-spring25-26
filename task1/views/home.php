<?php /* Task 1 view: Home page with categories + AJAX search/filter */ render_header("Home"); ?>

<h1>Browse Medicines</h1>

<div class="layout-2col">
    <!-- Sidebar: categories with liquid/solid segmentation -->
    <aside class="card sidebar">
        <h3>Categories</h3>
        <ul class="cat-list">
            <li><a href="<?= url('home') ?>">All medicines</a></li>
            <?php foreach ($categories as $c): ?>
                <li>
                    <a href="<?= url('category', ['id' => $c['id']]) ?>">
                        <?= h($c['name']) ?>
                    </a>
                    <span class="tag <?= h($c['category_type']) ?>"><?= h($c['category_type']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <section>
        <!-- AJAX search + filters -->
        <div class="card searchbar">
            <input type="text" id="searchInput" placeholder="Search medicine name...">
            <input type="text" id="vendorInput" placeholder="Vendor name...">
            <select id="genreSelect">
                <option value="">All genres</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= h($c['id']) ?>"><?= h($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-blue" onclick="runSearch()">Search</button>
        </div>

        <p class="muted result-count" id="searchCount">
            <?= count($medicines) ?> medicine<?= count($medicines) === 1 ? '' : 's' ?> found
        </p>

        <!-- Medicine cards (filled by PHP first, replaced live by AJAX as you type) -->
        <div id="medicineGrid" class="grid">
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
                    <p class="muted"><?= h($m['vendor_name']) ?> &middot; <?= h($m['category_name']) ?></p>
                    <p class="price">&#2547;<?= h(number_format($m['price'], 2)) ?></p>
                    <p class="muted">Stock: <?= h($m['availability']) ?></p>
                    <button class="btn btn-green"
                            onclick="addToCart(<?= (int)$m['id'] ?>, 1)">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<script src="<?= asset('task1_23540323/assets/js/task1.js') ?>"></script>
<script src="<?= asset('task3_23544053/assets/js/task3.js') ?>"></script>
<?php render_footer(); ?>

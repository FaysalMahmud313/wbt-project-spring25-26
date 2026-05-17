<?php /* Task 1 view: Registration form */ render_header("Register"); ?>

<h1>Create an Account</h1>

<?php if ($error !== ""): ?>
    <div class="alert error"><?= h($error) ?></div>
<?php endif; ?>

<form class="card form" method="post"
      action="<?= url('register') ?>"
      onsubmit="return validateRegister(this)">
    <div class="form-row">
        <label>Full Name</label>
        <input type="text" name="name" required value="<?= h($_POST['name'] ?? '') ?>">
    </div>
    <div class="form-row">
        <label>Email</label>
        <input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>">
    </div>
    <div class="form-row">
        <label>Password (min 8 characters)</label>
        <input type="password" name="password" required minlength="8">
    </div>
    <div class="form-row">
        <label>Address</label>
        <input type="text" name="address" required value="<?= h($_POST['address'] ?? '') ?>">
    </div>
    <div class="form-row">
        <label>Phone</label>
        <input type="text" name="phone" required value="<?= h($_POST['phone'] ?? '') ?>">
    </div>
    <p class="hint">You are registering as a <b>Customer</b>.
       Admin accounts are created by an existing administrator.</p>
    <div class="form-actions">
        <button type="submit" class="btn btn-blue">Register</button>
        <a class="btn btn-light" href="<?= url('login') ?>">Already have an account?</a>
    </div>
</form>

<script src="<?= asset('task1_23540323/assets/js/task1.js') ?>"></script>
<?php render_footer(); ?>

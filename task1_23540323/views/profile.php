<?php render_header("Profile"); ?>

<h1>My Profile</h1>

<?php if ($message !== ""): ?><div class="alert success"><?= h($message) ?></div><?php endif; ?>
<?php if ($error   !== ""): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>

<div class="layout-2col">
    <form class="card form" method="post"
          action="<?= url('profile') ?>"
          enctype="multipart/form-data"
          onsubmit="return validateProfile(this)">
        <input type="hidden" name="action" value="update_profile">
        <h3>Account Details</h3>

        <?php if (!empty($user['profile_picture'])): ?>
            <img class="avatar" src="<?= asset($user['profile_picture']) ?>" alt="avatar">
        <?php endif; ?>

        <div class="form-row">
            <label>Full Name</label>
            <input type="text" name="name" required value="<?= h($user['name']) ?>">
        </div>
        <div class="form-row">
            <label>Email</label>
            <input type="email" name="email" required value="<?= h($user['email']) ?>">
        </div>
        <div class="form-row">
            <label>Address</label>
            <input type="text" name="address" required value="<?= h($user['address']) ?>">
        </div>
        <div class="form-row">
            <label>Phone</label>
            <input type="text" name="phone" required value="<?= h($user['phone']) ?>">
        </div>
        <div class="form-row">
            <label>Profile Picture (JPEG/PNG, max 2MB)</label>
            <input type="file" name="profile_picture" accept="image/jpeg,image/png">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-blue">Save Changes</button>
        </div>
    </form>

    <form class="card form" method="post"
          action="<?= url('profile') ?>"
          onsubmit="return validatePasswordChange(this)">
        <input type="hidden" name="action" value="change_password">
        <h3>Change Password</h3>
        <div class="form-row">
            <label>Current Password</label>
            <input type="password" name="current_password" required>
        </div>
        <div class="form-row">
            <label>New Password (min 8 characters)</label>
            <input type="password" name="new_password" required minlength="8">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-blue">Update Password</button>
        </div>
    </form>
</div>

<script src="<?= asset('task1_23540323/assets/js/task1.js') ?>"></script>
<?php render_footer(); ?>

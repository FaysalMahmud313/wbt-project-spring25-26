<?php render_header("Login"); ?>

<div class="auth-wrap">
    <div class="auth-card card">
        <div class="auth-head">
            <span class="auth-logo">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
                    <path d="M14 3h-4v7H3v4h7v7h4v-7h7v-4h-7z"></path>
                </svg>
            </span>
            <h1>Welcome Back</h1>
            <p class="muted">Sign in to your MediShop account</p>
        </div>

        <?php if ($error !== ""): ?>
            <div class="alert error"><?= h($error) ?></div>
        <?php endif; ?>

        <form class="form" method="post"
              action="<?= url('login') ?>"
              onsubmit="return validateLogin(this)">

            <div class="form-row">
                <label>Email</label>
                <div class="input-icon">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <path d="M3 7l9 6 9-6"></path>
                    </svg>
                    <input type="email" name="email" placeholder="you@example.com"
                           required value="<?= h($_POST['email'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <label>Password</label>
                <div class="input-icon">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                        <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                    </svg>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-row checkbox-row">
                <label><input type="checkbox" name="remember" value="1"> Remember me</label>
            </div>

            <button type="submit" class="btn btn-blue btn-block">Login</button>
        </form>

        <p class="auth-foot">
            New here? <a href="<?= url('register') ?>">Create an account</a>
        </p>
        <p class="hint">Sample admin: <b>admin@shop.com / admin123</b><br>
           Sample customer: <b>customer@shop.com / customer123</b></p>
    </div>
</div>

<script src="<?= asset('task1_23540323/assets/js/task1.js') ?>"></script>
<?php render_footer(); ?>

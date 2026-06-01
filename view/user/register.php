<?php
$section = 'register';
require BASE_PATH . '/view/layout/header.php';

$errors = $errors ?? [];
$old = $old ?? [];
?>

<form method="POST" class="auth-form">

    <h2>Register</h2>

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username"
               value="<?= $helper->old($old, 'username') ?>">
        <?= $helper->error('username', $errors) ?>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email"
               value="<?= $helper->old($old, 'email') ?>">
        <?= $helper->error('email', $errors) ?>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password">
        <?= $helper->error('password', $errors) ?>
    </div>

    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password">
        <?= $helper->error('confirm_password', $errors) ?>
    </div>

    <button type="submit">Create Account</button>

    <p class="auth-link">
        Already have an account?
        <a href="<?= BASE_URL ?>/Public/index.php?page=login">
            Sign in
        </a>
    </p>

</form>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>
<?php
$section = 'login';
require BASE_PATH . '/view/layout/header.php';
?>

<?php $errors = $errors ?? []; ?>
<?php $old = $old ?? []; ?>

<form method="POST" class="auth-form">

    <h2>Login</h2>

    <!-- Email -->
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email"
            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
            placeholder="Enter your email">

        <?php if (!empty($errors['email'])): ?>
            <small class="error">
                <?= implode('<br>', $errors['email']) ?>
            </small>
        <?php endif; ?>
    </div>

    <!-- Password -->
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password"
            placeholder="Enter your password">

        <?php if (!empty($errors['password'])): ?>
            <small class="error">
                <?= implode('<br>', $errors['password']) ?>
            </small>
        <?php endif; ?>
    </div>

    <button type="submit">Login</button>

    <p class="auth-link">
        Don't have an account?
        <a href="index.php?page=register">Register</a>
    </p>

</form>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>
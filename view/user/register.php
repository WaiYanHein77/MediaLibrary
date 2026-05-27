<?php
$section = 'register';
require BASE_PATH . '/view/layout/header.php';
?>
<?php $errors = $errors ?? []; ?>

<form method="POST" class="auth-form">

    <h2>Register</h2>

    <!-- Username -->
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username"
            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
            placeholder="Choose username">

        <?php if (!empty($errors['username'])): ?>
            <small class="error">
                <?= implode('<br>', $errors['username']) ?>
            </small>
        <?php endif; ?>
    </div>

    <!-- Email -->
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email"
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            placeholder="Enter email">

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
            placeholder="Create password">

        <?php if (!empty($errors['password'])): ?>
            <small class="error">
                <?= implode('<br>', $errors['password']) ?>
            </small>
        <?php endif; ?>
    </div>

    <button type="submit">Create Account</button>

    <p class="auth-link">
        Already have an account?
        <a href="index.php?page=login">Login</a>
    </p>

</form>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>
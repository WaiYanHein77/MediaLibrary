<?php require BASE_PATH . '/view/layout/header.php'; ?>

<?php $errors = $errors ?? []; ?>
<?php $old = $old ?? []; ?>

<style>
.auth-form {
    width: 340px;
    margin: 60px auto;
    padding: 25px;
    border-radius: 10px;
    background: #ffffff;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    font-family: Arial, sans-serif;
}

.auth-form h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

small.error {
    color: red;
    font-size: 12px;
    display: block;
    margin-top: 5px;
}

.auth-form button {
    width: 100%;
    padding: 10px;
    background: #ef7d7d;
    color: white;
    border: none;
    border-radius: 6px;
}

.auth-form button:hover {
    background: #1e7e34;
}

.auth-link {
    text-align: center;
    margin-top: 12px;
}
</style>

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
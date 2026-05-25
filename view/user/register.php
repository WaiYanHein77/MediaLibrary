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
    outline: none;
}

.form-group input:focus {
    border-color: #2d6cdf;
}

.auth-form button {
    width: 100%;
    padding: 10px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
}

.auth-form button:hover {
    background: #1e7e34;
}

.auth-link {
    text-align: center;
    margin-top: 12px;
}

.auth-link a {
    color: #2d6cdf;
    text-decoration: none;
}
</style>

<form method="POST" class="auth-form">
    <h2>Register</h2>

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="Choose username" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Create password" required>
    </div>

    <button type="submit">Create Account</button>

    <p class="auth-link">
        Already have an account?
        <a href="index.php?page=login">Login</a>
    </p>
</form>
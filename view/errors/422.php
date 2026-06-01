<h1>Validation Error</h1>

<p><?= htmlspecialchars($message) ?></p>

<?php if (!empty($context)): ?>
<ul>
    <?php foreach ($context as $field => $errors): ?>
        <li>
            <b><?= htmlspecialchars($field) ?></b>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
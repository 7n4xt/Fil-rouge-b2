<!doctype html>
<html lang="en">
<body>
    <h1>Ymmo Platform</h1>
    <?php if (!empty($first_name)): ?>
        <p>Welcome, <?= htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8') ?>.</p>
    <?php else: ?>
        <p><a href="/login">Login</a> or <a href="/register">Register</a></p>
    <?php endif; ?>
</body>
</html>

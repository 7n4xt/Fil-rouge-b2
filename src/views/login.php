<?php $errors = $errors ?? []; $flash_success = $flash_success ?? null; ?>
<!doctype html><html lang="en"><body>
<h1>Login</h1>
<?php if ($flash_success): ?><p><?= htmlspecialchars($flash_success, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<?php if (!empty($errors)): ?><ul><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul><?php endif; ?>
<form method="post" action="/login"><input type="email" name="mail" placeholder="Email" required><input type="password" name="password" placeholder="Password" required><button type="submit">Sign in</button></form>
<a href="/register">Need an account? Register</a>
</body></html>

<?php $errors = $errors ?? []; $old = $old ?? []; ?>
<!doctype html><html lang="en"><body>
<h1>Register</h1>
<?php if (!empty($errors)): ?><ul><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul><?php endif; ?>
<form method="post" action="/register">
<input name="first_name" placeholder="First name" value="<?= htmlspecialchars($old['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><input name="last_name" placeholder="Last name" value="<?= htmlspecialchars($old['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><input type="email" name="mail" placeholder="Email" value="<?= htmlspecialchars($old['mail'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><input name="phone_number" placeholder="Phone number" value="<?= htmlspecialchars($old['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><input type="password" name="password" placeholder="Password (min 8 chars)" required><input type="password" name="password_confirm" placeholder="Confirm password" required>
<button type="submit">Create account</button></form><a href="/login">Already have an account? Login</a>
</body></html>

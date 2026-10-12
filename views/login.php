<?php

require_once __DIR__ . '/../core/core.php';

// Already signed in? Go to the home page (index.php has no redirects, so no loop)
if (is_logged_in()) {
    redirect('index.php');
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);

$page_title = 'Login - Shoppn';

require_once __DIR__ . '/layout/header.php';

?>

<h2>Login</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<form method="POST" action="<?php echo BASE_URL; ?>/actions/login_action.php">

    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" required>
    </p>

    <p>
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required>
    </p>

    <button type="submit">Login</button>

</form>

<p>
    Don't have an account?
    <a href="<?php echo BASE_URL; ?>/views/register.php">Register here</a>
</p>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
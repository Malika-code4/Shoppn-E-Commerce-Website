<?php

require_once __DIR__ . '/../core/core.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Shoppn</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<?php require_once __DIR__ . '/layout/header.php'; ?>


<div class="container">

    <h2>Login</h2>


    <?php if (isset($_SESSION['error'])): ?>

        <p class="error">

            <?php

            echo htmlspecialchars($_SESSION['error']);

            unset($_SESSION['error']);

            ?>

        </p>

    <?php endif; ?>


    <form
        action="../actions/login_action.php"
        method="POST"
    >

        <div>

            <label for="customer_email">
                Email
            </label>

            <input
                type="email"
                id="customer_email"
                name="customer_email"
                maxlength="50"
                required
            >

        </div>


        <div>

            <label for="customer_pass">
                Password
            </label>

            <input
                type="password"
                id="customer_pass"
                name="customer_pass"
                required
            >

        </div>


        <button type="submit">
            Login
        </button>

    </form>


    <p>

        Don't have an account?

        <a href="register.php">
            Register here
        </a>

    </p>


</div>


<?php require_once __DIR__ . '/layout/footer.php'; ?>


</body>

</html>

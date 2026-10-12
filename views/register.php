<?php

require_once __DIR__ . '/../core/core.php';

// Signed-in users have no reason to register again
if (is_logged_in()) {
    redirect('index.php');
}

$error = $_SESSION['error'] ?? '';

unset($_SESSION['error']);

$page_title = 'Register - Shoppn';

require_once __DIR__ . '/layout/header.php';

?>

<div class="container">

    <h2>Create an Account</h2>

    <?php if ($error): ?>
        <p class="error" style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form
        action="<?php echo BASE_URL; ?>/actions/register_action.php"
        method="POST"
        id="registerForm"
    >

        <div>
            <label for="customer_name">Full Name</label>
            <input
                type="text"
                id="customer_name"
                name="customer_name"
                maxlength="100"
                required
            >
            <span class="field-error" id="nameError"></span>
        </div>

        <div>
            <label for="customer_email">Email</label>
            <input
                type="email"
                id="customer_email"
                name="customer_email"
                maxlength="50"
                required
            >
            <span class="field-error" id="emailError"></span>
        </div>

        <div>
            <label for="customer_pass">Password</label>
            <input
                type="password"
                id="customer_pass"
                name="customer_pass"
                required
            >
            <span class="field-error" id="passwordError"></span>
        </div>

        <div>
            <label for="customer_country">Country</label>
            <select id="customer_country" name="customer_country" required>
                <option value="">Select Country</option>
                <option value="Ghana">Ghana</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niger">Niger</option>
                <option value="Benin">Benin</option>
                <option value="Togo">Togo</option>
                <option value="Cote d'Ivoire">Cote d'Ivoire</option>
            </select>
        </div>

        <div>
            <label for="customer_city">City</label>
            <input
                type="text"
                id="customer_city"
                name="customer_city"
                maxlength="30"
                required
            >
        </div>

        <div>
            <label for="customer_contact">Contact Number</label>
            <input
                type="text"
                id="customer_contact"
                name="customer_contact"
                maxlength="15"
                required
            >
            <span class="field-error" id="phoneError"></span>
        </div>

        <button type="submit">Register</button>

    </form>

    <p>
        Already have an account?
        <a href="<?php echo BASE_URL; ?>/views/login.php">Login here</a>
    </p>

</div>

<script src="<?php echo BASE_URL; ?>/js/validate.js"></script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
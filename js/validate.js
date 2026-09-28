document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("registerForm");

    if (!form) {
        return;
    }

    form.addEventListener("submit", function (event) {

        let valid = true;

        const name = document.getElementById("customer_name");
        const email = document.getElementById("customer_email");
        const password = document.getElementById("customer_pass");
        const phone = document.getElementById("customer_contact");

        const nameError = document.getElementById("nameError");
        const emailError = document.getElementById("emailError");
        const passwordError = document.getElementById("passwordError");
        const phoneError = document.getElementById("phoneError");

        nameError.textContent = "";
        emailError.textContent = "";
        passwordError.textContent = "";
        phoneError.textContent = "";

        // Name validation
        if (name.value.trim().length < 2) {
            nameError.textContent = "Name must contain at least 2 characters.";
            valid = false;
        }

        // Email validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email.value.trim())) {
            emailError.textContent = "Please enter a valid email address.";
            valid = false;
        }

        // Password validation
        const passwordPattern = /^(?=.*\d).{8,}$/;

        if (!passwordPattern.test(password.value)) {
            passwordError.textContent =
                "Password must be at least 8 characters and contain a number.";
            valid = false;
        }

        // Phone validation
        const phonePattern = /^[0-9+\-\s]{7,15}$/;

        if (!phonePattern.test(phone.value.trim())) {
            phoneError.textContent =
                "Enter a valid contact number.";
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });
});
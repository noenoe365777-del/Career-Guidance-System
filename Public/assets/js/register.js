console.log("Register JS Loaded!");

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("registerForm");
    if (!form) return;

    // =========================
    // Terms Elements
    // =========================
    const terms = document.getElementById("terms");
    const termsError = document.getElementById("terms-error");



    // =========================
    // Show Error
    // =========================
    function showError(input, message) {

        clearError(input);

        input.classList.add(
            "border-red-500",
            "ring-2",
            "ring-red-300"
        );

        let error;

        // password fields
        if (input.parentElement.classList.contains("relative")) {
            error = input.parentElement.nextElementSibling;
        } else {
            error = input.nextElementSibling;
        }

        if (error) {
            error.textContent = message;
        }
    }

    // =========================
    // Clear Error
    // =========================
    function clearError(input) {

        input.classList.remove(
            "border-red-500",
            "ring-2",
            "ring-red-300"
        );

        let error;

        if (input.parentElement.classList.contains("relative")) {
            error = input.parentElement.nextElementSibling;
        } else {
            error = input.nextElementSibling;
        }

        if (error) {
            error.textContent = "";
        }
    }

    // =========================
    // Live Validation
    // =========================
    form.querySelectorAll("input, select").forEach(input => {

        input.addEventListener("input", () => {
            clearError(input);
        });

        input.addEventListener("change", () => {
            clearError(input);
        });
    });

    // Terms checkbox clear error
    if (terms) {
        terms.addEventListener("change", () => {
            termsError.textContent = "";
        });
    }

    // =========================
    // Password Strength
    // =========================
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm-password");

    const togglePassword = document.getElementById("togglePassword");
    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

    // Password
    if (togglePassword) {

        togglePassword.addEventListener("click", () => {

            const icon = togglePassword.querySelector("i");

            if (password.type === "password") {

                password.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");

            } else {

                password.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");

            }

        });

    }

    // Confirm Password
    if (toggleConfirmPassword) {

        toggleConfirmPassword.addEventListener("click", () => {

            const icon = toggleConfirmPassword.querySelector("i");

            if (confirmPassword.type === "password") {

                confirmPassword.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");

            } else {

                confirmPassword.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");

            }

        });

    }

    // =========================
    // Submit Validation
    // =========================
    form.addEventListener("submit", e => {

        let valid = true;

        const username = document.getElementById("username");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const confirm = document.getElementById("confirm-password");
        const education = document.getElementById("education");
        const dob = document.getElementById("dob");
        const gender = document.getElementById("gender");

        const emailPattern = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;

        // Username
        if (username.value.trim() === "") {
            showError(username, "Username is required");
            valid = false;
        } else if (username.value.trim().length < 4) {
            showError(username, "Username must be at least 4 characters");
            valid = false;
        }

        // Email
        if (email.value.trim() === "") {
            showError(email, "Email is required");
            valid = false;
        } else if (!emailPattern.test(email.value.trim())) {
            showError(email, "Please enter a valid email");
            valid = false;
        }

        // Password
        if (password.value === "") {
            showError(password, "Password is required");
            valid = false;
        } else if (password.value.length < 8) {
            showError(password, "Password must be at least 8 characters");
            valid = false;
        }

        // Confirm Password
        if (confirm.value === "") {
            showError(confirm, "Confirm password is required");
            valid = false;
        } else if (confirm.value !== password.value) {
            showError(confirm, "Passwords do not match");
            valid = false;
        }

        // Education
        if (education.value === "") {
            showError(education, "Please choose education");
            valid = false;
        }

        // DOB
        if (dob.value === "") {
            showError(dob, "Please choose date of birth");
            valid = false;
        }

        // Gender
        if (gender.value === "") {
            showError(gender, "Please choose gender");
            valid = false;
        }

        // Terms
        if (!terms.checked) {
            termsError.textContent = "You must agree to the Terms and Privacy Policy.";
            valid = false;
        } else {
            termsError.textContent = "";
        }

        if (!valid) {
            e.preventDefault();
            return;
        }

        // Loading state
        const btn = form.querySelector("button[type='submit']");
        btn.disabled = true;
        btn.innerHTML = "Creating Account...";
    });
});
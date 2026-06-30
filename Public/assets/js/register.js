document.addEventListener("DOMContentLoaded", function () {

    // ===============================
    // Password Show / Hide
    // ===============================
    document.querySelectorAll(".toggle-password").forEach(toggle => {

        toggle.addEventListener("click", function () {

            const input = document.getElementById(this.dataset.target);

            if (!input) return;

            if (input.type === "password") {
                input.type = "text";
                this.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                this.classList.replace("fa-eye-slash", "fa-eye");
            }

        });

    });

    const form = document.getElementById("registerForm");

    if (!form) return;

    //===================================
    // Show Error
    //===================================

    function showError(input, message) {

        const field = input.closest(".input-group");

        input.closest(".input-field").classList.add("error");

        let error = field.querySelector(".error-text");

        if (!error) {
            error = document.createElement("small");
            error.className = "error-text";
            field.appendChild(error);
        }

        error.innerText = message;
    }

    //===================================
    // Clear Error
    //===================================

    function clearError(input) {

        const field = input.closest(".input-group");

        input.closest(".input-field").classList.remove("error");

        const error = field.querySelector(".error-text");

        if (error) {
            error.remove();
        }

    }

    //===================================
    // Live Validation
    //===================================

    form.querySelectorAll("input, select").forEach(input => {

        input.addEventListener("input", () => {

            clearError(input);

        });

        input.addEventListener("change", () => {

            clearError(input);

        });

    });

    //===================================
    // Submit
    //===================================

    form.addEventListener("submit", function (e) {

        let valid = true;

        const username = document.getElementById("username");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const confirm = document.getElementById("confirm-password");
        const education = document.getElementById("education");
        const dob = document.getElementById("dob");
        const gender = document.getElementById("gender");

        // Username

        if (username.value.trim() === "") {

            showError(username, "Username is required.");

            valid = false;

        } else if (username.value.trim().length < 4) {

            showError(username, "Username must be at least 4 characters.");

            valid = false;

        }

        // Email

        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email.value.trim() === "") {

            showError(email, "Email is required.");

            valid = false;

        } else if (!emailPattern.test(email.value.trim())) {

            showError(email, "Invalid email address.");

            valid = false;

        }

        // Password

        if (password.value === "") {

            showError(password, "Password is required.");

            valid = false;

        } else if (password.value.length < 8) {

            showError(password, "Password must be at least 8 characters.");

            valid = false;

        }

        // Confirm Password

        if (confirm.value === "") {

            showError(confirm, "Please confirm your password.");

            valid = false;

        } else if (password.value !== confirm.value) {

            showError(confirm, "Passwords do not match.");

            valid = false;

        }

        // Education

        if (education.value === "") {

            showError(education, "Please select education level.");

            valid = false;

        }

        // DOB

        if (dob.value === "") {

            showError(dob, "Date of birth is required.");

            valid = false;

        }

        // Gender

        if (gender.value === "") {

            showError(gender, "Please select gender.");

            valid = false;

        }

        if (!valid) {

            e.preventDefault();

        }

    });

});
console.log("Register JS Loaded!");

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("registerForm");
    if (!form) return;

    // ==========================
    // Password Toggle
    // ==========================

    document.querySelectorAll(".toggle-password").forEach(icon => {

        icon.addEventListener("click", () => {

            const input = document.getElementById(icon.dataset.target);

            if (!input) return;

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }

        });

    });

    // ==========================
    // Show Error
    // ==========================

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
    }
    // normal input/select
    else {
        error = input.nextElementSibling;
    }

    if (error && error.classList.contains("error-message")) {
        error.textContent = message;
    }

}

    // ==========================
    // Remove Error
    // ==========================
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

    if (error && error.classList.contains("error-message")) {
        error.textContent = "";
    }

}


    // ==========================
    // Live Validation
    // ==========================

    form.querySelectorAll("input, select").forEach(input => {

        terms.addEventListener("change", () => {

    termsError.textContent = "";

});

        input.addEventListener("input", () => {

            clearError(input);

        });

        input.addEventListener("change", () => {

            clearError(input);

        });

    });

    // ==========================
    // Password Strength
    // ==========================

    const password = document.getElementById("password");

    const strength = document.createElement("div");

    strength.className = "text-sm mt-2 font-medium";

    password.parentElement.appendChild(strength);

    password.addEventListener("input", () => {

        let score = 0;

        const value = password.value;

        if (value.length >= 8) score++;
        if (/[A-Z]/.test(value)) score++;
        if (/[a-z]/.test(value)) score++;
        if (/[0-9]/.test(value)) score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;

        if (value === "") {

            strength.innerHTML = "";

            return;

        }

        if (score <= 2) {

            strength.innerHTML =
                "<span class='text-red-500'>Weak Password</span>";

        } else if (score <= 4) {

            strength.innerHTML =
                "<span class='text-yellow-500'>Medium Password</span>";

        } else {

            strength.innerHTML =
                "<span class='text-green-600'>Strong Password</span>";

        }

    });

    // ==========================
    // Submit Validation
    // ==========================

    form.addEventListener("submit", e => {

        let valid = true;

        const username = document.getElementById("username");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const confirm = document.getElementById("confirm-password");
        const education = document.getElementById("education");
        const dob = document.getElementById("dob");
        const gender = document.getElementById("gender");

        const terms = document.getElementById("terms");
const termsError = document.getElementById("terms-error");

        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (username.value.trim() === "") {

            showError(username, "Username is required");

            valid = false;

        } else if (username.value.trim().length < 4) {

            showError(username, "Username must be at least 4 characters");

            valid = false;

        }

        if (email.value.trim() === "") {

            showError(email, "Email is required");

            valid = false;

        } else if (!emailPattern.test(email.value.trim())) {

            showError(email, "Please enter a valid email");

            valid = false;

        }

        if (password.value === "") {

            showError(password, "Password is required");

            valid = false;

        } else if (password.value.length < 8) {

            showError(password, "Password must be at least 8 characters");

            valid = false;

        }

        if (confirm.value === "") {

            showError(confirm, "Confirm password is required");

            valid = false;

        } else if (confirm.value !== password.value) {

            showError(confirm, "Passwords do not match");

            valid = false;

        }

        if (education.value === "") {

            showError(education, "Please choose education");

            valid = false;

        }

        if (dob.value === "") {

            showError(dob, "Please choose date of birth");

            valid = false;

        }

        if (gender.value === "") {

            showError(gender, "Please choose gender");

            valid = false;

        }


        if (!terms.checked) {

    termsError.textContent =
        "You must agree to the Terms and Privacy Policy.";

    valid = false;

}
else{

    termsError.textContent = "";

}

        if (!valid) {

            e.preventDefault();

            return;

        }

        const btn = form.querySelector("button[type='submit']");

        btn.disabled = true;

        btn.innerHTML = "Creating Account...";

    });

});
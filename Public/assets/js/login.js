console.log("LOGIN JS LOADED");

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("loginForm");
    if (!form) return;

    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const remember = document.getElementById("remember");
    const rememberError = document.getElementById("remember-error");

    const togglePass = document.getElementById("togglePass");

    // =====================================
    // PASSWORD TOGGLE
    // =====================================

    if (togglePass) {

        togglePass.addEventListener("click", () => {

            const icon = togglePass.querySelector("i");

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

    // =====================================
    // SHOW ERROR
    // =====================================

    function showError(input, message) {

        clearError(input);

        input.classList.add(
            "border-red-500",
            "ring-2",
            "ring-red-300"
        );

        const error = document.createElement("small");

        error.className =
            "client-error text-red-500 text-sm mt-2 block";

        error.innerText = message;

        const wrapper = input.closest(".mb-6");

        wrapper.appendChild(error);

    }

    // =====================================
    // CLEAR ONE ERROR
    // =====================================

    function clearError(input) {

        input.classList.remove(
            "border-red-500",
            "ring-2",
            "ring-red-300"
        );

        const wrapper = input.closest(".mb-6");

        const error = wrapper.querySelector(".client-error");

        if (error) {
            error.remove();
        }

    }

    // =====================================
    // CLEAR ALL ERRORS
    // =====================================

    function clearErrors() {

        document.querySelectorAll(".client-error")
            .forEach(error => error.remove());

        document.querySelectorAll("input")
            .forEach(input => {

                input.classList.remove(
                    "border-red-500",
                    "ring-2",
                    "ring-red-300"
                );

            });

        rememberError.innerText = "";

        rememberError.classList.add("hidden");

    }

    // =====================================
    // LIVE VALIDATION
    // =====================================

    email.addEventListener("input", () => {

        clearError(email);

    });

    password.addEventListener("input", () => {

        clearError(password);

    });

    remember.addEventListener("change", () => {

        rememberError.innerText = "";
        rememberError.classList.add("hidden");

    });

    // =====================================
    // FORGOT PASSWORD
    // =====================================

    const forgotBtn = document.getElementById("forgotPasswordBtn");
    const forgotForm = document.getElementById("forgotPasswordForm");
    const forgotEmailInput = document.getElementById("forgotEmailInput");

    if (forgotBtn && forgotForm && forgotEmailInput) {
        forgotBtn.addEventListener("click", () => {
            const emailValue = email.value.trim();
            forgotEmailInput.value = emailValue;
            forgotForm.submit();
        });
    }

    // =====================================
    // FORM SUBMIT
    // =====================================

    form.addEventListener("submit", function (e) {

        clearErrors();

        let valid = true;

        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // ---------------- EMAIL ----------------

        if (email.value.trim() === "") {

            showError(email, "Email is required.");

            valid = false;

        }

        else if (!emailPattern.test(email.value.trim())) {

            showError(email, "Please enter a valid email.");

            valid = false;

        }

        // ---------------- PASSWORD ----------------

        if (password.value.trim() === "") {

            showError(password, "Password is required.");

            valid = false;

        }

        // ---------------- REMEMBER ----------------

        if (!remember.checked) {

            rememberError.innerText =
                "Please tick Remember me.";

            rememberError.classList.remove("hidden");

            valid = false;

        }

        // ---------------- STOP ----------------

        if (!valid) {

            e.preventDefault();

            return;

        }

        // =====================================
        // LOADING BUTTON
        // =====================================

        const btn = document.getElementById("loginBtn");
        const text = document.getElementById("loginBtnText");

        btn.disabled = true;

        btn.classList.add(
            "opacity-70",
            "cursor-not-allowed"
        );

        text.innerHTML = `
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Logging in...
        `;

    });

});
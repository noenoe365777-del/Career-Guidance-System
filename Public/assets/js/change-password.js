document.addEventListener("DOMContentLoaded", function () {

    // =============================
    // Elements
    // =============================

    const password = document.getElementById("newPassword");
    const confirm = document.getElementById("confirmPassword");
    const form = document.querySelector("form");
    const submitBtn = document.getElementById("submitBtn");
    const btnText = document.getElementById("btnText");

    // =============================
    // Password Strength
    // =============================

    if (password) {
        password.addEventListener("input", updateStrength);
    }

    function updateStrength() {

        const value = password.value;
        let score = 0;

        if (value.length >= 8) score++;
        if (/[A-Z]/.test(value)) score++;
        if (/[0-9]/.test(value)) score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;

        const bars = [
            document.getElementById("bar1"),
            document.getElementById("bar2"),
            document.getElementById("bar3"),
            document.getElementById("bar4")
        ];

        bars.forEach(bar => {
            bar.className = "h-2 rounded bg-gray-200 transition-all duration-300";
        });

        const text = document.getElementById("strengthText");

        switch (score) {

            case 1:
                bars[0].classList.add("bg-red-500");
                text.textContent = "Weak";
                text.className = "font-semibold text-red-500";
                break;

            case 2:
                bars[0].classList.add("bg-yellow-500");
                bars[1].classList.add("bg-yellow-500");
                text.textContent = "Medium";
                text.className = "font-semibold text-yellow-500";
                break;

            case 3:
                bars[0].classList.add("bg-blue-500");
                bars[1].classList.add("bg-blue-500");
                bars[2].classList.add("bg-blue-500");
                text.textContent = "Strong";
                text.className = "font-semibold text-blue-600";
                break;

            case 4:
                bars.forEach(bar => bar.classList.add("bg-green-500"));
                text.textContent = "Very Strong";
                text.className = "font-semibold text-green-600";
                break;

            default:
                text.textContent = "Weak";
                text.className = "font-semibold text-red-500";
        }
    }

    // =============================
    // Password Match
    // =============================

    if (confirm) {
        confirm.addEventListener("input", checkMatch);
    }

    function checkMatch() {

        const msg = document.getElementById("matchMessage");

        if (confirm.value === "") {
            msg.textContent = "";
            return;
        }

        if (confirm.value === password.value) {

            msg.textContent = "✓ Passwords match";
            msg.className = "mt-3 text-green-600 font-semibold";

        } else {

            msg.textContent = "✗ Passwords do not match";
            msg.className = "mt-3 text-red-600 font-semibold";

        }
    }

    // =============================
    // Loading Button
    // =============================

    if (form && submitBtn && btnText) {

        form.addEventListener("submit", function () {

            submitBtn.disabled = true;

            submitBtn.classList.add(
                "opacity-70",
                "cursor-not-allowed"
            );

            btnText.innerHTML = `
                <i class="fas fa-spinner fa-spin mr-2"></i>
                Updating...
            `;

        });

    }

});

// =============================
// Show / Hide Password
// =============================

window.togglePassword = function (id, btn) {

    const input = document.getElementById(id);
    const icon = btn.querySelector("i");

    if (input.type === "password") {

        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");

    } else {

        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");

    }

};
document.addEventListener("DOMContentLoaded", function () {

    const input = document.getElementById("profileImage");
    const preview = document.getElementById("avatarPreview");
    const form = document.getElementById("imageForm");

    if (!input) return;

    input.addEventListener("change", function () {

        const file = this.files[0];

        if (!file) return;

        // Preview image instantly
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
        };

        reader.readAsDataURL(file);

        // Upload automatically
        form.submit();

    });

});
<footer class="footer">
    © 2026 Career Guidance System. All Rights Reserved.
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const dropdown = document.getElementById("userDropdown");
    const button = document.getElementById("userDropdownBtn");

    if (dropdown && button) {

        button.addEventListener("click", function (e) {
            e.stopPropagation();
            dropdown.classList.toggle("active");
        });

        document.addEventListener("click", function (e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove("active");
            }
        });

    }

});
</script>
</body>
</html>
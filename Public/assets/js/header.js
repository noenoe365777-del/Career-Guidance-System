document.addEventListener("DOMContentLoaded", function () {

    const menuBtn = document.getElementById("mobileMenuBtn");
    const mobileMenu = document.getElementById("mobileMenu");

    if (menuBtn && mobileMenu) {

        menuBtn.addEventListener("click", function () {

            mobileMenu.classList.toggle("hidden");

            const icon = menuBtn.querySelector("i");

            icon.classList.toggle("fa-bars");
            icon.classList.toggle("fa-times");

        });

    }
if (mobileMenu) {

    const mobileLinks = mobileMenu.querySelectorAll("a");

    mobileLinks.forEach(link => {

        link.addEventListener("click", function () {

            mobileMenu.classList.add("hidden");

            if (menuBtn) {
                const icon = menuBtn.querySelector("i");

                icon.classList.remove("fa-times");
                icon.classList.add("fa-bars");
            }

        });

    });

}
    // Desktop User Dropdown
    const dropdownBtn = document.getElementById("userDropdownBtn");
    const dropdownMenu = document.getElementById("dropdownMenu");

    if (dropdownBtn && dropdownMenu) {

        dropdownBtn.addEventListener("click", function (e) {

            e.stopPropagation();
            dropdownMenu.classList.toggle("hidden");

        });

        document.addEventListener("click", function () {

            dropdownMenu.classList.add("hidden");

        });

    }

});
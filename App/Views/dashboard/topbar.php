<header class="sticky top-0 z-40 bg-white/90 backdrop-blur-lg border-b border-gray-200">

    <div class="flex items-center justify-between h-24 px-8">

        <!-- Left -->
        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Dashboard
            </h1>

        </div>

        <!-- Right -->
        <div class="flex items-center gap-8">

            <!-- Notification -->

            <button
                class="relative w-12 h-12 rounded-full bg-gray-100 hover:bg-indigo-100 transition duration-300 flex items-center justify-center">

                <i class="fas fa-bell text-xl text-gray-600"></i>

                <span
                    class="absolute -top-1 -right-1 flex items-center justify-center w-6 h-6 rounded-full bg-red-500 text-white text-xs font-bold animate-pulse">

                    2

                </span>

            </button>

            <!-- User -->

            <div
                class="flex items-center gap-4 bg-white border rounded-full px-3 py-2 shadow hover:shadow-md transition">

                <img
                    src="<?= BASE_URL ?>/public/assets/images/avatar.png"
                    class="w-14 h-14 rounded-full object-cover border-2 border-indigo-100"
                    alt="Student">

                <div>

                    <h3 class="font-bold text-gray-800">

<?= htmlspecialchars($_SESSION['user']['username'] ?? 'Student'); ?>

                    </h3>

                    <p class="text-gray-500 text-sm">

                        Student

                    </p>

                </div>

                <button>

                    <i class="fas fa-chevron-down text-gray-500"></i>

                </button>

            </div>

        </div>

    </div>

</header>
<?php
$pageTitle = "Change Password";
?>

<main class="relative min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-100 py-8 md:py-12 lg:py-16 px-4">

<div class="max-w-6xl mx-auto">

<?php if (!empty($_SESSION['errors'])): ?>

<div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 md:p-5 flex items-center gap-4">

    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-red-500 flex items-center justify-center">

        <i class="fas fa-times text-white"></i>

    </div>

    <div>

        <h3 class="font-bold text-red-700">
            Error
        </h3>

        <?php foreach ($_SESSION['errors'] as $error): ?>

            <p class="text-red-600">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endforeach; ?>

    </div>

</div>

<?php unset($_SESSION['errors']); ?>

<?php endif; ?>


<?php if (!empty($_SESSION['success'])): ?>

<div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 md:p-5 flex items-center gap-4">

    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-500 flex items-center justify-center">

        <i class="fas fa-check text-white"></i>

    </div>

    <div>

        <h3 class="font-bold text-green-700">
            Success
        </h3>

        <p class="text-green-600">
            <?= htmlspecialchars($_SESSION['success']); ?>
        </p>

    </div>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>
<div class="bg-white
rounded-2xl lg:rounded-3xl
shadow-2xl
overflow-hidden
grid
grid-cols-1
lg:grid-cols-2
animate-fade
hover:-translate-y-2
hover:shadow-[0_30px_80px_rgba(21,71,154,.25)]
transition-all
duration-500">

    <!-- Left Panel -->
   <div class="hidden lg:flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-8 xl:p-12">

        <lottie-player
            src="https://assets7.lottiefiles.com/packages/lf20_jcikwtux.json"
            background="transparent"
            speed="1"
            style="width:100%;max-width:300px;height:300px;"
            loop
            autoplay>
        </lottie-player>

        <h2 class="text-3xl font-bold text-slate-800 mt-6 text-center">
            Keep Your Account Secure
        </h2>

        <p class="text-gray-500 text-center mt-4 leading-8">
            Change your password regularly to protect your account and personal information.
        </p>

    </div>

    <!-- Right Panel -->
<div class="p-6 md:p-8 lg:p-10">

        <div class="flex items-center gap-4 mb-8">

            <div class="w-10 h-10 md:w-12 md:h-12 md:w-16 md:h-16 rounded-xl md:rounded-2xl bg-[#15479A] flex items-center justify-center shadow-lg">

                <i class="fas fa-lock text-white text-2xl"></i>

            </div>

            <div>
<h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-slate-800">
                    Change Password
                </h1>
<p class="text-sm md:text-base text-gray-500 mt-1">
                    Update your password securely.
                </p>

            </div>

        </div>
<form action="index.php?page=update-password"
      method="POST"
      class="space-y-5 md:space-y-7">

    <!-- Current Password -->
    <div>
        <label class="block text-sm md:text-base font-semibold text-gray-700 mb-2">
            Current Password
        </label>

        <div class="relative">

            <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>

            <input
                id="currentPassword"
                type="password"
                name="current_password"
                required
                placeholder="Enter current password"
                class="w-full
pl-14
pr-14
py-3 md:py-4
rounded-xl
border
border-gray-300
bg-white
shadow-sm
focus:border-[#15479A]
focus:ring-4
focus:ring-blue-100
focus:scale-[1.02]
hover:border-blue-300
transition-all
duration-300">

            <button
                type="button"
                onclick="togglePassword('currentPassword',this)"
                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#15479A]">

                <i class="fas fa-eye"></i>

            </button>

        </div>
    </div>

    <!-- New Password -->
    <div>

        <label class="block text-sm font-semibold text-gray-700 mb-2">
            New Password
        </label>

        <div class="relative">

            <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>

            <input
                id="newPassword"
                type="password"
                name="new_password"
                required
                placeholder="Enter new password"
                class="w-full
pl-14
pr-14
py-3 md:py-4
rounded-xl
border
border-gray-300
bg-white
shadow-sm
focus:border-[#15479A]
focus:ring-4
focus:ring-blue-100
focus:scale-[1.02]
hover:border-blue-300
transition-all
duration-300">

            <button
                type="button"
                onclick="togglePassword('newPassword',this)"
                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#15479A]">

                <i class="fas fa-eye"></i>

            </button>

        </div>

        <div class="mt-4">

            <div class="flex justify-between text-sm">

                <span>Password Strength</span>

                <span id="strengthText" class="font-semibold text-red-500">
                    Weak
                </span>

            </div>

            <div class="grid grid-cols-4 gap-2 mt-2">

                <div id="bar1" class="h-2 rounded bg-gray-200"></div>
                <div id="bar2" class="h-2 rounded bg-gray-200"></div>
                <div id="bar3" class="h-2 rounded bg-gray-200"></div>
                <div id="bar4" class="h-2 rounded bg-gray-200"></div>

            </div>

        </div>

    </div>

    <!-- Confirm Password -->

    <div>

        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Confirm Password
        </label>

        <div class="relative">

            <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>

            <input
                id="confirmPassword"
                type="password"
                name="confirm_password"
                required
                placeholder="Confirm password"
                class="w-full
pl-14
pr-14
py-3 md:py-4
rounded-xl
border
border-gray-300
bg-white
shadow-sm
focus:border-[#15479A]
focus:ring-4
focus:ring-blue-100
focus:scale-[1.02]
hover:border-blue-300
transition-all
duration-300">

            <button
                type="button"
                onclick="togglePassword('confirmPassword',this)"
                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#15479A]">

                <i class="fas fa-eye"></i>

            </button>

        </div>

        <p id="matchMessage" class="mt-3 text-sm"></p>

    </div>
<button
    id="submitBtn"
    type="submit"
class="w-full py-3 md:py-4 rounded-xl
           bg-gradient-to-r
           from-[#15479A]
           to-blue-600
           text-white
           font-bold
          text-base md:text-lg
           shadow-lg
           hover:scale-105
           hover:shadow-2xl
           transition-all
           duration-300">

    <span id="btnText">
        <i class="fas fa-lock mr-2"></i>
        Update Password
    </span>

</button>


</form>

    </div>

</div>
</div>

</main>

<?php $extraJs = 'assets/js/change-password.js'; ?>
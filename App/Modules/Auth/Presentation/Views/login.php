<?php
use App\Helpers\FormHelper;

$errors = $_SESSION['errors'] ?? [];

$success = $_SESSION['success'] ?? null;

$old = $_SESSION['old'] ?? [];

unset(
    
    $_SESSION['errors'],
    $_SESSION['success'],
    $_SESSION['old']
);

$pageTitle = "Login";


?>


    <!-- =========================
         LOGIN CONTENT
    ========================== -->
<main class="flex items-center justify-center py-10 px-6">

<div class="max-w-6xl w-full grid lg:grid-cols-2 gap-10 items-center">


<!-- ================= LEFT SIDE ================= -->

<div class="flex flex-col items-center lg:items-start justify-center animate-fadeUp">

 <img
    src="assets/images/login.png"
    alt="Login"
    class="w-full max-w-md animate-float">

    <h1 class="mt-6 text-4xl font-bold text-[#15479A]">

        Discover Your Future

    </h1>

    <p class="mt-3 text-gray-600">

        Smart career guidance for every student.

    </p>

</div>

 <!-- ================= RIGHT SIDE ================= -->

<div class="animate-fade">

    <div class="bg-white rounded-3xl shadow-xl p-6 lg:p-8 h-fit transition duration-300 hover:shadow-2xl">

        <!-- User Icon -->

       

        <!-- Heading -->

        <h2 class="text-3xl font-bold text-center text-slate-800">

            Welcome Back!

        </h2>

        <p class="text-center text-gray-500 mt-2 mb-6">

            Login to continue your account

        </p>

        <?php if ($success): ?>

            <div class="mb-5 p-3 rounded-lg bg-green-100 text-green-700 border border-green-300">

                <?= htmlspecialchars($success) ?>

            </div>

        <?php endif; ?>

        <!-- Login Form -->
<form
  id="loginForm"
    action="<?= BASE_URL ?>/index.php?page=login"
    method="POST"
    class="space-y-5"
    novalidate
>

        <!-- ================= EMAIL ================= -->

<div>

    <label for="email" class="block mb-2 font-semibold text-gray-700">
        Email Address
    </label>

    <div class="relative">

        <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

        <input
            type="text"
            id="email"
            name="email"
            placeholder="Enter your email"
            value="<?= FormHelper::old($old,'email')?>"

            class="w-full h-12 pl-12 pr-4 rounded-xl border border-gray-300
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                   outline-none transition"

            <?= FormHelper::hasError($errors,'email')
                ? 'border-red-500'
                : '' ?>
        >

    </div>

    <?php if(isset($errors['email'])): ?>

        <p class="text-red-500 text-sm mt-2">

            <?= htmlspecialchars($errors['email']) ?>

        </p>

    <?php endif; ?>

</div>

<!-- ================= PASSWORD ================= -->

<div>

    <label for="password" class="block mb-2 font-semibold text-gray-700">
        Password
    </label>

    <div class="relative">

        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

        <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter your password"

            class="w-full h-12 pl-12 pr-12 rounded-xl border border-gray-300
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                   outline-none transition"

            <?= FormHelper::hasError($errors,'password')
                ? 'border-red-500'
                : '' ?>
        >

        <button
            type="button"
            id="togglePass"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-indigo-600">

            <i class="far fa-eye"></i>

        </button>

    </div>

    <?= FormHelper::error($errors,'password') ?>

</div>

<!-- ================= REMEMBER ================= -->

<div class="flex items-center justify-between text-sm">

    <label class="flex items-center gap-2 text-gray-600 cursor-pointer">

        <input
            type="checkbox"
            id="remember"
            name="remember"
            class="rounded border-gray-300 text-indigo-600">

        Remember me

    </label>

    <a href="<?= BASE_URL ?>/index.php?page=forgot-password"
       class="text-indigo-600 hover:underline">

        Forgot Password?

    </a>

</div>

<!-- ================= LOGIN BUTTON ================= -->

<button
    id="loginBtn"
    type="submit"

    class="w-full h-12 rounded-xl bg-gradient-to-r from-[#15479A] to-blue-700
           text-white font-semibold shadow-lg
           hover:shadow-xl hover:scale-[1.02]
           transition duration-300">

    <i class="fas fa-sign-in-alt mr-2"></i>

    Login

</button>

</form>

<!-- ================= SOCIAL LOGIN ================= -->

<div class="flex items-center my-6">

    <div class="flex-1 border-t border-gray-300"></div>

    <span class="px-4 text-sm text-gray-500">

        OR

    </span>

    <div class="flex-1 border-t border-gray-300"></div>

</div>

<a
href="<?= BASE_URL ?>/index.php?page=google-login"
    class="flex items-center justify-center gap-3 w-full h-12 border border-gray-300 rounded-xl
           hover:border-[#15479A] hover:bg-gray-50 transition duration-300">

    <img
        src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
        class="w-5 h-5"
        alt="Google">

    <span class="font-medium text-gray-700">

        Continue with Google

    </span>

</a>

<!-- ================= REGISTER ================= -->

<p class="text-center text-gray-600 mt-6 text-sm">

    Don't have an account?

    <a
  href="<?= BASE_URL ?>/index.php?page=register"
        class="font-semibold text-[#15479A] hover:underline">

        Register here

    </a>

</p>

    </div>

</div>

</div>

</main>
<?php
$extraJs = "assets/js/login.js";
?>
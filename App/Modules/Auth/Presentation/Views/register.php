<?php
use App\Helpers\FormHelper;

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;

unset(
    $_SESSION['errors'],
    $_SESSION['old'],
    $_SESSION['success'],
    $_SESSION['error']
);


?>

<!-- ================= REGISTER PAGE ================= -->

<main class="py-10">

<div class="max-w-6xl mx-auto px-6">

<div class="grid lg:grid-cols-2 gap-10 items-center">

<!-- ================= LEFT PANEL ================= -->

<div class="flex flex-col items-center lg:items-start justify-center animate-fadeUp">

<img
    src="<?= BASE_URL ?>/assets/images/login.png"
    alt="Career Guidance"
    class="w-full max-w-md animate-float">

    <h1 class="mt-6 text-4xl lg:text-5xl font-bold text-[#15479A]">

        Create Your Account

    </h1>

    <p class="mt-3 text-gray-600 text-lg">

        Start your career journey with us.

    </p>

</div>

<!-- ================= RIGHT PANEL ================= -->

<div class="flex justify-center">

<div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 w-full max-w-xl animate-fade">

  

    <!-- Heading -->

    <h2 class="text-3xl font-bold text-center text-gray-800 mt-5">

        Register

    </h2>

    <p class="text-center text-gray-500 mt-2">

        Create your account to continue

    </p>

    <?php if($success): ?>

    <div class="mt-5 bg-green-100 border border-green-300 text-green-700 rounded-lg px-4 py-3">

        <?= htmlspecialchars($success) ?>

    </div>

    <?php endif; ?>

    <?php if($error): ?>

    <div class="mt-5 bg-red-100 border border-red-300 text-red-700 rounded-lg px-4 py-3">

        <?= htmlspecialchars($error) ?>

    </div>

    <?php endif; ?>

    <!-- ================= REGISTER FORM ================= -->

    <form
        id="registerForm"
        action="<?= BASE_URL ?>/index.php?page=register"
        method="POST"
        novalidate
        class="grid md:grid-cols-2 gap-5 mt-8">

        <!-- ================= Username ================= -->

<div>

    <label class="block mb-2 font-medium text-gray-700">
        Username
    </label>

    <input
        type="text"
        id="username"
        name="username"
        placeholder="Enter username"
        value="<?= FormHelper::old($old,'username')?>"
        class="w-full h-12 px-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#15479A] focus:border-[#15479A] outline-none">

    <small class="text-red-500 text-sm">
        <?= $errors['username'] ?? '' ?>
    </small>

</div>

<!-- ================= Email ================= -->

<div>

    <label class="block mb-2 font-medium text-gray-700">
        Email
    </label>

    <input
        type="email"
        id="email"
        name="email"
        placeholder="Enter email"
        value="<?= FormHelper::old($old,'email')?>"
        class="w-full h-12 px-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#15479A] focus:border-[#15479A] outline-none">

    <small class="text-red-500 text-sm">
        <?= $errors['email'] ?? '' ?>
    </small>

</div>

<!-- ================= Password ================= -->

<div>

    <label class="block mb-2 font-medium text-gray-700">
        Password
    </label>

    <div class="relative">

        <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter password"
            class="w-full h-12 px-4 pr-12 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#15479A] focus:border-[#15479A] outline-none">

<button
    type="button"
    id="togglePassword"
    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-indigo-600">

    <i class="far fa-eye"></i>

</button>

    </div>

    <small class="text-red-500 text-sm">
        <?= $errors['password'] ?? '' ?>
    </small>

</div>

<!-- ================= Confirm Password ================= -->

<div>

    <label class="block mb-2 font-medium text-gray-700">
        Confirm Password
    </label>

    <div class="relative">

        <input
            type="password"
            id="confirm-password"
            name="confirm_password"
            placeholder="Confirm password"
            class="w-full h-12 px-4 pr-12 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#15479A] focus:border-[#15479A] outline-none">

      <button
    type="button"
    id="toggleConfirmPassword"
    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-indigo-600">

    <i class="far fa-eye"></i>

</button>

    </div>

    <small class="text-red-500 text-sm">
        <?= $errors['confirm_password'] ?? '' ?>
    </small>

</div>

<!-- ================= Education ================= -->

<div>

    <label class="block mb-2 font-medium text-gray-700">
        Education
    </label>

    <select
        id="education"
        name="education"
        class="w-full h-12 px-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#15479A] focus:border-[#15479A]">

        <option value="">Select Education</option>

        <option value="8" <?= FormHelper::selected($old,'education','8')?>>
            High School
        </option>

        <option value="9" <?= FormHelper::selected($old,'education','9')?>>
            Undergraduate
        </option>

        <option value="10" <?= FormHelper::selected($old,'education','10')?>>
            Graduate
        </option>

    </select>

    <small class="text-red-500 text-sm">
        <?= $errors['education'] ?? '' ?>
    </small>

</div>

<!-- ================= Date of Birth ================= -->

<div>

    <label class="block mb-2 font-medium text-gray-700">
        Date of Birth
    </label>

    <input
        type="date"
        id="dob"
        name="dob"
        value="<?= FormHelper::old($old,'dob')?>"
        class="w-full h-12 px-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#15479A] focus:border-[#15479A]">

    <small class="text-red-500 text-sm">
        <?= $errors['dob'] ?? '' ?>
    </small>

</div>

<!-- ================= Gender ================= -->

<div class="md:col-span-2">

    <label class="block mb-2 font-medium text-gray-700">
        Gender
    </label>

    <select
        id="gender"
        name="gender"
        class="w-full h-12 px-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#15479A] focus:border-[#15479A]">

        <option value="">Select Gender</option>

        <option value="5" <?= FormHelper::selected($old,'gender','5')?>>
            Male
        </option>

        <option value="6" <?= FormHelper::selected($old,'gender','6')?>>
            Female
        </option>

        <option value="7" <?= FormHelper::selected($old,'gender','7')?>>
            Other
        </option>

    </select>

    <small class="text-red-500 text-sm">
        <?= $errors['gender'] ?? '' ?>
    </small>

</div>

<!-- ================= Terms ================= -->

<div class="md:col-span-2">

    <label class="flex items-start gap-3 cursor-pointer">

        <input
            type="checkbox"
            id="terms"
            name="terms"
            value="1"
            class="mt-1 rounded border-gray-300 text-[#15479A] focus:ring-[#15479A]">

        <span class="text-sm text-gray-600">

            I agree to the

            <a href="#" class="text-[#15479A] font-medium hover:underline">
                Terms
            </a>

            and

            <a href="#" class="text-[#15479A] font-medium hover:underline">
                Privacy Policy
            </a>

        </span>

    </label>

    <small
        id="terms-error"
        class="text-red-500 text-sm mt-2 block">
    </small>

</div>

<!-- ================= Register Button ================= -->

<div class="md:col-span-2">

    <button
        type="submit"
        class="w-full h-12 rounded-xl bg-gradient-to-r from-[#15479A] to-blue-700 text-white font-semibold hover:opacity-90 transition duration-300">

        <i class="fas fa-user-plus mr-2"></i>

        Create Account

    </button>

</div>

<!-- ================= Divider ================= -->

<div class="md:col-span-2 flex items-center my-2">

    <div class="flex-1 border-t border-gray-300"></div>

    <span class="px-4 text-gray-500 text-sm">

        OR

    </span>

    <div class="flex-1 border-t border-gray-300"></div>

</div>

<!-- ================= Google Login ================= -->

<div class="md:col-span-2">

    <a
    href="<?= BASE_URL ?>/index.php?page=google-login"
        class="w-full h-12 border border-gray-300 rounded-xl flex items-center justify-center gap-3 hover:border-[#15479A] hover:bg-gray-50 transition">

        <img
            src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
            class="w-5 h-5"
            alt="Google">

        <span class="font-medium text-gray-700">

            Continue with Google

        </span>

    </a>

</div>

<!-- ================= Login ================= -->

<div class="md:col-span-2 text-center mt-2 text-gray-600">

    Already have an account?
<a
    href="<?= BASE_URL ?>/index.php?page=login"
        class="font-semibold text-[#15479A] hover:underline">

        Login

    </a>

</div>

</form>

</div>

</div>

</div>

</main>

<?php
$extraJs = "assets/js/register.js";
?>
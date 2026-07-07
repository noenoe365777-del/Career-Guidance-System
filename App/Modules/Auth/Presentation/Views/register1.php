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

$pageTitle = "Register";

require BASE_PATH . "/App/Views/header.php";
?>
<main>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-100 flex items-center justify-center px-6 py-16">

<div class="max-w-7xl w-full grid lg:grid-cols-2 gap-16 items-center">

<!-- LEFT PANEL -->
<div class="flex flex-col justify-center animate-fade-in">

   

    <h1 class="text-5xl font-bold text-gray-900 mt-4 leading-tight">
        Build Your Future
        <br>
        With Confidence
    </h1>

    <p class="mt-6 text-gray-600 leading-8 text-lg">

        Join thousands of students discovering careers that match
        their interests, strengths, and future goals.

    </p>

    <!-- Features -->
    <div class="mt-10 space-y-4">

        <div class="flex items-center gap-3">

            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">

                <i class="fas fa-check text-blue-600"></i>

            </div>

            <span class="font-medium">

                Personalized Career Recommendations

            </span>

        </div>

        <div class="flex items-center gap-3">

            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">

                <i class="fas fa-check text-blue-600"></i>

            </div>

            <span class="font-medium">

                Career Assessment Tests

            </span>

        </div>

        <div class="flex items-center gap-3">

            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">

                <i class="fas fa-check text-blue-600"></i>

            </div>

            <span class="font-medium">

                Discover Your Strengths

            </span>

        </div>

    </div>

    <!-- Image -->
    <div class="mt-12">

        <img
            src="/career-guidance-system/Public/assets/images/login.png"
         class="w-full max-w-lg mx-auto animate-float drop-shadow-2xl"
            alt="Career Guidance">

    </div>

</div>

<!-- RIGHT PANEL -->
<div class="flex justify-center">

<div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 p-10 w-full max-w-2xl animate-slide-up">
        <div class="text-center">

            <div class="w-20 h-20 mx-auto rounded-full bg-blue-100 flex items-center justify-center">

                <i class="fas fa-user-plus text-4xl text-blue-600"></i>

            </div>

            <h2 class="text-4xl font-bold mt-6">

                Create Account

            </h2>

            <p class="text-gray-500 mt-2">

                Join Career Guidance for Students

            </p>
            <?php if($success): ?>

<div class="mt-6 bg-green-100 border border-green-300 text-green-700 rounded-xl px-4 py-3">

<?= htmlspecialchars($success) ?>

</div>

<?php endif; ?>


<?php if($error): ?>

<div class="mt-6 bg-red-100 border border-red-300 text-red-700 rounded-xl px-4 py-3">

<?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>

</div>
<form
    id="registerForm"
    action="index.php?page=register"
    method="POST"
    nonvalidate
    class="mt-8 grid md:grid-cols-2 gap-6">

<div>

<label class="block font-semibold mb-2">

Username

</label>

<input
id="username"
type="text"
name="username"

placeholder="Enter username"
value="<?= FormHelper::old($old,'username')?>"
class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none">

<small class="error-message text-red-500 text-sm mt-2 block">
<?= $errors['username'] ?? '' ?>
</small>


</div>

<div>

<label class="block font-semibold mb-2">

Email

</label>

<input
id="email"
type="email"
name="email"

placeholder="Enter email"
value="<?= FormHelper::old($old,'email')?>"
class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none">


<small class="error-message text-red-500 text-sm mt-2 block">
<?= $errors['email'] ?? '' ?>
</small>

</div>


<div>

<label class="block font-semibold mb-2">

Password

</label>

<div class="relative">

<input
type="password"
id="password"
name="password"

placeholder="Enter password"
class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-blue-400 focus:outline-none">

<i
class="fa fa-eye toggle-password absolute right-4 top-4 cursor-pointer text-gray-500"
data-target="password">
</i>

</div>
<small class="error-message text-red-500 text-sm mt-2 block">
<?= $errors['password'] ?? '' ?>
</small>

</div>


<div>

<label class="block font-semibold mb-2">

Confirm Password

</label>

<div class="relative">

<input
type="password"
id="confirm-password"
name="confirm_password"

placeholder="Confirm password"
class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-blue-400 focus:outline-none">

<i
class="fa fa-eye toggle-password absolute right-4 top-4 cursor-pointer text-gray-500"
data-target="confirm-password">
</i>

</div>

<small class="error-message text-red-500 text-sm mt-2 block">
<?= $errors['confirm_password'] ?? '' ?>
</small>

</div>

<div>

<label class="block font-semibold mb-2">

Education Level

</label>

<select
id="education"
name="education"

class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400">

<option value="">Select Education</option>

<option
value="8"
<?= FormHelper::selected($old,'education','8') ?>>
High School
</option>

<option
value="9"
<?= FormHelper::selected($old,'education','9') ?>>
Undergraduate
</option>

<option
value="10"
<?= FormHelper::selected($old,'education','10') ?>>
Graduate
</option>

</select>


<small class="error-message text-red-500 text-sm mt-2 block">
<?= $errors['education'] ?? '' ?>
</small>

</div>

<div>

<label class="block font-semibold mb-2">

Date of Birth

</label>

<input
id="dob"
type="date"
name="dob"

value="<?= FormHelper::old($old,'dob')?>"
class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400">


<small class="error-message text-red-500 text-sm mt-2 block">
<?= $errors['dob'] ?? '' ?>
</small>
</div>

<div class="md:col-span-2">

<label class="block font-semibold mb-2">

Gender

</label>

<select
id="gender"
name="gender"

class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400">

<option value="">Select Gender</option>

<option
value="5"
<?= FormHelper::selected($old,'gender','5') ?>>
Male
</option>

<option
value="6"
<?= FormHelper::selected($old,'gender','6') ?>>
Female
</option>

<option
value="7"
<?= FormHelper::selected($old,'gender','7') ?>>
Other
</option>

</select>

<small class="error-message text-red-500 text-sm mt-2 block">
<?= $errors['gender'] ?? '' ?>
</small>
</div>


<div class="md:col-span-2">

    <div class="flex items-start gap-3">

    <input
    type="checkbox"
    id="terms"
    name="terms"
    value="1">

        <p class="text-gray-600 text-sm">

            I agree to the

            <a href="#" class="text-blue-600 font-semibold">
                Terms
            </a>

            and

            <a href="#" class="text-blue-600 font-semibold">
                Privacy Policy
            </a>

        </p>

    </div>

    <small
        id="terms-error"
        class="text-red-500 text-sm mt-2 block">
    </small>

</div>


<div class="md:col-span-2">

<button
type="submit"
class="w-full bg-gradient-to-r from-blue-700 to-indigo-700
hover:from-indigo-700
hover:to-blue-700
text-white
py-4
rounded-xl
font-semibold
transition
duration-300">

Create Account

</button>

</div>

<div class="md:col-span-2">

<div class="flex items-center my-5">

<hr class="flex-1">

<span class="mx-4 text-gray-400">OR</span>

<hr class="flex-1">

</div>

<a
href="/career-guidance-system/Public/index.php?page=google-login"
class="w-full border border-gray-200 rounded-xl py-4 flex items-center justify-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">

<img
src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
class="w-6">

Continue with Google

</a>

</div>

<div class="md:col-span-2 text-center mt-6">

Already have an account?

<a
href="index.php?page=login"
class="text-[#15479A] font-semibold">

Login

</a>

</div>



</form>

</div>

</div>




</div>

</div>



    </main>

    
<?php
$extraJs = "/career-guidance-system/Public/assets/js/register.js";
require BASE_PATH . "/App/Views/footer.php";
?>

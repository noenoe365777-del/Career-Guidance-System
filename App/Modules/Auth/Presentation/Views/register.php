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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Career Guidance System</title>

    <link rel="stylesheet" href="/career-guidance-system/Public/assets/css/style.css">
    <link rel="stylesheet" href="/career-guidance-system/Public/assets/css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header class="navbar">
    <div class="logo" onclick="window.location.href='index.php?page=home'" style="cursor:pointer;">
        <div class="logo-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="logo-text">
            <h2>Career Guidance System</h2>
            <span>FOR STUDENTS</span>
        </div>
    </div>

    <nav class="nav-links">
        <a href="index.php?page=home">Home</a>
        <a href="index.php?page=assessments">Assessments</a>
        <a href="index.php?page=careers">Careers</a>
        <a href="index.php?page=about">About Us</a>
        <a href="index.php?page=contact">Contact Us</a>
    </nav>

    <div class="auth-buttons">
        <a href="index.php?page=login" class="btn-login">
            <i class="far fa-user"></i> Login
        </a>
        <a href="index.php?page=register" class="btn-register active-auth">
            <i class="fas fa-user-plus"></i> Register
        </a>
    </div>
</header>

<main class="login-wrapper register-page">
    <div class="login-left">
        <div class="banner-content">
            <h2>Build your future<br>with the right guidance</h2>
            <p>
                Create your account and take assessments to discover careers
                that match your interests, skills, and strengths.
            </p>

            <div class="vector-placeholder">
                <img src="/career-guidance-system/Public/assets/images/login.png" alt="Student studying">
            </div>
        </div>
    </div>

    <div class="login-right">
        <div class="form-container">

            <div class="avatar-icon">
                <i class="far fa-user"></i>
            </div>

            <h1 class="register-title">Create Your Account</h1>
            <p class="form-subtitle">Join Career Guidance for Students</p>

            <?php if ($errors['register'] ?? false): ?>
                <div class="alert alert-error"><?= htmlspecialchars($errors['register']) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

           <form
    id="registerForm"
    action="index.php?page=register"
    method="POST"
    novalidate
>

                <div class="input-group">
                    <label for="fullname">Username</label>
                   <div class="input-field <?= FormHelper::hasError($errors, 'username') ?>">
                        <i class="far fa-user field-icon"></i>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Enter your  username"
                        value="<?= FormHelper::old($old, 'username') ?>"
                        required
                        >
                    </div>
                    <?= FormHelper::error($errors, 'username') ?>
                </div>

                <div class="input-group">
                    <label for="email">Email Address</label>
                    <div class="input-field <?= FormHelper::hasError($errors, 'email') ?>">
                        <i class="far fa-envelope field-icon"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                            value="<?= FormHelper::old($old, 'email') ?>"
                            required
                        
                        >
                    </div>
                    <?= FormHelper::error($errors, 'email') ?>
                </div>

    <div class="input-group">

<label>Password</label>

<div class="input-field <?= FormHelper::hasError($errors,'password') ?>">

<i class="fas fa-lock field-icon"></i>

<input
type="password"
id="password"
name="password"
placeholder="Create a password"
required
minlength="8"
>

<i class="far fa-eye toggle-password" data-target="password"></i>

</div>

<?= FormHelper::error($errors,'password') ?>

</div>

<div class="input-group">

<label>Confirm Password</label>

<div class="input-field <?= FormHelper::hasError($errors,'confirm_password') ?>">

<i class="fas fa-lock field-icon"></i>

<input
type="password"
id="confirm-password"
name="confirm_password"
placeholder="Confirm your password"
required
>

<i class="far fa-eye toggle-password" data-target="confirm-password"></i>

</div>

<?= FormHelper::error($errors,'confirm_password') ?>

</div>



           <div class="input-group">

<label>Education Level</label>

<div class="input-field select-field <?= FormHelper::hasError($errors,'education') ?>">

<select id="education" name="education" required>

<option value="">Select your education level</option>

<option value="8"
<?= FormHelper::selected($old,'education','8') ?>>
High School
</option>

<option value="9"
<?= FormHelper::selected($old,'education','9') ?>>
Undergraduate
</option>

<option value="10"
<?= FormHelper::selected($old,'education','10') ?>>
Graduate
</option>

</select>

<i class="fas fa-chevron-down select-icon"></i>

</div>

<?= FormHelper::error($errors,'education') ?>

</div>



               <div class="input-group">

<label>Date of Birth</label>

<div class="input-field <?= FormHelper::hasError($errors,'dob') ?>">

<input
type="date"
id="dob"
name="dob"
value="<?= FormHelper::old($old,'dob') ?>"
required
>

</div>

<?= FormHelper::error($errors,'dob') ?>

</div>



             <div class="input-group">

<label>Gender</label>

<div class="input-field select-field <?= FormHelper::hasError($errors,'gender') ?>">

<select
id="gender"
name="gender"
required
>

<option value="">Select Gender</option>

<option value="5"
<?= FormHelper::selected($old,'gender','5') ?>>
Male
</option>

<option value="6"
<?= FormHelper::selected($old,'gender','6') ?>>
Female
</option>

<option value="7"
<?= FormHelper::selected($old,'gender','7') ?>>
Other
</option>
</select>

<i class="fas fa-chevron-down select-icon"></i>

</div>

<?= FormHelper::error($errors,'gender') ?>

</div>

                <div class="terms-wrap">
                    <label class="terms-label">
                        <input type="checkbox" required>
                        <span>
                            I agree to the
                            <a href="#">Terms of Service</a>
                            and
                            <a href="#">Privacy Policy</a>.
                        </span>
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    Create Account
                </button>
            </form>

            <div class="divider">
                <span>OR</span>
            </div>
<a href="/career-guidance-system/Public/index.php?page=google-login" class="btn-google">
    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" class="google-icon">
    <span>Register with Google</span>
</a>

            <p class="switch-auth">
                Already have an account?
                <a href="index.php?page=login">Log in</a>
            </p>
        </div>
    </div>
</main>

<footer class="footer-bar">
    © 2026 Career Guidance System. All Rights Reserved.
</footer>

<script>
document.querySelectorAll('.toggle-password').forEach(toggle => {
    toggle.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        if (!input) return;

        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});

const registerForm = document.getElementById("registerForm");

registerForm.addEventListener("submit", function(e){

    const username = document.getElementById("username");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm-password");
    const education = document.getElementById("education");
    const dob = document.getElementById("dob");
    const gender = document.getElementById("gender");

    // If EVERYTHING is empty,
    // let PHP validate
    if(
        username.value.trim() === "" &&
        email.value.trim() === "" &&
        password.value.trim() === "" &&
        confirmPassword.value.trim() === "" &&
        education.value === "" &&
        dob.value === "" &&
        gender.value === ""
    ){
        return;
    }

    // Otherwise use browser validation

    if(!username.checkValidity()){
        username.reportValidity();
        e.preventDefault();
        return;
    }

    if(!email.checkValidity()){
        email.reportValidity();
        e.preventDefault();
        return;
    }

    if(!password.checkValidity()){
        password.reportValidity();
        e.preventDefault();
        return;
    }

    if(!confirmPassword.checkValidity()){
        confirmPassword.reportValidity();
        e.preventDefault();
        return;
    }

    if(!education.checkValidity()){
        education.reportValidity();
        e.preventDefault();
        return;
    }

    if(!dob.checkValidity()){
        dob.reportValidity();
        e.preventDefault();
        return;
    }

    if(!gender.checkValidity()){
        gender.reportValidity();
        e.preventDefault();
        return;
    }
});
</script>

</body>
</html>
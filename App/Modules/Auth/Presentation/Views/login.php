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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Career Guidance System</title>

    <!-- Shared Home CSS -->
    <link rel="stylesheet" href="/career-guidance-system/Public/assets/css/style.css">

    <!-- Login Page CSS -->
    <link rel="stylesheet" href="/career-guidance-system/Public/assets/css/login.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="page-wrapper">

    <!-- =========================
         NAVBAR (same as home page)
    ========================== -->
    <header class="navbar">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text">
                <h2>Career Guidance System</h2>
                <span>FOR STUDENTS</span>
            </div>
        </div>

        <nav class="nav-links">
            <a href="/career-guidance-system/Public/index.php?page=home">Home</a>
            <a href="/career-guidance-system/Public/index.php?page=assessments">Assessments</a>
            <a href="/career-guidance-system/Public/index.php?page=careers">Careers</a>
            <a href="/career-guidance-system/Public/index.php?page=about">About Us</a>
            <a href="/career-guidance-system/Public/index.php?page=contact">Contact Us</a>
        </nav>

        <div class="auth-buttons">
            <a href="/career-guidance-system/Public/index.php?page=login" class="btn-login">
                <i class="fas fa-user"></i> Login
            </a>
            <a href="/career-guidance-system/Public/index.php?page=register" class="btn-register">
                <i class="fas fa-user-plus"></i> Register
            </a>
        </div>
    </header>

    <!-- =========================
         LOGIN CONTENT
    ========================== -->
    <main class="login-page">
        <div class="login-container">

            <!-- LEFT SIDE -->
            <div class="login-left">
                <h1>Build your future<br>with the right guidance</h1>
                <p>
                    Login to continue your assessments, recommendations,
                    and career planning journey.
                </p>

                <div class="login-illustration">
                    <img src="/career-guidance-system/Public/assets/images/login.png" alt="Student studying">
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="login-right">
                <div class="login-card">

                    <div class="login-icon">
                        <i class="far fa-user"></i>
                    </div>

                    <h2>Welcome Back!</h2>
                    <p class="login-subtitle">Login to continue to your account</p>

                    

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

     <form
    id="loginForm"
    action="/career-guidance-system/Public/index.php?page=login"
    method="POST"
    class="login-form"
    novalidate
>
                   <div class="form-group">
    <label for="email">Email Address</label>

    <div class="input-box <?= FormHelper::hasError($errors, 'email') ?>">
        <i class="far fa-envelope"></i>
<input
    type="text"
    id="email"
    name="email"
    placeholder="Enter your email"
 value="<?= FormHelper::old($old, 'email') ?>"
 >
   

</div>
<?= FormHelper::error($errors, 'email') ?>
</div>


<div class="form-group">
    <label for="password">Password</label>

    <div class="input-box password-box <?= FormHelper::hasError($errors, 'password') ?>">
        <i class="fas fa-lock"></i>

        <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter your password"
        >

        <span class="toggle-password" id="togglePass">
            <i class="far fa-eye"></i>
        </span>
    </div>

    <?= FormHelper::error($errors, 'password') ?>
</div>

                        <div class="login-options">
                            <label class="remember-me">
                                <input type="checkbox" name="remember">
                                <span>Remember me</span>
                            </label>
                            <a href="#" class="forgot-link">Forgot Password?</a>
                        </div>

                        <button type="submit" class="login-btn">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </form>

              <div class="divider">
    <span>OR</span>
</div>

<a href="/career-guidance-system/Public/index.php?page=google-login" class="btn-google-login">
    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" class="google-icon">
    <span>Continue with Google</span>
</a>

<p class="register-link">
    Don't have an account?
    <a href="/career-guidance-system/Public/index.php?page=register">Register here</a>
</p>
                </div>
            </div>

        </div>
    </main>

    <!-- =========================
         FOOTER (same as home page)
    ========================== -->
    <footer class="footer">
        © 2026 Career Guidance System. All Rights Reserved.
    </footer>

</div>

<script>
const togglePass = document.getElementById('togglePass');
const passwordInput = document.getElementById('password');

if (togglePass && passwordInput) {
    togglePass.addEventListener('click', () => {
        const icon = togglePass.querySelector('i');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'text') {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
}


</script>

</body>
</html>
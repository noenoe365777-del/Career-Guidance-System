<?php
$user = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Guidance System</title>

    <link rel="stylesheet" href="/career-guidance-system/Public/assets/css/style.css?v=3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

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
        <a href="index.php?page=home" class="active">Home</a>
        <a href="index.php?page=assessments">Assessments</a>
        <a href="index.php?page=careers">Careers</a>
        <a href="index.php?page=about">About Us</a>
        <a href="index.php?page=contact">Contact Us</a>
    </nav>

    <div class="auth-buttons">
        <?php if ($user): ?>
            <div class="user-dropdown" id="userDropdown">
                <button type="button" class="user-dropdown-btn" id="userDropdownBtn">
                    <div class="user-info">
                        <span class="user-name"><?= htmlspecialchars($user['full_name'] ?? 'Student') ?></span>
                        <span class="user-role"><?= htmlspecialchars($user['education_level'] ?? $user['role'] ?? 'Student') ?></span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </button>

                <div class="dropdown-menu">
                    <a href="index.php?page=profile">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <a href="index.php?page=logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <a href="index.php?page=login" class="btn-login">
                <i class="fas fa-user"></i> Login
            </a>
            <a href="index.php?page=register" class="btn-register">
                <i class="fas fa-user-plus"></i> Register
            </a>
        <?php endif; ?>
    </div>
</header>

<section class="hero-container">
    <div class="hero-content">
        <h1>Discover the Right <br> Career Path for You</h1>

        <p class="hero-subtitle">
            Take assessments, explore career options, and get
            personalized guidance to build a successful future.
        </p>

        <div class="hero-actions">
            <a href="#" class="btn-primary">Take Assessment</a>
            <a href="#" class="btn-secondary">Explore Careers</a>
        </div>

        <div class="value-props">
            <div class="prop-item">
                <span class="icon-circle icon-blue">
                    <i class="fas fa-bars"></i>
                </span>
                <div>
                    <h4>Discover Your Strengths</h4>
                    <p>Scientifically designed assessments</p>
                </div>
            </div>

            <div class="prop-item">
                <span class="icon-circle icon-green">
                    <i class="fas fa-chart-bar"></i>
                </span>
                <div>
                    <h4>Personalized Analysis</h4>
                    <p>Detailed insights about your skills</p>
                </div>
            </div>

            <div class="prop-item">
                <span class="icon-circle icon-pink">
                    <i class="far fa-circle"></i>
                </span>
                <div>
                    <h4>Career Recommendations</h4>
                    <p>Find best paths that match profile</p>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-image">
        <img src="/career-guidance-system/Public/assets/images/home.png" alt="Graduate">
    </div>
</section>

<section class="how-it-works">
    <h2>How It Works</h2>

    <div class="steps-container">
        <div class="step-card">
            <span class="step-num step-1">1</span>
            <h3>Take Assessment</h3>
            <p>Answer questions about interests and personality.</p>
        </div>

        <div class="step-arrow">→</div>

        <div class="step-card">
            <span class="step-num step-2">2</span>
            <h3>Get Your Results</h3>
            <p>Our system analyzes your responses and generates metrics.</p>
        </div>

        <div class="step-arrow">→</div>

        <div class="step-card">
            <span class="step-num step-3">3</span>
            <h3>View Recommendations</h3>
            <p>Get personalized career options matching your profile.</p>
        </div>

        <div class="step-arrow">→</div>

        <div class="step-card">
            <span class="step-num step-4">4</span>
            <h3>Explore Career Details</h3>
            <p>Explore structural data, salaries, and growth trends.</p>
        </div>
    </div>
</section>

<footer class="footer">
    © 2026 Career Guidance System. All Rights Reserved.
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.getElementById('userDropdown');
    const button = document.getElementById('userDropdownBtn');

    if (dropdown && button) {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('active');
        });

        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    }
});
</script>

</body>
</html>
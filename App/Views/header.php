<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $pageTitle ?? 'Career Guidance System' ?></title>

<link rel="stylesheet" href="/career-guidance-system/Public/assets/css/style.css">

<?php if(isset($extraCss)): ?>
<link rel="stylesheet" href="<?= $extraCss ?>">
<?php endif; ?>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>

<header class="navbar">

    <div class="logo"
         onclick="window.location.href='index.php?page=home'"
         style="cursor:pointer;">

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

<?php if(isset($_SESSION['user'])): ?>

        <a href="index.php?page=profile" class="btn-login">
            <i class="fas fa-user"></i> Profile
        </a>

        <a href="index.php?page=logout" class="btn-register">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>

<?php else: ?>

        <a href="index.php?page=login" class="btn-login">
            <i class="far fa-user"></i> Login
        </a>

        <a href="index.php?page=register" class="btn-register">
            <i class="fas fa-user-plus"></i> Register
        </a>

<?php endif; ?>

    </div>

</header>
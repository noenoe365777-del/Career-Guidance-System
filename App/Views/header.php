
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

    <?php $user = $_SESSION['user'] ?? null; ?>

<div class="auth-buttons">

<?php if ($user): ?>

<div class="user-dropdown" id="userDropdown">

    <button type="button"
            class="user-dropdown-btn"
            id="userDropdownBtn">

        <div class="user-info">
            <span class="user-name">
                <?= htmlspecialchars($user['username']) ?>
            </span>

            <span class="user-role">
                <?= htmlspecialchars($user['role_name'] ?? 'Student') ?>
            </span>
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
    <i class="far fa-user"></i> Login
</a>

<a href="index.php?page=register" class="btn-register">
    <i class="fas fa-user-plus"></i> Register
</a>

<?php endif; ?>

</div>
    

</header>
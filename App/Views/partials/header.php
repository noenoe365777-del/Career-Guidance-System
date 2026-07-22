<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $pageTitle ?? 'Career Guidance System' ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

    <?php if (isset($extraCss)): ?>
        <link rel="stylesheet" href="<?= $extraCss ?>">
    <?php endif; ?>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/custom.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/animation.css">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            start: '#2563eb',
                            mid: '#4f46e5',
                            end: '#6366f1'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans bg-slate-50/50 text-slate-900 min-h-screen flex flex-col antialiased overflow-x-hidden" data-page="<?= $_GET['page'] ?? 'home' ?>">

<?php 
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null; 

// Track the current page safely to determine active navigation elements
$currentPage = $_GET['page'] ?? 'home';

$isLoggedIn = !empty($_SESSION['user_id']);
$assessmentsPage = $isLoggedIn ? 'student-assessments-v2' : 'assessments';

$navItems = [
    'home' => 'Home',
    $assessmentsPage => 'Assessments',
    'careers' => 'Careers',
    'about-us' => 'About ',
    'contact' => 'Contact'
];
?>

<!-- ================= HEADER ================= -->
<header class="sticky top-0 z-50 bg-white/75 backdrop-blur-md border-b border-slate-100">
    <div class="max-w-7xl mx-auto h-20 px-4 sm:px-6 flex items-center justify-between">

        <!-- Logo Section -->
        <a href="<?= BASE_URL ?>/index.php?page=home" class="flex items-center gap-3.5 group">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-brand-start via-brand-mid to-brand-end flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-105 group-hover:rotate-3 transition-all duration-300 ease-out">
                <i class="fas fa-graduation-cap text-white text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-extrabold tracking-tight bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 bg-clip-text text-transparent">
                    Career Guidance
                </h2>
                <p class="text-[10px] tracking-[0.25em] uppercase text-indigo-600 font-bold">
                    For Students
                </p>
            </div>
        </a>

        <!-- Desktop Navigation Matrix -->
        <nav class="hidden lg:flex items-center gap-1 font-medium text-sm">
            <?php foreach ($navItems as $key => $label): 
                $isActive = ($currentPage === $key);
            ?>
                <a href="<?= BASE_URL ?>/index.php?page=<?= $key ?>" 
                   class="px-4 py-2 rounded-lg relative group transition-all duration-200 
                   <?= $isActive 
                       ? 'text-indigo-600 bg-indigo-50/50 font-semibold' 
                       : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' 
                   ?>">
                    <?= $label ?>
                    <!-- Animated bottom indicator bar -->
                    <span class="absolute bottom-1.5 left-4 right-4 h-0.5 bg-indigo-600 rounded-full transition-transform duration-200 origin-left 
                        <?= $isActive ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' ?>">
                    </span>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Right Action Interface -->
        <div class="flex items-center gap-3">
            <!-- Admin Login link removed from public header (admin portal will be on separate subdomain) -->

            <?php if($user): ?>
                <!-- Authed Profile Container Wrapper -->
                <div class="relative hidden lg:block" id="userDropdownWrapper">
                    <div id="userDropdownTrigger" class="flex items-center gap-4 bg-white border border-slate-200/60 rounded-3xl p-2 pr-5 shadow-sm hover:bg-slate-50/50 transition-all duration-200 cursor-pointer select-none">
                        
                        <!-- Squircle Avatar Component -->
                        <div class="pointer-events-none w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-base shadow-sm shadow-indigo-500/10">
                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                        </div>

                        <!-- Profile Metadata Details -->
                        <div class="pointer-events-none text-left pr-2">
                            <div class="font-bold text-[15px] text-slate-800 tracking-tight leading-tight">
                                <?= htmlspecialchars($user['username']) ?>
                            </div>
                            <div class="text-xs text-slate-400 font-medium mt-0.5">
                                <?= htmlspecialchars($user['role_name'] ?? 'Student') ?>
                            </div>
                        </div>

                        <!-- Caret Icon Indicator -->
                        <i class="pointer-events-none fas fa-chevron-down text-xs text-slate-400/80 transition-transform duration-200" id="dropdownArrow"></i>
                    </div>

                    <!-- Dropdown Panel -->
                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden z-50">
                        <a href="<?= BASE_URL ?>/index.php?page=profile" class="flex items-center gap-3 px-5 py-3.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors <?= ($currentPage === 'profile') ? 'bg-slate-50 font-semibold text-slate-900' : '' ?>">
                            <i class="fas fa-user text-slate-400 text-xs w-4"></i> Profile
                        </a>
                        <div class="border-t border-slate-100"></div>
                        <a href="<?= BASE_URL ?>/index.php?page=logout" class="flex items-center gap-3 px-5 py-3.5 text-sm text-red-600 hover:bg-red-50/60 transition-colors">
                            <i class="fas fa-sign-out-alt text-red-400 text-xs w-4"></i> Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
<a href="<?= BASE_URL ?>/index.php?page=login"
   class="hidden lg:inline-flex items-center text-blue-600 px-4 py-2.5 rounded-xl font-semibold text-sm border-2 border-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300">
    Log In
</a>
                <a href="<?= BASE_URL ?>/index.php?page=register" class="hidden lg:inline-flex items-center bg-gradient-to-r from-brand-start to-brand-mid text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-md hover:scale-105 transition-all duration-200">
                   <i class="fas fa-user-plus mr-2"></i>
Register
                </a>
            <?php endif; ?>

            <!-- Mobile Menu Toggle Button -->
            <button id="mobileMenuBtn" class="lg:hidden w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all">
                <i class="fas fa-bars text-sm" id="menuIcon"></i>
            </button>
        </div>
    </div>
</header>

<!-- ================= MOBILE MENU CONTAINER ================= -->
<div id="mobileMenu" class="hidden lg:hidden fixed inset-x-0 top-20 bg-white border-b border-slate-200 shadow-xl px-4 py-6 space-y-1 z-40">
    
    <?php foreach ($navItems as $key => $label): 
        $icons = ['home' => 'house', 'assessments' => 'file-alt', 'student-assessments' => 'file-alt', 'student-assessments-v2' => 'file-alt', 'careers' => 'briefcase', 'about-us' => 'circle-info', 'contact' => 'envelope'];
        $isActive = ($currentPage === $key);
    ?>
        <a href="<?= BASE_URL ?>/index.php?page=<?= $key ?>" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium transition-all
           <?= $isActive 
               ? 'bg-indigo-50 text-indigo-600 font-bold' 
               : 'text-slate-600 hover:bg-indigo-50/40 hover:text-indigo-600' 
           ?>">
            <i class="fas fa-<?= $icons[$key] ?? 'link' ?> w-5 text-center text-sm <?= $isActive ? 'text-indigo-600' : 'opacity-70' ?>"></i>
            <span><?= $label ?></span>
        </a>
    <?php endforeach; ?>

    <div class="border-t border-slate-200 my-4"></div>

    <?php if($user): ?>
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3 mb-2 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-start to-brand-mid flex items-center justify-center text-white font-bold">
                <?= strtoupper(substr($user['username'],0,1)) ?>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm leading-tight"><?= htmlspecialchars($user['username']) ?></h4>
                <p class="text-xs text-slate-400 font-medium mt-0.5"><?= htmlspecialchars($user['role_name'] ?? 'Student') ?></p>
            </div>
        </div>
        
        <a href="<?= BASE_URL ?>/index.php?page=profile" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium transition-all <?= ($currentPage === 'profile') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50' ?>">
            <i class="fas fa-user w-5 text-center text-sm <?= ($currentPage === 'profile') ? 'text-indigo-600' : 'opacity-70' ?>"></i>
            <span>My Profile</span>
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium transition-all <?= ($currentPage === 'logout') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50' ?>">
            <i class="fas fa-sign-out-alt w-5 text-center text-sm"></i>
            <span>Log Out</span>
        </a>
    <?php else: ?>
        <div class="space-y-2 pt-2">
            <!-- Admin Login intentionally omitted from public mobile menu (admin portal is separate) -->
            <div class="grid grid-cols-2 gap-3">
                <a href="<?= BASE_URL ?>/index.php?page=login" class="w-full text-center border border-slate-200 text-slate-700 font-semibold rounded-xl py-3 text-sm hover:bg-slate-50 transition-all">
                    Log In
                </a>
                <a href="<?= BASE_URL ?>/index.php?page=register" class="w-full text-center bg-gradient-to-r from-brand-start to-brand-mid text-white font-semibold rounded-xl py-3 text-sm shadow-md transition-all">
                    Register
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Refined Interaction Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileTrigger = document.getElementById('userDropdownTrigger');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownArrow = document.getElementById('dropdownArrow');
        const dropdownWrapper = document.getElementById('userDropdownWrapper');

        const menuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = document.getElementById('menuIcon');

        // Toggle Desktop Dropdown Panel Display
        if (profileTrigger && dropdownMenu) {
            profileTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
                if (dropdownArrow) {
                    dropdownArrow.classList.toggle('rotate-180');
                }
            });
        }

        // Toggle Mobile Navigation Drawer
        if(menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                mobileMenu.classList.toggle('hidden');
                if (mobileMenu.classList.contains('hidden')) {
                    menuIcon.className = 'fas fa-bars text-sm';
                } else {
                    menuIcon.className = 'fas fa-xmark text-sm';
                }
            });
        }

        // Close UI components if user clicks outside them
        document.addEventListener('click', function(e) {
            if (dropdownWrapper && !dropdownWrapper.contains(e.target)) {
                if (dropdownMenu) dropdownMenu.classList.add('hidden');
                if (dropdownArrow) dropdownArrow.classList.remove('rotate-180');
            }
            if (mobileMenu && !mobileMenu.contains(e.target) && !menuBtn.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                if (menuIcon) menuIcon.className = 'fas fa-bars text-sm';
            }
        });
    });
</script>
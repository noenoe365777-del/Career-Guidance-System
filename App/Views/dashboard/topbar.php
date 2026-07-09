<?php
$currentPage = $currentPage ?? ($_GET['page'] ?? 'dashboard');
$pageTitles = [
    'dashboard' => 'Dashboard',
    'assessments' => 'Assessments',
    'recommendation' => 'Career Maps',
    'profile' => 'Profile',
    'change-password' => 'Settings',
    'edit-profile' => 'Edit Profile'
];
$currentPageLabel = $pageTitles[$currentPage] ?? ($pageTitle ?? 'Dashboard');

$studentName = trim((string)($_SESSION['user']['username'] ?? 'Student'));
?>

<nav class="sticky top-0 z-40 bg-white border-b border-slate-100">
    <div class="px-6 lg:px-8 h-16 flex items-center justify-between">
        
        <div class="flex items-center gap-3">
            <button id="open-sidebar-btn" class="lg:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-slate-50 border-0 bg-transparent transition-colors duration-200 outline-none" type="button" aria-label="Toggle sidebar">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <h1 class="text-base font-bold text-slate-800 tracking-tight m-0 hidden md:block"><?= htmlspecialchars($currentPageLabel) ?></h1>
        </div>

        <div class="flex items-center gap-4">
            
            <button class="relative p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all duration-200 border-0 bg-transparent outline-none group" type="button" aria-label="Notifications">
                <i class="fas fa-bell text-xl transition-transform duration-200 group-hover:scale-105"></i>
                <span class="absolute top-2 right-2 flex h-2 w-2 items-center justify-center rounded-full bg-blue-500 ring-2 ring-white"></span>
            </button>

            <div class="relative">
                <button class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-slate-50/80 transition-all duration-200 border-0 bg-transparent text-left outline-none" type="button" id="userDropdownBtn" aria-expanded="false">
                    
                    <div class="flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600 font-semibold shrink-0 w-8 h-8">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    
                    <div class="hidden sm:flex items-center gap-1 select-none">
                        <span class="text-xs font-semibold text-slate-700 max-w-[120px] truncate">
                            <?= htmlspecialchars($studentName) ?>
                        </span>
                        <i class="fas fa-chevron-down text-[9px] text-slate-400" id="userDropdownChevron"></i>
                    </div>
                </button>

                <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-2xl shadow-xl p-1.5 space-y-0.5 z-50">
                    <a href="<?= BASE_URL ?>/index.php?page=profile" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-colors duration-150 no-underline">
                        <i class="fas fa-user text-sm text-slate-400"></i>
                        <span>My Profile</span>
                    </a>
                    <div class="border-t border-slate-50 my-1"></div>
                    <a href="<?= BASE_URL ?>/index.php?page=logout" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50/50 transition-colors duration-150 no-underline">
                        <i class="fas fa-sign-out-alt text-sm opacity-80"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownBtn = document.getElementById('userDropdownBtn');
    const dropdownMenu = document.getElementById('userDropdownMenu');
    const chevron = document.getElementById('userDropdownChevron');

    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
            if (chevron) chevron.classList.toggle('rotate-180');
        });

        document.addEventListener('click', function(e) {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        });
    }
});
</script>
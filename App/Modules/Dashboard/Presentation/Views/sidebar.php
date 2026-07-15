<?php
// Minimal sidebar for dashboard views
$userName = $user['name'] ?? $_SESSION['user_name'] ?? 'Student';
?>
<style>
    .bg-custom-gradient {
        background: linear-gradient(135deg, #5d5bf6 0%, #3b39df 100%) !important;
    }
    .sidebar-link {
        transition: all 0.2s cubic-bezier(0.25, 1, 0.5, 1);
    }
    .sidebar-link:hover {
        transform: translateX(4px);
    }
    .sidebar-link-active {
        transform: translateX(4px);
    }
</style>
<aside class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-100 p-6 hidden lg:flex lg:flex-col">
    <div class="flex items-center gap-3 mb-8">
        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600">
            <i class="bi bi-mortarboard-fill text-xl"></i>
        </div>
        <div>
            <div class="text-sm font-semibold">Career Guidance</div>
            <div class="text-xs text-slate-400">System</div>
        </div>
    </div>

    <nav class="flex-1 space-y-3">
        <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="sidebar-link flex items-center gap-3 p-3 rounded-xl text-sm font-semibold no-underline group text-white bg-custom-gradient shadow-md sidebar-link-active">
            <i class="bi bi-house text-xl text-white"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="sidebar-link flex items-center gap-3 p-3 rounded-xl text-sm font-semibold no-underline group text-slate-600 hover:text-indigo-600 hover:bg-slate-50">
            <i class="bi bi-clipboard-check text-xl text-slate-500 group-hover:text-indigo-600"></i>
            <span>Assessments</span>
        </a>

        <a href="<?= BASE_URL ?>/index.php?page=careers" class="sidebar-link flex items-center gap-3 p-3 rounded-xl text-sm font-semibold no-underline group text-slate-600 hover:text-indigo-600 hover:bg-slate-50">
            <i class="bi bi-briefcase text-xl text-slate-500 group-hover:text-indigo-600"></i>
            <span>Careers</span>
        </a>

        <a href="<?= BASE_URL ?>/index.php?page=profile" class="sidebar-link flex items-center gap-3 p-3 rounded-xl text-sm font-semibold no-underline group text-slate-600 hover:text-indigo-600 hover:bg-slate-50">
            <i class="bi bi-person text-xl text-slate-500 group-hover:text-indigo-600"></i>
            <span>Profile</span>
        </a>

        <a href="<?= BASE_URL ?>/index.php?page=settings" class="sidebar-link flex items-center gap-3 p-3 rounded-xl text-sm font-semibold no-underline group text-slate-600 hover:text-indigo-600 hover:bg-slate-50">
            <i class="bi bi-gear text-xl text-slate-500 group-hover:text-indigo-600"></i>
            <span>Settings</span>
        </a>
    </nav>

    <div class="mt-6">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="flex items-center gap-3 text-red-600 font-semibold no-underline group">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

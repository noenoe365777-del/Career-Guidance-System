<?php
// Minimal sidebar for dashboard views
$userName = $user['name'] ?? $_SESSION['user_name'] ?? 'Student';
?>
<aside class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-100 p-6 hidden lg:flex lg:flex-col">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center">
            <i class="fas fa-paper-plane"></i>
        </div>
        <div>
            <div class="text-sm font-semibold">Career Guidance</div>
            <div class="text-xs text-slate-400">System</div>
        </div>
    </div>

    <nav class="flex-1 space-y-3">
        <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 text-blue-700">
            <i class="fas fa-home w-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="<?= BASE_URL ?>/index.php?page=assessments" class="flex items-center gap-3 p-3 rounded-lg text-slate-700 hover:bg-slate-50">
            <i class="fas fa-list w-5"></i>
            <span>Assessments</span>
        </a>

        <a href="<?= BASE_URL ?>/index.php?page=careers" class="flex items-center gap-3 p-3 rounded-lg text-slate-700 hover:bg-slate-50">
            <i class="fas fa-briefcase w-5"></i>
            <span>Careers</span>
        </a>

        <a href="<?= BASE_URL ?>/index.php?page=profile" class="flex items-center gap-3 p-3 rounded-lg text-slate-700 hover:bg-slate-50">
            <i class="fas fa-user w-5"></i>
            <span>Profile</span>
        </a>

        <a href="<?= BASE_URL ?>/index.php?page=settings" class="flex items-center gap-3 p-3 rounded-lg text-slate-700 hover:bg-slate-50">
            <i class="fas fa-cog w-5"></i>
            <span>Settings</span>
        </a>
    </nav>

    <div class="mt-6">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="flex items-center gap-3 text-red-600 font-semibold">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

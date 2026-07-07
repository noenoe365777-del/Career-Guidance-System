<?php
// Minimal topbar for dashboard views
$userName = $user['name'] ?? $_SESSION['user_name'] ?? 'Student';
?>
<header class="bg-white border-b border-slate-100 p-4 lg:ml-72">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="text-sm text-slate-500">Welcome back,</div>
            <div class="text-lg font-semibold text-slate-900"><?= htmlspecialchars($userName) ?></div>
        </div>

        <div class="flex items-center gap-4">
            <div class="relative">
                <button class="p-2 rounded-full bg-slate-100">
                    <i class="far fa-bell text-slate-600"></i>
                </button>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">2</span>
            </div>
            <div class="flex items-center gap-2">
                <img src="<?= BASE_URL ?>/assets/images/avatar.png" alt="avatar" class="w-9 h-9 rounded-full object-cover">
                <div class="text-sm text-slate-700">Student</div>
            </div>
        </div>
    </div>
</header>

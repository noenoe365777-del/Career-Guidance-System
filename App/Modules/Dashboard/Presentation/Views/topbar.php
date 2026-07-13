<?php
$userName = $user['name'] ?? $_SESSION['user_name'] ?? 'Student';
?>
<header class="border-b border-slate-100 bg-white p-4 lg:ml-72">
    <div class="mx-auto flex items-center justify-between">
        <div>
            <button id="open-sidebar-btn" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 lg:hidden">
                <i class="bi bi-list text-xl"></i>
            </button>
        </div>
        <div class="flex items-center gap-3">
            <button class="relative flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                <i class="bi bi-bell text-lg"></i>
                <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-red-500"></span>
            </button>
            <div class="flex items-center gap-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-sm">
                    <i class="bi bi-person-fill text-sm"></i>
                </div>
                <span class="hidden text-sm font-medium text-slate-700 sm:inline"><?= htmlspecialchars($userName) ?></span>
                <i class="bi bi-chevron-down hidden text-[10px] text-slate-400 sm:inline"></i>
            </div>
        </div>
    </div>
</header>

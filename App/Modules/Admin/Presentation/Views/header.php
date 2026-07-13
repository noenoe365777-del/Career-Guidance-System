<?php
$admin = $admin ?? ($_SESSION['admin'] ?? []);
$adminName = trim((string)($admin['full_name'] ?? $admin['username'] ?? 'Admin'));
$adminInitial = strtoupper(substr($adminName ?: 'A', 0, 1));
$headerTitle = $headerTitle ?? ($pageTitle ?? 'Dashboard');

$notifUnreadCount = 0;
try {
    $pdo = \App\Config\Database::getConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM notifications WHERE is_read = 0");
    $notifUnreadCount = (int)$stmt->fetchColumn();
} catch (\Throwable $e) {}
?>

<nav class="sticky top-0 z-40 bg-white border-b border-slate-100">    <div class="px-6 lg:px-8 h-16 flex items-center justify-between">

        <div class="flex items-center gap-3">
            <button class="md:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 border-0 bg-transparent transition-all duration-200 ease-out outline-none hover:scale-105 active:scale-95" 
                    type="button" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#adminSidebarMobile" 
                    aria-controls="adminSidebarMobile">
                <i class="bi bi-list text-xl"></i>
            </button>

            <h1 class="text-base font-bold text-slate-800 tracking-tight m-0 hidden md:block"><?= htmlspecialchars($headerTitle) ?></h1>
        </div>

        <div class="flex items-center gap-4">

            <a href="<?= BASE_URL ?>/index.php?page=admin-notifications"
               class="relative p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all duration-200 ease-out no-underline outline-none inline-flex items-center justify-center hover:scale-110 active:scale-95" 
               aria-label="Notifications">
                <i class="bi bi-bell text-xl transition-transform duration-200"></i>
                <?php if ($notifUnreadCount > 0): ?>
                <span class="notification-badge absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold leading-none shadow-sm ring-2 ring-white"><?= $notifUnreadCount ?></span>
                <?php endif; ?>
            </a>

            <div class="relative dropdown">
                <button class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-slate-50/80 transition-all duration-200 ease-out border-0 bg-transparent text-left outline-none hover:scale-[1.02] active:scale-[0.98]" 
                        type="button" 
                        id="userProfileDropdown" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">

                    <div class="flex items-center justify-center rounded-full bg-blue-50 text-blue-600 font-semibold shrink-0 w-8 h-8">
                        <i class="bi bi-person text-base"></i>
                    </div>

                    <div class="flex items-center gap-1 select-none">
                        <span class="text-xs font-semibold text-slate-700 max-w-[120px] truncate">
                            <?= htmlspecialchars($adminName ?: 'Admin') ?>
                        </span>
                        <i class="bi bi-chevron-down text-[9px] text-slate-400 transition-transform duration-200"></i>
                    </div>
                </button>

                <ul class="dropdown-menu dropdown-menu-end absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-2xl shadow-xl p-1.5 space-y-0.5 list-none m-0"
                    aria-labelledby="userProfileDropdown">
                    <li>
                        <a class="dropdown-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-50 transition-all duration-150 ease-out no-underline hover:translate-x-0.5" 
                           href="#profile">
                            <i class="bi bi-person text-sm text-slate-400"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li class="border-t border-slate-50 my-1"></li>
                    <li>
                        <a class="dropdown-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50/50 transition-all duration-150 ease-out no-underline hover:translate-x-0.5" 
                           href="<?= BASE_URL ?>/index.php?page=admin-logout">
                            <i class="bi bi-box-arrow-right text-sm opacity-80"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>
<?php
$admin = $admin ?? ($_SESSION['admin'] ?? []);
$rawName = trim((string)($admin['full_name'] ?? $admin['username'] ?? 'Admin'));
$adminName = strtolower($rawName) === 'admin' ? 'Admin' : $rawName;
$adminInitial = strtoupper(substr($adminName ?: 'A', 0, 1));
$breadcrumbLabel = $breadcrumbLabel ?? ($pageTitle ?? 'Dashboard');

$notifUnreadCount = 0;
try {
    $pdo = \App\Config\Database::getConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM notifications WHERE is_read = 0");
    $notifUnreadCount = (int)$stmt->fetchColumn();
} catch (\Throwable $e) {}
?>
<style>
    @keyframes headerFadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes breadcrumbFade { from { opacity: 0; transform: translateX(-6px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes dropdownOpen { from { opacity: 0; transform: translateY(-4px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .header-anim { animation: headerFadeIn 0.4s ease-out both; }
    .breadcrumb-anim { animation: breadcrumbFade 0.35s ease-out 0.1s both; }
    .dropdown-menu { animation: dropdownOpen 0.2s ease-out both; }
    .icon-hover { transition: all 0.2s ease; }
    .icon-hover:hover { transform: scale(1.12); }
    .icon-hover:active { transform: scale(0.95); }
    .profile-btn { transition: all 0.2s ease; }
    .profile-btn:hover { transform: scale(1.03); }
    .profile-btn:active { transform: scale(0.97); }
    .dropdown-item-link {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.5rem 0.75rem; border-radius: 0.65rem;
        font-size: 0.8rem; font-weight: 500;
        transition: all 0.15s ease; text-decoration: none;
        color: #475569;
    }
    .dropdown-item-link:hover {
        background: #f5f3ff;
        color: #5B5FEF;
        transform: translateX(3px);
    }
    .dropdown-item-link:hover i { color: #5B5FEF; }
    .dropdown-item-link.logout { color: #dc2626; }
    .dropdown-item-link.logout:hover { background: #fef2f2; color: #dc2626; }
    .dropdown-item-link.logout:hover i { color: #dc2626; }
</style>

<nav class="sticky top-0 z-40 bg-white border-b border-slate-100 header-anim">
    <div class="px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3 min-w-0">
            <button class="md:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 border-0 bg-transparent outline-none icon-hover"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#adminSidebarMobile"
                    aria-controls="adminSidebarMobile">
                <i class="bi bi-list text-xl"></i>
            </button>
            <div class="min-w-0 hidden md:block">
                <h1 class="text-base font-bold text-slate-800 tracking-tight m-0 leading-tight">Admin Dashboard</h1>
                <p class="breadcrumb-anim text-xs font-medium text-slate-400 m-0 mt-0.5 flex items-center gap-1">
                    <span>Home</span>
                    <i class="bi bi-chevron-right text-[9px] opacity-50"></i>
                    <span class="text-slate-500 font-semibold"><?= htmlspecialchars($breadcrumbLabel) ?></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="<?= BASE_URL ?>/index.php?page=admin-notifications"
               class="relative p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 no-underline outline-none inline-flex items-center justify-center icon-hover"
               aria-label="Notifications">
                <i class="bi bi-bell text-xl"></i>
                <?php if ($notifUnreadCount > 0): ?>
                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold leading-none shadow-sm ring-2 ring-white"><?= $notifUnreadCount ?></span>
                <?php endif; ?>
            </a>

            <div class="relative dropdown">
                <button class="profile-btn flex items-center gap-2.5 p-1 rounded-xl hover:bg-slate-50/80 border-0 bg-transparent text-left outline-none"
                        type="button"
                        id="userProfileDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <div class="flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 text-[#5B5FEF] font-bold shrink-0 w-8 h-8 text-xs shadow-sm ring-2 ring-white">
                        <?= htmlspecialchars($adminInitial) ?>
                    </div>
                    <div class="hidden sm:block min-w-0 max-w-[140px]">
                        <p class="text-sm font-semibold text-slate-700 truncate m-0 leading-tight"><?= htmlspecialchars($adminName) ?></p>
                        <p class="text-[10px] font-medium text-slate-400 m-0 leading-tight">Administrator</p>
                    </div>
                    <i class="bi bi-chevron-down text-[9px] text-slate-400 transition-transform duration-200"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end absolute right-0 mt-2 w-52 bg-white border border-slate-100 rounded-2xl shadow-xl p-1.5 space-y-0.5 list-none m-0"
                    aria-labelledby="userProfileDropdown">
                    <li>
                        <div class="px-3 py-2.5 border-b border-slate-50 mb-1">
                            <p class="text-sm font-bold text-slate-800 m-0"><?= htmlspecialchars($adminName) ?></p>
                            <p class="text-[11px] text-slate-400 m-0">Administrator</p>
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item-link" href="#profile">
                            <i class="bi bi-person text-sm text-slate-400"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-link" href="<?= BASE_URL ?>/index.php?page=admin-dashboard">
                            <i class="bi bi-house text-sm text-slate-400"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="border-t border-slate-100 my-1"></li>
                    <li>
                        <a class="dropdown-item-link logout" href="<?= BASE_URL ?>/index.php?page=admin-logout">
                            <i class="bi bi-box-arrow-right text-sm"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

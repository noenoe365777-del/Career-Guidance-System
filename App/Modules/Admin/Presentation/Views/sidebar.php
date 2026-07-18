<?php
$activeMenu = $activeMenu ?? 'dashboard';
$settingsExpanded = in_array($activeMenu, [
    'settings', 'role-permissions',
], true);

$unreadCount = 0;
try {
    $pdo = \App\Config\Database::getConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM notifications WHERE is_read = 0");
    $unreadCount = (int)$stmt->fetchColumn();
} catch (\Throwable $e) {
    $unreadCount = 0;
}

$menuItems = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-house', 'href' => BASE_URL . '/index.php?page=admin-dashboard'],
    ['key' => 'users', 'label' => 'Users', 'icon' => 'bi-people', 'href' => BASE_URL . '/index.php?page=admin-users'],
    ['key' => 'assessments', 'label' => 'Assessments', 'icon' => 'bi-clipboard-check', 'href' => BASE_URL . '/index.php?page=admin-assessments'],
    ['key' => 'questions', 'label' => 'Questions', 'icon' => 'bi-question-circle', 'href' => BASE_URL . '/index.php?page=admin-questions'],
    ['key' => 'careers', 'label' => 'Careers', 'icon' => 'bi-briefcase', 'href' => BASE_URL . '/index.php?page=admin-careers'],
    ['key' => 'reports', 'label' => 'Reports', 'icon' => 'bi-bar-chart', 'href' => BASE_URL . '/index.php?page=admin-reports'],
    ['key' => 'notifications', 'label' => 'Notifications', 'icon' => 'bi-bell', 'href' => BASE_URL . '/index.php?page=admin-notifications', 'badge' => $unreadCount > 0 ? $unreadCount : null],
    [
        'key' => 'settings',
        'label' => 'Settings',
        'icon' => 'bi-gear',
        'href' => '#',
        'children' => [
            ['key' => 'role-permissions', 'label' => 'Roles & Permissions', 'icon' => 'bi-shield-check', 'href' => BASE_URL . '/index.php?page=admin-role-permissions'],
        ],
    ],
];

function renderMenuItems(array $items, string $activeMenu, bool $settingsExpanded, string $prefix): string
{
    ob_start();
    foreach ($items as $item):
        $isActive = $activeMenu === $item['key'];
?>
        <div class="<?= $prefix === 'desktop' ? 'relative' : '' ?>">
            <?php if (!empty($item['children'])): ?>
                <button class="sidebar-link w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 no-underline outline-none border-0 bg-transparent group
                        <?= $settingsExpanded ? 'text-white bg-custom-gradient shadow-md sidebar-link-active' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' ?>"
                        type="button"
                        data-toggle="submenu"
                        aria-expanded="<?= $settingsExpanded ? 'true' : 'false' ?>">
                    <span class="flex items-center gap-3">
                        <i class="bi <?= htmlspecialchars($item['icon']) ?> text-base <?= $settingsExpanded ? 'text-white' : 'text-slate-600 group-hover:text-indigo-600' ?>"></i>
                        <span><?= htmlspecialchars($item['label']) ?></span>
                    </span>
                    <i class="bi settings-chevron text-xs transition-transform duration-200 <?= $settingsExpanded ? 'bi-chevron-up rotate-180 text-white' : 'bi-chevron-down text-slate-400 group-hover:text-indigo-600' ?>"></i>
                </button>

                <div class="submenu <?= $settingsExpanded ? 'open' : '' ?> mt-1 ml-4 border-l border-slate-100 pl-2 space-y-1" style="max-height: <?= $settingsExpanded ? 'none' : '0' ?>">
                    <?php foreach ($item['children'] as $child):
                        $isChildActive = $activeMenu === $child['key'];
                    ?>
                        <a class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 no-underline group
                                <?= $isChildActive ? 'text-white bg-custom-gradient shadow-sm sidebar-link-active' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50' ?>"
                           href="<?= htmlspecialchars($child['href']) ?>">
                            <i class="bi <?= htmlspecialchars($child['icon']) ?> text-sm <?= $isChildActive ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' ?>"></i>
                            <span><?= htmlspecialchars($child['label']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <a class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 no-underline group
                        <?= $isActive ? 'text-white bg-custom-gradient shadow-md sidebar-link-active' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' ?>"
                   href="<?= htmlspecialchars($item['href']) ?>">
                    <i class="bi <?= htmlspecialchars($item['icon']) ?> text-base <?= $isActive ? 'text-white' : 'text-slate-600 group-hover:text-indigo-600' ?>"></i>
                    <span class="flex-1"><?= htmlspecialchars($item['label']) ?></span>
                    <?php if (!empty($item['badge'])): ?>
                    <span class="notification-badge inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold leading-none shadow-sm"><?= (int)$item['badge'] ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>
<?php
    endforeach;
    return ob_get_clean();
}
?>
<style>
    .submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.25s cubic-bezier(0.25, 1, 0.5, 1);
    }
    .bg-custom-gradient {
        background: linear-gradient(135deg, #5d5bf6 0%, #3b39df 100%) !important;
    }
    .text-custom-indigo {
        color: #4f46e5;
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

<aside class="hidden md:flex md:flex-col justify-between w-64 h-screen fixed top-0 left-0 bg-white border-r border-slate-100 p-4 shrink-0 z-30 overflow-y-auto">
    <div>
        <div class="flex items-center gap-3 px-2 mb-8">
            <div class="flex items-center justify-center w-10 h-10 bg-indigo-50 text-custom-indigo rounded-xl">
                <i class="bi bi-mortarboard text-xl"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-800 tracking-tight m-0">Career Guidance</h2>
                <p class="text-[11px] font-medium text-slate-400 m-0">Admin Panel</p>
            </div>
        </div>
        <nav class="space-y-1">
            <?= renderMenuItems($menuItems, $activeMenu, $settingsExpanded, 'desktop') ?>
        </nav>
    </div>
    <div class="border-t border-slate-100 pt-4">
        <div class="flex items-center gap-2.5 px-2 text-slate-400">
            <i class="bi bi-shield-check text-base text-emerald-500"></i>
            <span class="text-xs font-medium tracking-wide">Secure admin access</span>
        </div>
    </div>
</aside>

<div class="offcanvas offcanvas-start border-0 shadow-xl w-72" tabindex="-1" id="adminSidebarMobile" aria-labelledby="adminSidebarMobileLabel">
    <div class="offcanvas-header border-b border-slate-50 px-4 py-3.5">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-9 h-9 bg-indigo-50 text-custom-indigo rounded-xl">
                <i class="bi bi-mortarboard text-lg"></i>
            </div>
            <div>
                <h5 class="offcanvas-title text-sm font-bold text-slate-800 m-0" id="adminSidebarMobileLabel">Career Guidance</h5>
                <p class="text-[10px] text-slate-400 font-medium m-0">Admin Panel</p>
            </div>
        </div>
        <button type="button" class="btn-close shadow-none focus:outline-none text-xs" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4 flex flex-col justify-between h-full">
        <nav class="space-y-1.5 w-full">
            <?= renderMenuItems($menuItems, $activeMenu, $settingsExpanded, 'mobile') ?>
        </nav>
        <div class="border-t border-slate-100 pt-4 mt-auto">
            <div class="flex items-center gap-2.5 text-slate-400">
                <i class="bi bi-shield-check text-base text-emerald-500"></i>
                <span class="text-xs font-medium tracking-wide">Secure admin access</span>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    function updateButtonState(button, isOpen) {
        if (!button) return;
        var chevron = button.querySelector('.settings-chevron');
        var icon = button.querySelector('.bi:not(.settings-chevron)');
        if (!chevron) return;

        button.classList.toggle('text-white', isOpen);
        button.classList.toggle('bg-custom-gradient', isOpen);
        button.classList.toggle('shadow-md', isOpen);
        button.classList.toggle('sidebar-link-active', isOpen);
        button.classList.toggle('text-slate-600', !isOpen);
        button.classList.toggle('hover:text-indigo-600', !isOpen);
        button.classList.toggle('hover:bg-slate-50', !isOpen);

        chevron.classList.toggle('rotate-180', isOpen);
        chevron.classList.toggle('bi-chevron-up', isOpen);
        chevron.classList.toggle('text-white', isOpen);
        chevron.classList.toggle('bi-chevron-down', !isOpen);
        chevron.classList.toggle('text-slate-400', !isOpen);

        if (icon) {
            icon.classList.toggle('text-white', isOpen);
            icon.classList.toggle('text-slate-600', !isOpen);
        }

        button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    function init() {
        var submenus = document.querySelectorAll('.submenu');
        var i, sm, btn;

        for (i = 0; i < submenus.length; i++) {
            sm = submenus[i];
            sm.style.maxHeight = sm.classList.contains('open') ? sm.scrollHeight + 'px' : '0';
        }

        var toggles = document.querySelectorAll('[data-toggle="submenu"]');
        for (i = 0; i < toggles.length; i++) {
            btn = toggles[i];
            sm = btn.nextElementSibling;
            if (sm && sm.classList.contains('submenu')) {
                updateButtonState(btn, sm.classList.contains('open'));
            }
        }
    }

    function closeAllSubmenus() {
        document.querySelectorAll('.submenu.open').forEach(function(sm) {
            sm.classList.remove('open');
            sm.style.maxHeight = '0';
        });
        document.querySelectorAll('[data-toggle="submenu"]').forEach(function(btn) {
            updateButtonState(btn, false);
        });
    }

    init();

    document.addEventListener('click', function(e) {
        var link = e.target.closest('.sidebar-link:not([data-toggle])');
        if (link) {
            closeAllSubmenus();
            return;
        }

        var btn = e.target.closest('[data-toggle="submenu"]');
        if (!btn) return;

        var sm = btn.nextElementSibling;
        if (!sm || !sm.classList.contains('submenu')) return;

        var isOpen = sm.classList.contains('open');

        closeAllSubmenus();

        if (!isOpen) {
            document.querySelectorAll('.sidebar-link:not([data-toggle]).sidebar-link-active').forEach(function(link) {
                link.classList.remove('sidebar-link-active', 'text-white', 'bg-custom-gradient', 'shadow-md');
                link.classList.add('text-slate-600', 'hover:text-indigo-600', 'hover:bg-slate-50');
                var icon = link.querySelector('.bi:not(.settings-chevron)');
                if (icon) {
                    icon.classList.remove('text-white');
                    icon.classList.add('text-slate-600');
                }
            });
            sm.classList.add('open');
            sm.style.maxHeight = sm.scrollHeight + 'px';
            updateButtonState(btn, true);
        }
    });
})();
</script>

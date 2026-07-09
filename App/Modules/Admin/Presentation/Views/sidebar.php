<?php
$activeMenu = $activeMenu ?? 'dashboard';
$settingsExpanded = in_array($activeMenu, ['settings', 'roles', 'permissions', 'assign-permissions', 'student-permissions'], true);

$menuItems = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-house', 'href' => BASE_URL . '/index.php?page=admin-dashboard'],
    ['key' => 'users', 'label' => 'Users', 'icon' => 'bi-people', 'href' => BASE_URL . '/index.php?page=admin-users'],
    ['key' => 'assessments', 'label' => 'Assessments', 'icon' => 'bi-clipboard-check', 'href' => BASE_URL . '/index.php?page=admin-assessments'],
    ['key' => 'questions', 'label' => 'Questions', 'icon' => 'bi-question-circle', 'href' => BASE_URL . '/index.php?page=admin-questions'],
    ['key' => 'careers', 'label' => 'Careers', 'icon' => 'bi-briefcase', 'href' => BASE_URL . '/index.php?page=admin-careers'],
    ['key' => 'reports', 'label' => 'Reports', 'icon' => 'bi-bar-chart', 'href' => BASE_URL . '/index.php?page=admin-reports'],
    [
        'key' => 'settings',
        'label' => 'Settings',
        'icon' => 'bi-gear',
        'href' => '#',
        'children' => [
            ['key' => 'roles', 'label' => 'Role Management', 'icon' => 'bi-person-gear', 'href' => BASE_URL . '/index.php?page=admin-roles'],
            ['key' => 'permissions', 'label' => 'Permission Management', 'icon' => 'bi-shield-lock', 'href' => BASE_URL . '/index.php?page=admin-permissions'],
            ['key' => 'assign-permissions', 'label' => 'Assign Permissions', 'icon' => 'bi-person-check', 'href' => BASE_URL . '/index.php?page=admin-assign-permissions'],
            ['key' => 'student-permissions', 'label' => 'Student Permissions', 'icon' => 'bi-person-lines', 'href' => BASE_URL . '/index.php?page=admin-settings-student-permissions'],
        ],
    ],
];
?>

<style>
    .collapsing {
        transition: height 0.25s cubic-bezier(0.25, 1, 0.5, 1);
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

<aside
class="hidden md:flex md:flex-col justify-between
w-64
h-screen
sticky
top-0
bg-white
border-r
border-slate-100
p-4
shrink-0">
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
            <?php foreach ($menuItems as $item): ?>
                <?php 
                    $isActive = $activeMenu === $item['key']; 
                    $isSettingsGroup = $item['key'] === 'settings';
                ?>
                <div class="relative">
                    <?php if (!empty($item['children'])): ?>
                        <button class="sidebar-link w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 no-underline outline-none border-0 bg-transparent group
                                <?= $settingsExpanded ? 'text-white bg-custom-gradient shadow-md sidebar-link-active' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' ?>"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#settingsSubmenuDesktop"
                                aria-expanded="<?= $settingsExpanded ? 'true' : 'false' ?>"
                                aria-controls="settingsSubmenuDesktop">
                            <span class="flex items-center gap-3">
                                <i class="bi <?= htmlspecialchars($item['icon']) ?> text-base <?= $settingsExpanded ? 'text-white' : 'text-slate-600 group-hover:text-indigo-600' ?>"></i>
                                <span><?= htmlspecialchars($item['label']) ?></span>
                            </span>
                            <i class="bi settings-chevron text-xs transition-transform duration-200 <?= $settingsExpanded ? 'bi-chevron-up rotate-180 text-white' : 'bi-chevron-down text-slate-400 group-hover:text-indigo-600' ?>"></i>
                        </button>

                        <div id="settingsSubmenuDesktop" class="collapse <?= $settingsExpanded ? 'show' : '' ?> mt-1 ml-4 border-l border-slate-100 pl-2 space-y-1">
                            <?php foreach ($item['children'] as $child): ?>
                                <?php $isChildActive = $activeMenu === $child['key']; ?>
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
                            <span><?= htmlspecialchars($item['label']) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
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
            <?php foreach ($menuItems as $item): ?>
                <?php 
                    $isActive = $activeMenu === $item['key']; 
                    $settingsExpanded = in_array($activeMenu, ['settings', 'roles', 'permissions', 'assign-permissions', 'student-permissions'], true);
                ?>
                <div>
                    <?php if (!empty($item['children'])): ?>
                        <button class="sidebar-link w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 no-underline border-0 bg-transparent group
                                <?= $settingsExpanded ? 'text-white bg-custom-gradient shadow-md sidebar-link-active' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' ?>"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#settingsSubmenuMobile"
                                aria-expanded="<?= $settingsExpanded ? 'true' : 'false' ?>"
                                aria-controls="settingsSubmenuMobile">
                            <span class="flex items-center gap-3">
                                <i class="bi <?= htmlspecialchars($item['icon']) ?> text-base <?= $settingsExpanded ? 'text-white' : 'text-slate-600 group-hover:text-indigo-600' ?>"></i>
                                <span><?= htmlspecialchars($item['label']) ?></span>
                            </span>
                            <i class="bi settings-chevron text-xs transition-transform duration-200 <?= $settingsExpanded ? 'bi-chevron-up rotate-180 text-white' : 'bi-chevron-down text-slate-400 group-hover:text-indigo-600' ?>"></i>
                        </button>

                        <div id="settingsSubmenuMobile" class="collapse <?= $settingsExpanded ? 'show' : '' ?> mt-1 ml-4 border-l border-slate-100 pl-2 space-y-1">
                            <?php foreach ($item['children'] as $child): ?>
                                <?php $isChildActive = $activeMenu === $child['key']; ?>
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
                            <span><?= htmlspecialchars($item['label']) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
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
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach((button) => {
            const targetId = button.getAttribute('data-bs-target');
            const targetElement = document.querySelector(targetId);
            const chevron = button.querySelector('.settings-chevron');
            const icon = button.querySelector('.bi:not(.settings-chevron)');

            if (!targetElement || !chevron) return;

            targetElement.addEventListener('show.bs.collapse', () => {
                chevron.classList.add('rotate-180', 'bi-chevron-up', 'text-white');
                chevron.classList.remove('bi-chevron-down', 'text-slate-400');
                button.classList.add('text-white', 'bg-custom-gradient', 'shadow-md');
                button.classList.remove('text-slate-600', 'hover:text-indigo-600', 'hover:bg-slate-50');
                if (icon) {
                    icon.classList.add('text-white');
                    icon.classList.remove('text-slate-600');
                }
            });

            targetElement.addEventListener('hide.bs.collapse', () => {
                chevron.classList.remove('rotate-180', 'bi-chevron-up', 'text-white');
                chevron.classList.add('bi-chevron-down', 'text-slate-400');
                button.classList.remove('text-white', 'bg-custom-gradient', 'shadow-md');
                button.classList.add('text-slate-600', 'hover:text-indigo-600', 'hover:bg-slate-50');
                if (icon) {
                    icon.classList.remove('text-white');
                    icon.classList.add('text-slate-600');
                }
            });
        });
    });
</script>
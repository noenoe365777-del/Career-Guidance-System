<?php
$activeMenu = $activeMenu ?? 'dashboard';
$settingsExpanded = in_array($activeMenu, ['settings', 'roles', 'permissions', 'assign-permissions'], true);

$menuItems = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-house-door-fill', 'href' => BASE_URL . '/index.php?page=admin-dashboard'],
    ['key' => 'users', 'label' => 'Users', 'icon' => 'bi-people-fill', 'href' => BASE_URL . '/index.php?page=admin-users'],
    ['key' => 'assessments', 'label' => 'Assessments', 'icon' => 'bi-clipboard-check-fill', 'href' => '#'],
    ['key' => 'questions', 'label' => 'Questions', 'icon' => 'bi-question-circle-fill', 'href' => '#'],
    ['key' => 'careers', 'label' => 'Careers', 'icon' => 'bi-briefcase-fill', 'href' => '#'],
    ['key' => 'reports', 'label' => 'Reports', 'icon' => 'bi-bar-chart-line-fill', 'href' => '#'],
    [
        'key' => 'settings',
        'label' => 'Settings',
        'icon' => 'bi-gear-fill',
        'href' => '#',
        'children' => [
            ['key' => 'roles', 'label' => 'Role Management', 'icon' => 'bi-person-gear-fill', 'href' => BASE_URL . '/index.php?page=admin-roles'],
            ['key' => 'permissions', 'label' => 'Permission Management', 'icon' => 'bi-shield-lock-fill', 'href' => BASE_URL . '/index.php?page=admin-permissions'],
            ['key' => 'assign-permissions', 'label' => 'Assign Permissions', 'icon' => 'bi-person-check-fill', 'href' => BASE_URL . '/index.php?page=admin-assign-permissions'],
        ],
    ],
];
?>
<style>
    .settings-toggle {
        width: 100%;
        border: 0;
        background: transparent;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.7rem 0.8rem;
    }

    .settings-toggle:hover,
    .settings-toggle:focus {
        background-color: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
    }

    .settings-toggle.active {
        background-color: #0d6efd;
        color: #fff;
    }

  .sidebar-submenu{
    margin-left:1rem;
    margin-top:.35rem;
    width:100%;
}

.sidebar-submenu .nav-link{
    display:flex;
    align-items:center;
    gap:1px;
    width:100%;
    padding:10px 14px;
    color:#6c757d;
    text-decoration:none;
    border-radius:8px;
    white-space:nowrap;
    font-size:15px;
    font-weight:400;
}

.sidebar-submenu .nav-link i{
    width:20px;
    text-align:center;
    flex-shrink:0;
}

.sidebar-submenu .nav-link span{
    display:inline-block;
    white-space:nowrap;
    text-align:left;
    flex:1;
}
.sidebar-submenu .nav-link:hover{
    background:#eef4ff;
    color:#0d6efd;
}

.sidebar-submenu .nav-link.active{
    background:#0d6efd;
    color:#fff;
}
</style>
<aside class="admin-sidebar d-none d-md-flex flex-column justify-content-between p-3" id="adminSidebarDesktop">
    <div>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-semibold mb-0">Main Menu</h5>
        </div>
        <ul class="nav flex-column gap-1">
            <?php foreach ($menuItems as $item): ?>
                <?php $isActive = $activeMenu === $item['key']; ?>
                <li class="nav-item">
                    <?php if (!empty($item['children'])): ?>
                        <button class="settings-toggle nav-link rounded-3 <?= $isActive ? 'active bg-primary text-white' : 'text-secondary' ?>"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#settingsSubmenuDesktop"
                                aria-expanded="<?= $settingsExpanded ? 'true' : 'false' ?>"
                                aria-controls="settingsSubmenuDesktop">
                            <span>
                                <i class="bi <?= htmlspecialchars($item['icon']) ?> me-2"></i>
                                <span><?= htmlspecialchars($item['label']) ?></span>
                            </span>
                            <i class="bi settings-chevron <?= $settingsExpanded ? 'bi-chevron-up' : 'bi-chevron-down' ?>"></i>
                        </button>
                        <ul id="settingsSubmenuDesktop" class="sidebar-submenu collapse nav flex-column gap-1 <?= $settingsExpanded ? 'show' : '' ?>">
                            <?php foreach ($item['children'] as $child): ?>
                                <?php $isChildActive = $activeMenu === $child['key']; ?>
                                <li class="nav-item">
                                   <a class="nav-link rounded-3 <?= $isChildActive ? 'fw-bold text-primary' : 'text-secondary' ?>"  href="<?= htmlspecialchars($child['href']) ?>">
                                        <i class="bi <?= htmlspecialchars($child['icon']) ?> me-2"></i>
                                        <span><?= htmlspecialchars($child['label']) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <a class="nav-link rounded-3 <?= $isActive ? 'active bg-primary text-white' : 'text-secondary' ?>" href="<?= htmlspecialchars($item['href']) ?>">
                            <i class="bi <?= htmlspecialchars($item['icon']) ?> me-2"></i>
                            <span><?= htmlspecialchars($item['label']) ?></span>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="border-top pt-3 mt-3">
        <div class="d-flex align-items-center gap-2 text-muted small">
            <i class="bi bi-shield-check"></i>
            <span>Secure admin access</span>
        </div>
    </div>
</aside>

<div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebarMobile" aria-labelledby="adminSidebarMobileLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="adminSidebarMobileLabel">Admin Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-3">
        <ul class="nav flex-column gap-1">
            <?php foreach ($menuItems as $item): ?>
                <?php $isActive = $activeMenu === $item['key']; ?>
                <li class="nav-item">
                    <?php if (!empty($item['children'])): ?>
                        <button class="settings-toggle nav-link rounded-3 <?= $isActive ? 'active bg-primary text-white' : 'text-secondary' ?>"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#settingsSubmenuMobile"
                                aria-expanded="<?= $settingsExpanded ? 'true' : 'false' ?>"
                                aria-controls="settingsSubmenuMobile">
                            <span>
                                <i class="bi <?= htmlspecialchars($item['icon']) ?> me-2"></i>
                                <span><?= htmlspecialchars($item['label']) ?></span>
                            </span>
                            <i class="bi settings-chevron <?= $settingsExpanded ? 'bi-chevron-up' : 'bi-chevron-down' ?>"></i>
                        </button>
                        <ul id="settingsSubmenuMobile" class="sidebar-submenu collapse nav flex-column gap-1 <?= $settingsExpanded ? 'show' : '' ?>">
                            <?php foreach ($item['children'] as $child): ?>
                                <?php $isChildActive = $activeMenu === $child['key']; ?>
                                <li class="nav-item">
                                    <a class="nav-link rounded-3 <?= $isChildActive ? 'active bg-primary text-white' : 'text-secondary' ?>" href="<?= htmlspecialchars($child['href']) ?>">
                                        <i class="bi <?= htmlspecialchars($child['icon']) ?> me-2"></i>
                                        <span><?= htmlspecialchars($child['label']) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <a class="nav-link rounded-3 <?= $isActive ? 'active bg-primary text-white' : 'text-secondary' ?>" href="<?= htmlspecialchars($item['href']) ?>">
                            <i class="bi <?= htmlspecialchars($item['icon']) ?> me-2"></i>
                            <span><?= htmlspecialchars($item['label']) ?></span>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.settings-toggle').forEach((button) => {
            const targetId = button.getAttribute('data-bs-target');
            const icon = button.querySelector('.settings-chevron');

            if (!targetId || !icon) {
                return;
            }

            const syncIcon = () => {
                const expanded = button.getAttribute('aria-expanded') === 'true';
                icon.classList.toggle('bi-chevron-down', !expanded);
                icon.classList.toggle('bi-chevron-up', expanded);
            };

            button.addEventListener('shown.bs.collapse', syncIcon);
            button.addEventListener('hidden.bs.collapse', syncIcon);
            syncIcon();
        });
    });
</script>

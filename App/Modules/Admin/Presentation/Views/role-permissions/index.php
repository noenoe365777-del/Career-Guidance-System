<?php
$roles = $roles ?? [];
$groupedPermissions = $groupedPermissions ?? [];
$selectedRoleId = $selectedRoleId ?? 0;
$selectedRoleName = $selectedRoleName ?? '';
$assignedPermissionIds = $assignedPermissionIds ?? [];
$errors = $errors ?? [];
$message = $message ?? null;
$assignedSet = [];
foreach ($assignedPermissionIds as $id) {
    $assignedSet[(int)$id] = true;
}

$pageTitle = $selectedRoleId > 0 ? htmlspecialchars($selectedRoleName) . ' Permissions' : 'Roles & Permissions';
$headerTitle = $pageTitle;
$activeMenu = 'role-permissions';

$moduleIconMap = [
    'Dashboard'       => ['icon' => 'bi-speedometer2', 'bg' => '#eef2ff', 'color' => '#5B5FEF'],
    'Users'           => ['icon' => 'bi-people',       'bg' => '#ecfdf5', 'color' => '#059669'],
    'Assessments'     => ['icon' => 'bi-clipboard-check', 'bg' => '#fffbeb', 'color' => '#d97706'],
    'Questions'       => ['icon' => 'bi-question-circle', 'bg' => '#fef2f2', 'color' => '#ef4444'],
    'Careers'         => ['icon' => 'bi-briefcase',    'bg' => '#f0f9ff', 'color' => '#0284c7'],
    'Reports'         => ['icon' => 'bi-bar-chart-line','bg' => '#fdf4ff', 'color' => '#c026d3'],
    'Notifications'   => ['icon' => 'bi-bell',         'bg' => '#fff7ed', 'color' => '#ea580c'],
    'Settings'        => ['icon' => 'bi-gear',         'bg' => '#f1f5f9', 'color' => '#475569'],
    'General'         => ['icon' => 'bi-grid',         'bg' => '#f1f5f9', 'color' => '#64748b'],
];

function permModuleInfo(string $module, array $map): array {
    return $map[$module] ?? ['icon' => 'bi-grid', 'bg' => '#f1f5f9', 'color' => '#64748b'];
}

ob_start();
?>
<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUpCard { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .card-in { animation: slideUpCard 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    .d1 { animation-delay: 0.05s; }
    .d2 { animation-delay: 0.10s; }
    .d3 { animation-delay: 0.15s; }
    .d4 { animation-delay: 0.20s; }
    .d5 { animation-delay: 0.25s; }
    .d6 { animation-delay: 0.30s; }
    .d7 { animation-delay: 0.35s; }
    .d8 { animation-delay: 0.40s; }
    .d9 { animation-delay: 0.45s; }

    .perm-card {
        border-radius: 16px; background: #fff; border: 1px solid #e2e8f0;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out;
        overflow: hidden;
    }
    .perm-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(91,95,239,0.18);
        border-color: #c7d2fe;
    }

    .perm-module-icon {
        transition: transform 0.3s ease-out;
    }
    .perm-card:hover .perm-module-icon {
        transform: scale(1.12) rotate(5deg);
    }

    .perm-check {
        display: flex; align-items: center; gap: 0.65rem;
        padding: 0.55rem 0.85rem; border-radius: 10px; border: 1px solid #f1f5f9;
        cursor: pointer; transition: all 0.15s ease;
    }
    .perm-check:hover {
        border-color: #c7d2fe; background: #fafaff;
    }
    .perm-check input[type="checkbox"] {
        width: 1rem; height: 1rem; border-radius: 4px; border: 1.5px solid #cbd5e1;
        accent-color: #6366f1; cursor: pointer; flex-shrink: 0;
    }
    .perm-check input[type="checkbox"]:checked {
        background: #6366f1; border-color: #6366f1;
    }
    .perm-check-label { font-size: 0.84rem; font-weight: 500; color: #334155; }
    .perm-check-sub { font-size: 0.72rem; color: #94a3b8; }

    .stat-card {
        border-radius: 16px; padding: 24px; background: #fff; border: 1px solid #e2e8f0;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(91,95,239,0.18);
        border-color: #c7d2fe;
    }

    .module-select-all {
        font-size: 0.72rem; font-weight: 600; color: #6366f1; cursor: pointer;
        padding: 0.25rem 0.65rem; border-radius: 6px; border: 1px solid #e0e7ff;
        background: #eef2ff; transition: all 0.15s ease;
    }
    .module-select-all:hover { background: #e0e7ff; }

    .badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.2rem 0.65rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 500;
        border: 1px solid transparent;
    }
    .badge-count { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
</style>

<div class="max-w-[1440px] mx-auto px-6 lg:px-8 py-8">

<?php if ($selectedRoleId > 0): ?>

    <!-- Header with Save button -->
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="<?= BASE_URL ?>/index.php?page=admin-role-permissions" class="text-sm text-slate-400 hover:text-indigo-600 transition no-underline">Roles</a>
                <i class="bi bi-chevron-right text-xs text-slate-300"></i>
                <span class="text-sm font-semibold text-slate-700"><?= htmlspecialchars($selectedRoleName) ?></span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900"><?= htmlspecialchars($selectedRoleName) ?> Permissions</h1>
            <p class="mt-1 text-sm text-slate-500">Configure access levels and feature permissions for this role.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/index.php?page=admin-role-permissions" class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-all no-underline">
                <i class="bi bi-arrow-left text-sm"></i> Back
            </a>
            <button type="submit" form="permissionsForm" class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 active:scale-[0.97] cursor-pointer border-0">
                <i class="bi bi-check-lg text-sm"></i> Save Permissions
            </button>
        </div>
    </div>

    <!-- Messages -->
    <?php if ($message === 'saved'): ?>
    <div class="mb-6">
        <div class="flex items-center gap-3 p-4 border border-emerald-100 bg-emerald-50/50 rounded-2xl text-emerald-800 text-sm font-medium"><i class="bi bi-check-circle-fill text-base text-emerald-500"></i><div>Permissions updated successfully.</div></div>
    </div>
    <?php endif; ?>
    <?php if (!empty($errors['role_id'])): ?>
    <div class="mb-6">
        <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium"><i class="bi bi-x-circle-fill text-base text-rose-500"></i><div><?= htmlspecialchars($errors['role_id']) ?></div></div>
    </div>
    <?php endif; ?>

    <!-- Role Info Card -->
    <?php
    $isAdminRole = stripos($selectedRoleName, 'admin') !== false;
    $roleIcon = $isAdminRole ? 'bi-shield-lock' : 'bi-person-badge';
    $roleBg = $isAdminRole ? '#eef2ff' : '#ecfdf5';
    $roleColor = $isAdminRole ? '#5B5FEF' : '#059669';
    $totalPerms = 0;
    foreach ($groupedPermissions as $perms) { $totalPerms += count($perms); }
    $assignedCount = count($assignedSet);
    ?>
    <div class="stat-card card-in d1 mb-8" style="cursor:default;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="width:52px;height:52px;display:flex;align-items:center;justify-content:center;border-radius:14px;background:<?= $roleBg ?>;color:<?= $roleColor ?>;flex-shrink:0;">
                    <i class="bi <?= $roleIcon ?>" style="font-size:26px;"></i>
                </div>
                <div>
                    <h2 style="font-size:1.15rem;font-weight:700;color:#0f172a;margin:0;"><?= htmlspecialchars($selectedRoleName) ?> Role</h2>
                    <p style="font-size:0.84rem;color:#64748b;margin:2px 0 0 0;">
                        <?= $isAdminRole
                            ? 'Manage admin panel permissions including users, assessments, careers, reports, and settings.'
                            : 'Manage student portal feature access including dashboard, assessments, results, recommendations, and profile.' ?>
                    </p>
                </div>
            </div>
            <div style="display:flex;gap:12px;flex-shrink:0;">
                <div style="text-align:center;padding:0 16px;">
                    <p style="font-size:1.5rem;font-weight:700;color:#0f172a;margin:0;"><?= $totalPerms ?></p>
                    <p style="font-size:0.72rem;color:#94a3b8;margin:0;">Total Permissions</p>
                </div>
                <div style="width:1px;background:#e2e8f0;"></div>
                <div style="text-align:center;padding:0 16px;">
                    <p style="font-size:1.5rem;font-weight:700;color:#059669;margin:0;"><?= $assignedCount ?></p>
                    <p style="font-size:0.72rem;color:#94a3b8;margin:0;">Assigned</p>
                </div>
                <div style="width:1px;background:#e2e8f0;"></div>
                <div style="text-align:center;padding:0 16px;">
                    <p style="font-size:1.5rem;font-weight:700;color:#94a3b8;margin:0;"><?= $totalPerms - $assignedCount ?></p>
                    <p style="font-size:0.72rem;color:#94a3b8;margin:0;">Unassigned</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Permission Modules Grid -->
    <form id="permissionsForm" method="post" action="<?= BASE_URL ?>/index.php?page=admin-role-permissions-save">
        <input type="hidden" name="role_id" value="<?= (int)$selectedRoleId ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php $delayIndex = 2; ?>
            <?php foreach ($groupedPermissions as $moduleName => $permissions): ?>
                <?php
                $modInfo = permModuleInfo($moduleName, $moduleIconMap);
                $moduleAssigned = 0;
                foreach ($permissions as $p) {
                    if (isset($assignedSet[(int)($p['id'] ?? 0)])) $moduleAssigned++;
                }
                $moduleId = 'mod_' . preg_replace('/[^a-zA-Z0-9]/', '_', strtolower($moduleName));
                ?>
                <div class="perm-card card-in d<?= min($delayIndex++, 9) ?>">
                    <div style="padding:20px 24px 0;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div class="perm-module-icon" style="width:42px;height:42px;display:flex;align-items:center;justify-content:center;border-radius:12px;background:<?= $modInfo['bg'] ?>;color:<?= $modInfo['color'] ?>;flex-shrink:0;">
                                    <i class="bi <?= $modInfo['icon'] ?>" style="font-size:20px;"></i>
                                </div>
                                <div>
                                    <h3 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin:0;"><?= htmlspecialchars($moduleName) ?></h3>
                                    <span class="badge badge-count" style="margin-top:2px;"><?= $moduleAssigned ?> / <?= count($permissions) ?> assigned</span>
                                </div>
                            </div>
                            <button type="button" class="module-select-all" onclick="toggleModule('<?= $moduleId ?>', this)">Select All</button>
                        </div>
                    </div>
                    <div style="padding:0 24px 20px;">
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <?php foreach ($permissions as $permission): ?>
                                <?php
                                $permId = (int)($permission['id'] ?? 0);
                                $permName = (string)($permission['name'] ?? '');
                                $isChecked = isset($assignedSet[$permId]) ? 'checked' : '';
                                ?>
                                <label class="perm-check">
                                    <input type="checkbox" name="permissions[]" value="<?= $permId ?>" <?= $isChecked ?> class="<?= $moduleId ?>" onchange="updateModuleCount('<?= $moduleId ?>')">
                                    <span>
                                        <span class="perm-check-label"><?= htmlspecialchars($permName) ?></span>
                                        <?php if (!empty($permission['description'])): ?>
                                            <span class="perm-check-sub"><?= htmlspecialchars($permission['description']) ?></span>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bottom save bar -->
        <div class="mt-8 flex items-center justify-end gap-3">
            <a href="<?= BASE_URL ?>/index.php?page=admin-role-permissions" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition no-underline">Cancel</a>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 active:scale-[0.97] cursor-pointer border-0">
                <i class="bi bi-check-lg text-sm"></i> Save Permissions
            </button>
        </div>
    </form>

<?php else: ?>

    <!-- Roles List Header -->
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Roles & Permissions</h1>
            <p class="mt-1 text-sm text-slate-500">Select a role to manage its permissions and access levels.</p>
        </div>
    </div>

    <!-- Role Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($roles as $role):
            $roleId = (int)($role['id'] ?? 0);
            $roleName = (string)($role['name'] ?? '');
            $isAdmin = $roleId === 1;
            $icon = $isAdmin ? 'bi-shield-lock' : 'bi-person-badge';
            $bg = $isAdmin ? '#eef2ff' : '#ecfdf5';
            $color = $isAdmin ? '#5B5FEF' : '#059669';
            $description = $isAdmin
                ? 'Manage admin panel permissions including users, assessments, careers, reports, and settings.'
                : 'Manage student portal feature access including dashboard, assessments, results, recommendations, and profile.';
            $actionLabel = $isAdmin ? 'Manage Permissions' : 'Manage Students';
            $actionUrl = $isAdmin
                ? BASE_URL . '/index.php?page=admin-role-permissions&role_id=' . $roleId
                : BASE_URL . '/index.php?page=admin-settings-student-permissions';
        ?>
            <div class="perm-card card-in d<?= $isAdmin ? '1' : '2' ?>" style="cursor:default;">
                <div style="padding:28px;">
                    <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                        <div style="width:52px;height:52px;display:flex;align-items:center;justify-content:center;border-radius:14px;background:<?= $bg ?>;color:<?= $color ?>;flex-shrink:0;">
                            <i class="bi <?= $icon ?>" style="font-size:24px;"></i>
                        </div>
                        <div>
                            <h3 style="font-size:1.1rem;font-weight:700;color:#0f172a;margin:0;"><?= htmlspecialchars($roleName) ?></h3>
                            <p style="font-size:0.72rem;color:#94a3b8;margin:2px 0 0 0;">Role ID: <?= $roleId ?></p>
                        </div>
                    </div>
                    <p style="font-size:0.84rem;color:#64748b;margin:0 0 20px 0;line-height:1.6;"><?= htmlspecialchars($description) ?></p>
                    <a href="<?= $actionUrl ?>" style="display:inline-flex;align-items:center;gap:8px;padding:0.6rem 1.25rem;border-radius:12px;font-size:0.84rem;font-weight:600;color:#fff;background:<?= $color ?>;text-decoration:none;transition:all 0.2s ease;box-shadow:0 4px 12px <?= $isAdmin ? 'rgba(91,95,239,0.3)' : 'rgba(5,150,105,0.3)' ?>;">
                        <i class="bi bi-gear"></i><?= $actionLabel ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

</div>

<script>
function toggleModule(moduleClass, btn) {
    var checkboxes = document.querySelectorAll('.' + moduleClass);
    var allChecked = true;
    checkboxes.forEach(function(cb) { if (!cb.checked) allChecked = false; });
    checkboxes.forEach(function(cb) { cb.checked = !allChecked; });
    btn.textContent = allChecked ? 'Select All' : 'Deselect All';
    updateModuleCount(moduleClass);
}

function updateModuleCount(moduleClass) {
    var checkboxes = document.querySelectorAll('.' + moduleClass);
    var total = checkboxes.length;
    var checked = 0;
    checkboxes.forEach(function(cb) { if (cb.checked) checked++; });
    var card = checkboxes[0] ? checkboxes[0].closest('.perm-card') : null;
    if (card) {
        var badge = card.querySelector('.badge-count');
        if (badge) badge.textContent = checked + ' / ' + total + ' assigned';
    }
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

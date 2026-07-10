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
?>
<div class="px-4 sm:px-8 lg:px-10 py-8 space-y-8 flex-1 max-w-[1600px] w-full mx-auto">

<?php if ($selectedRoleId > 0): ?>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900"><?= htmlspecialchars($selectedRoleName) ?> Permissions</h2>
            <p class="text-slate-500 text-sm mt-1">Select the permissions granted to the <?= htmlspecialchars($selectedRoleName) ?> role.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=admin-role-permissions" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg font-medium text-sm transition">
            <i class="fas fa-arrow-left"></i>Back to Roles
        </a>
    </div>

    <?php if ($message === 'saved'): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Permissions updated successfully.</div>
    <?php endif; ?>

    <?php if (!empty($errors['role_id'])): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm"><?= htmlspecialchars($errors['role_id']) ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="p-4 sm:p-6">
            <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-role-permissions-save">
                <input type="hidden" name="role_id" value="<?= (int)$selectedRoleId ?>">

                <div class="space-y-4">
                    <?php foreach ($groupedPermissions as $moduleName => $permissions): ?>
                        <div class="border border-slate-200 rounded-lg p-4">
                            <h3 class="text-sm font-bold text-indigo-600 mb-3"><?= htmlspecialchars($moduleName) ?></h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <?php foreach ($permissions as $permission): ?>
                                    <label class="flex items-center gap-3 px-3 py-2 rounded-lg border border-slate-100 hover:border-slate-200 transition cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="<?= (int)($permission['id'] ?? 0) ?>" <?= isset($assignedSet[(int)($permission['id'] ?? 0)]) ? 'checked' : '' ?> class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <span>
                                            <span class="text-sm font-medium text-slate-800"><?= htmlspecialchars((string)($permission['name'] ?? '')) ?></span>
                                            <span class="text-xs text-slate-400 block"><?= htmlspecialchars((string)($permission['module'] ?? '')) ?></span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition shadow-sm"><i class="fas fa-save"></i>Save Permissions</button>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-role-permissions" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg font-medium text-sm transition">Cancel</a>
                </div>
            </form>
        </div>
    </div>

<?php else: ?>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Roles & Permissions</h2>
            <p class="text-slate-500 text-sm mt-1">Select a role to manage its permissions and access levels.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($roles as $role):
            $roleId = (int)($role['id'] ?? 0);
            $roleName = (string)($role['name'] ?? '');
            $isAdmin = $roleId === 1;
            $icon = $isAdmin ? 'bi-shield-lock' : 'bi-person-badge';
            $description = $isAdmin
                ? 'Manage admin panel permissions including users, assessments, careers, reports, and settings.'
                : 'Manage student portal feature access including dashboard, assessments, results, recommendations, and profile.';
            $actionLabel = $isAdmin ? 'Manage Permissions' : 'Manage Students';
            $actionUrl = $isAdmin
                ? BASE_URL . '/index.php?page=admin-role-permissions&role_id=' . $roleId
                : BASE_URL . '/index.php?page=admin-settings-student-permissions';
        ?>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl <?= $isAdmin ? 'bg-indigo-50 text-indigo-600' : 'bg-emerald-50 text-emerald-600' ?>">
                            <i class="bi <?= $icon ?> text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900"><?= htmlspecialchars($roleName) ?></h3>
                            <p class="text-xs text-slate-400">Role ID: <?= $roleId ?></p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mb-6"><?= htmlspecialchars($description) ?></p>
                    <a href="<?= $actionUrl ?>" class="inline-flex items-center gap-2 <?= $isAdmin ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white' ?> px-4 py-2 rounded-lg font-medium text-sm transition shadow-sm">
                        <i class="fas fa-cog"></i><?= $actionLabel ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

</div>

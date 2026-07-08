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
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Assign Permissions') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; padding-top: 72px; }
        .admin-sidebar { width: 260px; min-height: calc(100vh - 72px); background: #fff; border-right: 1px solid #e7ebf3; position: sticky; top: 72px; }
        .admin-content { min-height: calc(100vh - 72px); }
        .card-soft { border: 0; border-radius: 1rem; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06); }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../header.php'; ?>

    <div class="d-flex">
        <?php include __DIR__ . '/../sidebar.php'; ?>

        <main class="admin-content flex-grow-1 p-3 p-lg-4">
            <div class="container-fluid">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Assign Permissions</h2>
                        <p class="text-muted mb-0">Set the permissions granted to each role.</p>
                    </div>
                </div>

                <?php if ($message === 'saved'): ?>
                    <div class="alert alert-success">Permissions updated successfully.</div>
                <?php endif; ?>

                <?php if (!empty($errors['role_id'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errors['role_id']) ?></div>
                <?php endif; ?>

                <div class="card card-soft mb-4">
                    <div class="card-body">
                        <form method="get" class="row g-3 align-items-end">
                            <input type="hidden" name="page" value="admin-assign-permissions">
                            <div class="col-md-8">
                                <label for="role_id" class="form-label">Select Role</label>
                                <select class="form-select" id="role_id" name="role_id" onchange="this.form.submit()">
                                    <option value="0">Choose a role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= (int)($role['role_id'] ?? 0) ?>" <?= ((int)($role['role_id'] ?? 0) === $selectedRoleId) ? 'selected' : '' ?>><?= htmlspecialchars((string)($role['role_name'] ?? '')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($selectedRoleId > 0): ?>
                    <div class="card card-soft">
                        <div class="card-body">
                            <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-assign-permissions-save">
                                <input type="hidden" name="role_id" value="<?= (int)$selectedRoleId ?>">

                                <div class="mb-4">
                                    <h5 class="fw-semibold mb-2">Permissions for <?= htmlspecialchars($selectedRoleName !== '' ? $selectedRoleName : 'selected role') ?></h5>
                                    <p class="text-muted mb-0">Select the permissions that should be available to this role.</p>
                                </div>

                                <?php foreach ($groupedPermissions as $moduleName => $permissions): ?>
                                    <div class="border rounded-3 p-3 mb-3">
                                        <h6 class="fw-bold mb-3 text-primary"><?= htmlspecialchars($moduleName) ?></h6>
                                        <div class="row g-3">
                                            <?php foreach ($permissions as $permission): ?>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= (int)($permission['permission_id'] ?? 0) ?>" id="perm_<?= (int)($permission['permission_id'] ?? 0) ?>" <?= isset($assignedSet[(int)($permission['permission_id'] ?? 0)]) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="perm_<?= (int)($permission['permission_id'] ?? 0) ?>">
                                                            <span class="fw-semibold"><?= htmlspecialchars((string)($permission['permission_name'] ?? '')) ?></span>
                                                            <br>
                                                            <small class="text-muted"><?= htmlspecialchars((string)($permission['description'] ?? '')) ?></small>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">Save Permissions</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

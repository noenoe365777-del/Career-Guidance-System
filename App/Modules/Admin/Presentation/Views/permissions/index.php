<?php
$permissions = $permissions ?? [];
$message = $message ?? null;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Permission Management') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; padding-top: 72px; }
        .admin-sidebar { width: 260px; min-height: calc(100vh - 72px); background: #fff; border-right: 1px solid #e7ebf3; position: sticky; top: 72px; }
        .admin-content { min-height: calc(100vh - 72px); }
        .card-soft { border: 0; border-radius: 1rem; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06); }
    </style>
</head>
<body class="h-full text-slate-700 antialiased font-sans m-0 p-0">
    <?php
    $sidebarPath = file_exists(__DIR__ . '/sidebar.php') ? __DIR__ . '/sidebar.php' : __DIR__ . '/../sidebar.php';
    $headerPath = file_exists(__DIR__ . '/header.php') ? __DIR__ . '/header.php' : __DIR__ . '/../header.php';
    ?>
    <!-- admin-shell-wrapper -->
    <div class="flex h-screen overflow-hidden">
        <div class="hidden md:flex md:shrink-0 h-full">
            <?php include $sidebarPath; ?>
        </div>
        <div class="flex flex-col flex-1 min-w-0 h-full overflow-hidden">
            <?php include $headerPath; ?>
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-[#f4f7fc]">
                <div class="max-w-[1400px] mx-auto space-y-6">
                    <main class="admin-content flex-grow-1 p-3 p-lg-4">
                        <div class="container-fluid">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Permission Management</h2>
                        <p class="text-muted mb-0">Manage available system permissions for RBAC.</p>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-permissions-create" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Permission
                    </a>
                </div>

                <?php if ($message === 'created'): ?>
                    <div class="alert alert-success">Permission created successfully.</div>
                <?php elseif ($message === 'updated'): ?>
                    <div class="alert alert-success">Permission updated successfully.</div>
                <?php elseif ($message === 'deleted'): ?>
                    <div class="alert alert-warning">Permission deleted successfully.</div>
                <?php elseif ($message === 'not_found'): ?>
                    <div class="alert alert-danger">Permission not found.</div>
                <?php endif; ?>

                <div class="card card-soft">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Permission Name</th>
                                        <th>Module</th>
                                        <th>Description</th>
                                        <th>Created Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($permissions)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No permissions found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($permissions as $permission): ?>
                                            <tr>
                                                <td><?= htmlspecialchars((string)($permission['permission_name'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string)($permission['module_name'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string)($permission['description'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars(date('M d, Y', strtotime((string)($permission['created_at'] ?? date('Y-m-d'))))) ?></td>
                                                <td class="text-end">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="<?= BASE_URL ?>/index.php?page=admin-permissions-view&id=<?= (int)($permission['permission_id'] ?? 0) ?>" class="btn btn-outline-info">View</a>
                                                        <a href="<?= BASE_URL ?>/index.php?page=admin-permissions-edit&id=<?= (int)($permission['permission_id'] ?? 0) ?>" class="btn btn-outline-warning">Edit</a>
                                                        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-permissions-delete" onsubmit="return confirm('Delete this permission?');" class="d-inline">
                                                            <input type="hidden" name="id" value="<?= (int)($permission['permission_id'] ?? 0) ?>">
                                                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
    <!-- /admin-shell-wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

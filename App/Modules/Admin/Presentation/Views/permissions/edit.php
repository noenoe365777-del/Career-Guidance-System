<?php
$errors = $errors ?? [];
$old = $old ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Edit Permission') ?></title>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Edit Permission</h2>
                        <p class="text-muted mb-0">Update permission details.</p>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-permissions" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>

                <div class="card card-soft">
                    <div class="card-body">
                        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-permissions-update" novalidate>
                            <input type="hidden" name="id" value="<?= (int)($old['permission_id'] ?? 0) ?>">

                            <div class="mb-3">
                                <label for="permission_name" class="form-label">Permission Name</label>
                                <input type="text" class="form-control <?= isset($errors['permission_name']) ? 'is-invalid' : '' ?>" id="permission_name" name="permission_name" value="<?= htmlspecialchars((string)($old['permission_name'] ?? '')) ?>" required>
                                <?php if (!empty($errors['permission_name'])): ?><div class="invalid-feedback d-block"><?= htmlspecialchars($errors['permission_name']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="module_name" class="form-label">Module</label>
                                <input type="text" class="form-control <?= isset($errors['module_name']) ? 'is-invalid' : '' ?>" id="module_name" name="module_name" value="<?= htmlspecialchars((string)($old['module_name'] ?? '')) ?>" required>
                                <?php if (!empty($errors['module_name'])): ?><div class="invalid-feedback d-block"><?= htmlspecialchars($errors['module_name']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" id="description" name="description" rows="4" required><?= htmlspecialchars((string)($old['description'] ?? '')) ?></textarea>
                                <?php if (!empty($errors['description'])): ?><div class="invalid-feedback d-block"><?= htmlspecialchars($errors['description']) ?></div><?php endif; ?>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update Permission</button>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-permissions" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
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

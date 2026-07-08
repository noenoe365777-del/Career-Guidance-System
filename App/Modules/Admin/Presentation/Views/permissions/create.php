<?php
$errors = $errors ?? [];
$old = $old ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Create Permission') ?></title>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Create Permission</h2>
                        <p class="text-muted mb-0">Add a new permission to the RBAC system.</p>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-permissions" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>

                <div class="card card-soft">
                    <div class="card-body">
                        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-permissions-store" novalidate>
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
                                <button type="submit" class="btn btn-primary">Save Permission</button>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-permissions" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

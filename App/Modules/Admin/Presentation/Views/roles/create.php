<?php
$errors = $errors ?? [];
$old = $old ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Create Role') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
            padding-top: 72px;
        }

        .admin-sidebar {
            width: 260px;
            min-height: calc(100vh - 72px);
            background: #fff;
            border-right: 1px solid #e7ebf3;
            position: sticky;
            top: 72px;
        }

        .admin-content {
            min-height: calc(100vh - 72px);
        }

        .card-soft {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }
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
                        <h2 class="fw-bold mb-1">Create New Role</h2>
                        <p class="text-muted mb-0">Add a new user role for your RBAC system.</p>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-roles" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle me-2"></i>Back to Role List
                    </a>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h6 class="mb-2">Please fix the following errors:</h6>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars((string)$error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="card card-soft">
                    <div class="card-body p-4">
                        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-roles-store" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="role_name" class="form-label">Role Name</label>
                                    <input
                                        id="role_name"
                                        type="text"
                                        name="role_name"
                                        value="<?= htmlspecialchars((string)($old['role_name'] ?? '')) ?>"
                                        class="form-control <?= isset($errors['role_name']) ? 'is-invalid' : '' ?>"
                                        required
                                        minlength="3"
                                        maxlength="50"
                                    >
                                    <?php if (!empty($errors['role_name'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['role_name']) ?></div>
                                    <?php else: ?>
                                        <div class="form-text">Enter the role name, e.g. Admin or Student.</div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" name="status" class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>" required>
                                        <?php $statusValue = strtolower((string)($old['status'] ?? 'active')); ?>
                                        <option value="active" <?= $statusValue === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $statusValue === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                    <?php if (!empty($errors['status'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['status']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea
                                        id="description"
                                        name="description"
                                        rows="4"
                                        class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>"
                                        maxlength="255"
                                    ><?= htmlspecialchars((string)($old['description'] ?? '')) ?></textarea>
                                    <?php if (!empty($errors['description'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
                                    <?php else: ?>
                                        <div class="form-text">Optional description for this role.</div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mt-4 d-flex flex-column flex-sm-row gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Save Role
                                </button>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-roles" class="btn btn-outline-secondary">Cancel</a>
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
    <script>
        (() => {
            const form = document.querySelector('form');
            if (!form) return;

            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            });
        })();
    </script>
</body>
</html>

<?php
$permission = $permission ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Permission Details') ?></title>
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
                        <h2 class="fw-bold mb-1">Permission Details</h2>
                        <p class="text-muted mb-0">Review permission information.</p>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-permissions" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>

                <div class="card card-soft">
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-3">Permission Name</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars((string)($permission['permission_name'] ?? '')) ?></dd>

                            <dt class="col-sm-3">Module</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars((string)($permission['module_name'] ?? '')) ?></dd>

                            <dt class="col-sm-3">Description</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars((string)($permission['description'] ?? '')) ?></dd>

                            <dt class="col-sm-3">Created Date</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars((string)($permission['created_at'] ?? '')) ?></dd>

                            <dt class="col-sm-3">Updated Date</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars((string)($permission['updated_at'] ?? '')) ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

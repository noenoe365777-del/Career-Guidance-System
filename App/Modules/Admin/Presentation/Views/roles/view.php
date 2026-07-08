<?php
$role = $role ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Role Details') ?></title>
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
                        <h2 class="fw-bold mb-1">Role Details</h2>
                        <p class="text-muted mb-0">Detailed view of the selected role.</p>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-roles" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle me-2"></i>Back to Role List
                    </a>
                </div>

                <div class="card card-soft">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Role Name</label>
                                <div class="fw-semibold"><?= htmlspecialchars((string)($role['role_name'] ?? '')) ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Status</label>
                                <div class="fw-semibold">
                                    <span class="badge bg-success text-white"><?= htmlspecialchars((string)($role['status'] ?? 'active')) ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small">Description</label>
                                <div class="fw-semibold"><?= htmlspecialchars((string)($role['description'] ?? '')) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

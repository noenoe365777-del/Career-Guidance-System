<?php
$user = $user ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'User Details') ?></title>
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
                        <h2 class="fw-bold mb-1">User Details</h2>
                        <p class="text-muted mb-0">View the selected user profile information.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= BASE_URL ?>/index.php?page=admin-users" class="btn btn-outline-secondary">Back</a>
                    </div>
                </div>

                <div class="card card-soft">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Full Name</label>
                                <div class="fw-semibold"><?= htmlspecialchars((string)($user['username'] ?? '')) ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Email</label>
                                <div class="fw-semibold"><?= htmlspecialchars((string)($user['email'] ?? '')) ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Role</label>
                                <div class="fw-semibold"><?= htmlspecialchars((string)($user['role_name'] ?? 'Student')) ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Status</label>
                                <div class="fw-semibold"><?= htmlspecialchars((string)$userModel->getStatusName((int)($user['status_id'] ?? 3))) ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Education Level</label>
                                <div class="fw-semibold"><?= htmlspecialchars((string)$userModel->getEducationLevelName(!empty($user['education_level_id']) ? (int)$user['education_level_id'] : null)) ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Phone</label>
                                <div class="fw-semibold"><?= htmlspecialchars((string)($user['phone'] ?? '')) ?></div>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small">Address</label>
                                <div class="fw-semibold"><?= htmlspecialchars((string)($user['address'] ?? '')) ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Date of Birth</label>
                                <div class="fw-semibold"><?= htmlspecialchars((string)($user['date_of_birth'] ?? '')) ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Created Date</label>
                                <div class="fw-semibold"><?= htmlspecialchars(date('M d, Y', strtotime((string)($user['created_at'] ?? date('Y-m-d'))))) ?></div>
                            </div>
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

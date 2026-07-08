<?php
$students = $students ?? [];
$search = $search ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Student Permission Management') ?></title>
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
                        <h2 class="fw-bold mb-1">Student Permission Management</h2>
                        <p class="text-muted mb-0">Manage the student portal features available to each student account.</p>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-dashboard" class="btn btn-outline-secondary">Back to Dashboard</a>
                </div>

                <div class="card card-soft">
                    <div class="card-body p-4">
                        <form method="get" class="row g-2 mb-4">
                            <input type="hidden" name="page" value="admin-settings-student-permissions">
                            <div class="col-md-8">
                                <input type="text" name="search" class="form-control" placeholder="Search by student name or email" value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($students === []): ?>
                                        <tr><td colspan="4" class="text-center text-muted py-4">No students found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($students as $student): ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold"><?= htmlspecialchars((string)($student['username'] ?? '')) ?></div>
                                                </td>
                                                <td><?= htmlspecialchars((string)($student['email'] ?? '')) ?></td>
                                                <td>
                                                    <?php $statusId = (int)($student['status_id'] ?? 3); ?>
                                                    <span class="badge bg-<?= $statusId === 1 ? 'success' : ($statusId === 2 ? 'warning' : 'secondary') ?>"><?= htmlspecialchars((string)($student['role_name'] ?? 'Student')) ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="<?= BASE_URL ?>/index.php?page=admin-settings-student-permissions-manage&id=<?= (int)($student['user_id'] ?? 0) ?>" class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-shield-lock"></i> Manage
                                                    </a>
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

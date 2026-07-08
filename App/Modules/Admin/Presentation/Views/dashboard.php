<?php
$admin = $admin ?? [];
$activeMenu = $activeMenu ?? 'dashboard';
$adminName = trim((string)($admin['full_name'] ?? $admin['username'] ?? 'Admin'));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Dashboard') ?></title>
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

        .admin-sidebar .nav-link {
            color: #5b6472;
            padding: 0.75rem 0.9rem;
            transition: all 0.2s ease;
        }

        .admin-sidebar .nav-link:hover {
            background: #f4f7ff;
            color: #173b8b;
        }

        .admin-content {
            min-height: calc(100vh - 72px);
        }

        .stat-card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .hero-panel {
            background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);
            color: white;
            border-radius: 1.25rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <div class="d-flex">
        <?php include __DIR__ . '/sidebar.php'; ?>

        <main class="admin-content flex-grow-1 p-3 p-lg-4">
            <div class="container-fluid">
                <div class="hero-panel p-4 p-lg-5 mb-4">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-8">
                            <p class="mb-2 fw-semibold text-white-50">Administration Center</p>
                            <h2 class="fw-bold mb-2">Welcome back, <?= htmlspecialchars($adminName ?: 'Admin') ?>.</h2>
                            <p class="mb-0 text-white-50">Monitor student activity, manage assessments, and keep the platform running smoothly.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="<?= BASE_URL ?>/index.php?page=admin-logout" class="btn btn-light text-primary fw-semibold">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Users</h6>
                                        <h3 class="fw-bold mb-0"><?= number_format((int)($totalUsers ?? 0)) ?></h3>
                                    </div>
                                    <div class="rounded-circle bg-primary-subtle p-3 text-primary">
                                        <i class="bi bi-people-fill fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Assessments</h6>
                                        <h3 class="fw-bold mb-0"><?= number_format((int)($totalAssessments ?? 0)) ?></h3>
                                    </div>
                                    <div class="rounded-circle bg-success-subtle p-3 text-success">
                                        <i class="bi bi-clipboard-check-fill fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Careers</h6>
                                        <h3 class="fw-bold mb-0"><?= number_format((int)($totalCareers ?? 0)) ?></h3>
                                    </div>
                                    <div class="rounded-circle bg-warning-subtle p-3 text-warning">
                                        <i class="bi bi-briefcase-fill fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">System Status</h6>
                                        <h3 class="fw-bold mb-0 <?= htmlspecialchars($systemStatusClass ?? 'text-success') ?>"><?= htmlspecialchars($systemStatus ?? 'Healthy') ?></h3>
                                    </div>
                                    <div class="rounded-circle bg-info-subtle p-3 text-info">
                                        <i class="bi bi-shield-check fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card stat-card h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div>
                                        <h5 class="fw-semibold mb-1">Recent Users</h5>
                                        <p class="text-muted mb-0">Newest accounts created in the system.</p>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary">Live data</span>
                                </div>
                                <?php if (!empty($recentUsers)): ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($recentUsers as $user): ?>
                                            <div class="list-group-item px-0 py-3">
                                                <div class="d-flex justify-content-between align-items-start gap-3">
                                                    <div>
                                                        <div class="fw-semibold"><?= htmlspecialchars((string)($user['full_name'] ?? 'Unknown')) ?></div>
                                                        <div class="text-muted small"><?= htmlspecialchars((string)($user['email'] ?? '')) ?></div>
                                                    </div>
                                                    <small class="text-muted"><?= htmlspecialchars((string)($user['created_at'] ?? '')) ?></small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted small">No users found yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5" id="profile">
                        <div class="card stat-card h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.25rem;">
                                        <?= htmlspecialchars(strtoupper(substr($adminName ?: 'A', 0, 1))) ?>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold mb-1"><?= htmlspecialchars($adminName ?: 'Admin') ?></h5>
                                        <p class="text-muted mb-0">Administrator</p>
                                    </div>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span>Email</span>
                                        <span class="text-muted"><?= htmlspecialchars($admin['email'] ?? 'admin@example.com') ?></span>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span>Access Level</span>
                                        <span class="text-muted">Full control</span>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span>Last Login</span>
                                        <span class="text-muted">Today</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-1">
                    <div class="col-12">
                        <div class="card stat-card h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div>
                                        <h5 class="fw-semibold mb-1">Recent Assessment Submissions</h5>
                                        <p class="text-muted mb-0">Latest student assessment activity.</p>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Activity feed</span>
                                </div>
                                <?php if (!empty($recentSubmissions)): ?>
                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Student</th>
                                                    <th>Assessment</th>
                                                    <th>Status</th>
                                                    <th>Submitted</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentSubmissions as $submission): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars((string)($submission['full_name'] ?? 'Unknown')) ?></td>
                                                        <td><?= htmlspecialchars((string)($submission['title'] ?? 'Untitled')) ?></td>
                                                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars((string)($submission['status'] ?? 'Pending')) ?></span></td>
                                                        <td class="text-muted"><?= htmlspecialchars((string)($submission['created_at'] ?? '')) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted small">No submissions found yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                        <div class="card stat-card h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.25rem;">
                                        <?= htmlspecialchars(strtoupper(substr($adminName ?: 'A', 0, 1))) ?>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold mb-1"><?= htmlspecialchars($adminName ?: 'Admin') ?></h5>
                                        <p class="text-muted mb-0">Administrator</p>
                                    </div>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span>Email</span>
                                        <span class="text-muted"><?= htmlspecialchars($admin['email'] ?? 'admin@example.com') ?></span>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span>Access Level</span>
                                        <span class="text-muted">Full control</span>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span>Last Login</span>
                                        <span class="text-muted">Today</span>
                                    </li>
                                </ul>
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


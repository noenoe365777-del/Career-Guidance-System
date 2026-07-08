<?php
$admin = $admin ?? [];
$users = $users ?? [];
$search = $search ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$totalUsers = $totalUsers ?? 0;
$message = $message ?? null;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'User Management') ?></title>
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
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">User Management</h2>
                        <p class="text-muted mb-0">Monitor, create, edit, and manage system users.</p>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-users-create" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New User
                    </a>
                </div>

                <?php if ($message === 'created'): ?>
                    <div class="alert alert-success">User created successfully.</div>
                <?php elseif ($message === 'updated'): ?>
                    <div class="alert alert-success">User updated successfully.</div>
                <?php elseif ($message === 'deleted'): ?>
                    <div class="alert alert-warning">User deleted successfully.</div>
                <?php elseif ($message === 'not_found'): ?>
                    <div class="alert alert-danger">The selected user was not found.</div>
                <?php endif; ?>

                <div class="card card-soft mb-4">
                    <div class="card-body">
                        <form method="get" class="row g-2 align-items-end">
                            <input type="hidden" name="page" value="admin-users">
                            <div class="col-md-8">
                                <label class="form-label">Search by name or email</label>
                                <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>" placeholder="Search users">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-outline-primary w-100">Search</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-soft">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Education Level</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($users === []): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">No users found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td>#<?= (int)($user['user_id'] ?? 0) ?></td>
                                                <td><?= htmlspecialchars((string)($user['username'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string)($user['email'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string)$userModel->getEducationLevelName(!empty($user['education_level_id']) ? (int)$user['education_level_id'] : null)) ?></td>
                                                <td><span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars((string)($user['role_name'] ?? 'Student')) ?></span></td>
                                                <td><span class="badge bg-success-subtle text-success"><?= htmlspecialchars((string)$userModel->getStatusName((int)($user['status_id'] ?? 3))) ?></span></td>
                                                <td><?= htmlspecialchars(date('M d, Y', strtotime((string)($user['created_at'] ?? date('Y-m-d'))))) ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="<?= BASE_URL ?>/index.php?page=admin-users-view&id=<?= (int)($user['user_id'] ?? 0) ?>" class="btn btn-outline-info">View</a>
                                                        <a href="<?= BASE_URL ?>/index.php?page=admin-users-edit&id=<?= (int)($user['user_id'] ?? 0) ?>" class="btn btn-outline-warning">Edit</a>
                                                        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-users-delete" onsubmit="return confirm('Delete this user?');" class="d-inline">
                                                            <input type="hidden" name="id" value="<?= (int)($user['user_id'] ?? 0) ?>">
                                                            <button class="btn btn-outline-danger" type="submit">Delete</button>
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

                <?php if ($totalPages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= BASE_URL ?>/index.php?page=admin-users&search=<?= urlencode($search) ?>&page_number=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

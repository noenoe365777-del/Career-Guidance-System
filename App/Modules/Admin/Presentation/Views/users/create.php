<?php
$errors = $errors ?? [];
$old = $old ?? [];
$roles = $roles ?? [];
$statuses = $statuses ?? [];
$educationLevels = $educationLevels ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Add User') ?></title>
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
                        <h2 class="fw-bold mb-1">Create User</h2>
                        <p class="text-muted mb-0">Add a new user account and profile details.</p>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-users" class="btn btn-outline-secondary">Back</a>
                </div>

                <div class="card card-soft">
                    <div class="card-body p-4">
                        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-users-store">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="username" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars((string)($old['username'] ?? '')) ?>" required>
                                    <?php if (!empty($errors['username'])): ?><div class="invalid-feedback d-block"><?= htmlspecialchars($errors['username']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars((string)($old['email'] ?? '')) ?>" required>
                                    <?php if (!empty($errors['email'])): ?><div class="invalid-feedback d-block"><?= htmlspecialchars($errors['email']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required>
                                    <?php if (!empty($errors['password'])): ?><div class="invalid-feedback d-block"><?= htmlspecialchars($errors['password']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" required>
                                    <?php if (!empty($errors['confirm_password'])): ?><div class="invalid-feedback d-block"><?= htmlspecialchars($errors['confirm_password']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Role</label>
                                    <select name="user_role_id" class="form-select <?= isset($errors['user_role_id']) ? 'is-invalid' : '' ?>">
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= (int)$role['id'] ?>" <?= ((int)($old['user_role_id'] ?? 2) === (int)$role['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string)$role['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status_id" class="form-select <?= isset($errors['status_id']) ? 'is-invalid' : '' ?>">
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?= (int)$status['id'] ?>" <?= ((int)($old['status_id'] ?? 3) === (int)$status['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string)$status['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Education Level</label>
                                    <select name="education_level_id" class="form-select">
                                        <option value="">Select education level</option>
                                        <?php foreach ($educationLevels as $level): ?>
                                            <option value="<?= (int)$level['id'] ?>" <?= ((int)($old['education_level_id'] ?? 0) === (int)$level['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string)$level['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars((string)($old['phone'] ?? '')) ?>">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars((string)($old['address'] ?? '')) ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="<?= htmlspecialchars((string)($old['date_of_birth'] ?? '')) ?>">
                                </div>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Save User</button>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-users" class="btn btn-outline-secondary">Cancel</a>
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

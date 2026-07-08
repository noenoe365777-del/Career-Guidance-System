<?php
$admin = $admin ?? ($_SESSION['admin'] ?? []);
$adminName = trim((string)($admin['full_name'] ?? $admin['username'] ?? 'Admin'));
$adminInitial = strtoupper(substr($adminName ?: 'A', 0, 1));
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom fixed-top">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-primary" href="<?= BASE_URL ?>/index.php?page=admin-dashboard">
            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white" style="width: 42px; height: 42px;">
                <i class="bi bi-mortarboard-fill"></i>
            </span>
            <span class="d-none d-sm-inline">Career Guidance Admin</span>
        </a>

        <div class="d-flex align-items-center gap-2 ms-auto">
            <button class="btn btn-outline-secondary btn-sm d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMobile" aria-controls="adminSidebarMobile">
                <i class="bi bi-list"></i>
            </button>

            <button class="btn btn-light position-relative border-0" type="button" aria-label="Notifications">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
            </button>

            <div class="dropdown">
                <button class="btn d-flex align-items-center gap-2 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-semibold" style="width: 38px; height: 38px;">
                        <?= htmlspecialchars($adminInitial) ?>
                    </span>
                    <span class="d-none d-lg-block text-start">
                        <span class="d-block fw-semibold text-dark"><?= htmlspecialchars($adminName ?: 'Admin') ?></span>
                        <small class="text-muted">Administrator</small>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-item" href="#profile"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/index.php?page=admin-logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

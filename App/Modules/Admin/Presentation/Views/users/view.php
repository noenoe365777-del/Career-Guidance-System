<?php
$user = $user ?? [];

$pageTitle = 'User Details';
$headerTitle = 'User Details';
$activeMenu = 'users';

ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 m-0">User Details</h1>
        <p class="text-sm text-slate-500 m-0 mt-1">View the selected user profile information.</p>
    </div>
    <div class="flex gap-2">
        <a href="<?= BASE_URL ?>/index.php?page=admin-users"
           class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 no-underline">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Full Name</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($user['username'] ?? '')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Email</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($user['email'] ?? '')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Role</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($user['role_name'] ?? 'Student')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Status</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($user['status_name'] ?? 'Pending')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Education Level</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($user['education_level'] ?? 'Not set')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Phone</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($user['phone'] ?? '')) ?></div>
            </div>
            <div class="md:col-span-2">
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Address</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($user['address'] ?? '')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Date of Birth</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($user['date_of_birth'] ?? '')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Created Date</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars(date('M d, Y', strtotime((string)($user['created_at'] ?? date('Y-m-d'))))) ?></div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

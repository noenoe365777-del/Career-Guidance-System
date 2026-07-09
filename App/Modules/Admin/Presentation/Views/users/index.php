<?php
$admin = $admin ?? [];
$users = $users ?? [];
$search = $search ?? '';
$statusFilter = $statusFilter ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$totalUsers = $totalUsers ?? 0;
$message = $message ?? null;

$pageTitle = 'User Management';
$headerTitle = 'User Management';
$activeMenu = 'users';

ob_start();
?>

<?php if ($message !== null): ?>
    <div class="transform transition-all duration-300">
        <?php if ($message === 'created'): ?>
            <div class="flex items-center gap-3 p-4 border border-emerald-100 bg-emerald-50/50 rounded-2xl text-emerald-800 text-sm font-medium">
                <i class="bi bi-check-circle-fill text-base text-emerald-500"></i>
                <div>User created successfully.</div>
            </div>
        <?php elseif ($message === 'updated'): ?>
            <div class="flex items-center gap-3 p-4 border border-blue-100 bg-blue-50/50 rounded-2xl text-blue-800 text-sm font-medium">
                <i class="bi bi-info-circle-fill text-base text-blue-500"></i>
                <div>User updated successfully.</div>
            </div>
        <?php elseif ($message === 'deleted'): ?>
            <div class="flex items-center gap-3 p-4 border border-amber-100 bg-amber-50/50 rounded-2xl text-amber-800 text-sm font-medium">
                <i class="bi bi-exclamation-triangle-fill text-base text-amber-500"></i>
                <div>User deleted successfully.</div>
            </div>
        <?php elseif ($message === 'not_found'): ?>
            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium">
                <i class="bi bi-x-circle-fill text-base text-rose-500"></i>
                <div>The selected user was not found.</div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Overview</p>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-1">User Management</h1>
        <p class="text-sm text-slate-500 mt-1"><?= number_format($totalUsers) ?> registered user<?= $totalUsers !== 1 ? 's' : '' ?></p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5">
    <form method="get" class="flex flex-col sm:flex-row items-end gap-4 w-full m-0">
        <input type="hidden" name="page" value="admin-users">
        <div class="w-full flex-1">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Search by name or email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                    <i class="bi bi-search text-sm"></i>
                </span>
                <input type="text" name="search"
                       class="block w-full pl-11 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                       value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search users...">
            </div>
        </div>
        <div class="w-full sm:w-44">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Filter by status</label>
            <select name="status"
                    class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150">
                <option value="">All Statuses</option>
                <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 border-0 outline-none whitespace-nowrap">
            <i class="bi bi-funnel mr-2"></i>
            Filter
        </button>
        <?php if ($search !== '' || $statusFilter !== ''): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-users" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 no-underline">
                Clear
            </a>
        <?php endif; ?>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse align-middle">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/50">
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">User</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Education</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Registered</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <?php if ($users === []): ?>
                    <tr>
                        <td colspan="6" class="text-center py-16 text-slate-400 bg-white">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="bi bi-people text-4xl text-slate-200"></i>
                                <span class="text-sm">No users found.</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <?php
                        $userId = (int)($user['user_id'] ?? 0);
                        $fullName = htmlspecialchars((string)($user['username'] ?? ''));
                        $email = htmlspecialchars((string)($user['email'] ?? ''));
                        $educationLevel = htmlspecialchars((string)($user['education_level'] ?? 'Not set'));
                        $statusName = htmlspecialchars((string)($user['status_name'] ?? 'Pending'));
                        $isActive = strtolower($statusName) === 'active';
                        $createdAt = htmlspecialchars(date('M d, Y', strtotime((string)($user['created_at'] ?? date('Y-m-d')))));
                        $profileImage = (string)($user['profile_image'] ?? '');
                        $initials = strtoupper(substr($fullName, 0, 1) ?: '?');
                        ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if ($profileImage !== ''): ?>
                                        <img src="<?= htmlspecialchars($profileImage) ?>" alt=""
                                             class="w-9 h-9 rounded-full object-cover shrink-0">
                                    <?php else: ?>
                                        <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-sm shrink-0">
                                            <?= $initials ?>
                                        </div>
                                    <?php endif; ?>
                                    <span class="font-semibold text-slate-800"><?= $fullName ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-500"><?= $email ?></td>
                            <td class="px-5 py-4 text-slate-500"><?= $educationLevel ?></td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide
                                    <?= $isActive ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' ?>">
                                    <span class="inline-block h-1.5 w-1.5 rounded-full <?= $isActive ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
                                    <?= $statusName ?>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-400 whitespace-nowrap"><?= $createdAt ?></td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1">
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-users-view&id=<?= $userId ?>"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-150 no-underline" title="View Profile">
                                        <i class="bi bi-eye text-base"></i>
                                    </a>
                                    <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-users-toggle-status" class="inline m-0 p-0">
                                        <input type="hidden" name="id" value="<?= $userId ?>">
                                        <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-0 bg-transparent transition-all duration-150 outline-none p-0 cursor-pointer
                                            <?= $isActive ? 'text-slate-400 hover:text-amber-600 hover:bg-amber-50' : 'text-slate-400 hover:text-emerald-600 hover:bg-emerald-50' ?>"
                                                type="submit" title="<?= $isActive ? 'Deactivate' : 'Activate' ?>">
                                            <i class="bi <?= $isActive ? 'bi-pause-circle' : 'bi-play-circle' ?> text-base"></i>
                                        </button>
                                    </form>
                                    <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-users-delete" onsubmit="return confirm('Delete this user permanently?');" class="inline m-0 p-0">
                                        <input type="hidden" name="id" value="<?= $userId ?>">
                                        <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-0 bg-transparent text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all duration-150 outline-none p-0 cursor-pointer" type="submit" title="Delete User">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
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

<?php if ($totalPages > 1): ?>
    <nav class="flex justify-center">
        <ul class="inline-flex items-center gap-1.5 p-1 bg-white border border-slate-100 rounded-xl shadow-sm">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php $isCurrent = ($i === $currentPage); ?>
                <li>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-users&search=<?= urlencode($search) ?>&status=<?= urlencode($statusFilter) ?>&page_number=<?= $i ?>"
                       class="inline-flex items-center justify-center text-xs font-bold rounded-lg transition-all duration-150 no-underline border-0 min-w-[32px] h-8 px-2.5
                              <?= $isCurrent ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-800' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>

<?php
$roles = $roles ?? [];
$search = $search ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$message = $message ?? null;
?>
<div class="px-4 sm:px-8 lg:px-10 py-8 space-y-8 flex-1 max-w-[1600px] w-full mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Role Management</h2>
            <p class="text-slate-500 text-sm mt-1">Manage system roles and access levels.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=admin-roles-create" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition shadow-sm">
            <i class="fas fa-plus-circle"></i>Add Role
        </a>
    </div>

    <?php if ($message === 'created'): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Role created successfully.</div>
    <?php elseif ($message === 'updated'): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Role updated successfully.</div>
    <?php elseif ($message === 'deleted'): ?>
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg text-sm">Role deleted successfully.</div>
    <?php elseif ($message === 'not_found'): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">Role not found.</div>
    <?php endif; ?>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="p-4 sm:p-6">
            <form method="get" class="flex flex-col sm:flex-row gap-3 items-end">
                <input type="hidden" name="page" value="admin-roles">
                <div class="flex-1 w-full">
                    <label for="search" class="block text-sm font-medium text-slate-700 mb-1">Search roles</label>
                    <input id="search" type="text" name="search" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search by role name or description" value="<?= htmlspecialchars($search) ?>">
                </div>
                <button type="submit" class="w-full sm:w-auto bg-white border border-indigo-300 hover:bg-indigo-50 text-indigo-600 px-4 py-2 rounded-lg font-medium text-sm transition">Search</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Number of Users</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Created Date</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($roles)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">No roles found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($roles as $role): ?>
                            <?php
                                $status = trim((string)($role['status'] ?? 'active'));
                                $badgeClass = $status === 'inactive' ? 'bg-slate-100 text-slate-600' : 'bg-green-100 text-green-700';
                            ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-900"><?= htmlspecialchars((string)($role['role_name'] ?? '')) ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-600"><?= htmlspecialchars((string)($role['description'] ?? '')) ?></td>
                                <td class="px-4 py-3 whitespace-nowrap"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-600"><?= htmlspecialchars((string)($role['user_count'] ?? 0)) ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-600"><?= htmlspecialchars(date('M d, Y', strtotime((string)($role['created_at'] ?? date('Y-m-d'))))) ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="<?= BASE_URL ?>/index.php?page=admin-roles-view&id=<?= (int)($role['role_id'] ?? 0) ?>" class="bg-white border border-blue-300 hover:bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-medium transition">View</a>
                                        <a href="<?= BASE_URL ?>/index.php?page=admin-roles-edit&id=<?= (int)($role['role_id'] ?? 0) ?>" class="bg-white border border-amber-300 hover:bg-amber-50 text-amber-600 px-2 py-1 rounded text-xs font-medium transition">Edit</a>
                                        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-roles-delete" onsubmit="return confirm('Delete this role?');" class="inline">
                                            <input type="hidden" name="id" value="<?= (int)($role['role_id'] ?? 0) ?>">
                                            <button type="submit" class="bg-white border border-red-300 hover:bg-red-50 text-red-600 px-2 py-1 rounded text-xs font-medium transition">Delete</button>
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
            <ul class="flex items-center gap-1">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li>
                        <a class="px-3 py-1.5 rounded text-sm font-medium transition <?= $i === $currentPage ? 'bg-indigo-600 text-white' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-50' ?>" href="<?= BASE_URL ?>/index.php?page=admin-roles&search=<?= urlencode($search) ?>&page_number=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>

</div>

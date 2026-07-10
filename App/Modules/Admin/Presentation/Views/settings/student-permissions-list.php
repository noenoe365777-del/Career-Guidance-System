<?php
$students = $students ?? [];
$search = $search ?? '';
ob_start();
?>
<div class="px-4 sm:px-8 lg:px-10 py-8 space-y-8 flex-1 max-w-[1600px] w-full mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Student Permission Management</h2>
            <p class="text-slate-500 text-sm mt-1">Manage the student portal features available to each student account.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=admin-dashboard" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg font-medium text-sm transition">
            <i class="fas fa-arrow-left"></i>Back to Dashboard
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="p-4 sm:p-6">
            <form method="get" class="flex flex-col sm:flex-row gap-3 items-end">
                <input type="hidden" name="page" value="admin-settings-student-permissions">
                <div class="flex-1 w-full">
                    <label for="search" class="block text-sm font-medium text-slate-700 mb-1">Search students</label>
                    <input id="search" type="text" name="search" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search by student name or email" value="<?= htmlspecialchars($search) ?>">
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if ($students === []): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500">No students found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <?php $statusId = (int)($student['status_id'] ?? 3); ?>
                            <?php
                                $badgeClass = $statusId === 1 ? 'bg-green-100 text-green-700' : ($statusId === 2 ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-600');
                                $badgeText = htmlspecialchars((string)($student['role_name'] ?? 'Student'));
                            ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-900"><?= htmlspecialchars((string)($student['username'] ?? '')) ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-600"><?= htmlspecialchars((string)($student['email'] ?? '')) ?></td>
                                <td class="px-4 py-3 whitespace-nowrap"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badgeClass ?>"><?= $badgeText ?></span></td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-settings-student-permissions-manage&id=<?= (int)($student['user_id'] ?? 0) ?>" class="inline-flex items-center gap-1.5 bg-white border border-blue-300 hover:bg-blue-50 text-blue-600 px-2.5 py-1.5 rounded text-xs font-medium transition">
                                        <i class="fas fa-shield-halved"></i> Manage
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

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
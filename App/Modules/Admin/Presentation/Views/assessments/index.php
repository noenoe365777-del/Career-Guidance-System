<?php
$assessments = $assessments ?? [];
$search = $search ?? '';
$totalAssessments = $totalAssessments ?? 0;
$activeAssessments = $activeAssessments ?? 0;
$totalQuestions = $totalQuestions ?? 0;
$message = $message ?? null;

$pageTitle = 'Assessment Management';
$headerTitle = 'Assessment Management';
$activeMenu = 'assessments';

ob_start();
?>

<?php if ($message !== null): ?>
    <div class="transform transition-all duration-300">
        <?php if ($message === 'updated'): ?>
            <div class="flex items-center gap-3 p-4 border border-blue-100 bg-blue-50/50 rounded-2xl text-blue-800 text-sm font-medium">
                <i class="bi bi-info-circle-fill text-base text-blue-500"></i>
                <div>Assessment updated successfully.</div>
            </div>
        <?php elseif ($message === 'not_found'): ?>
            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium">
                <i class="bi bi-x-circle-fill text-base text-rose-500"></i>
                <div>The selected assessment was not found.</div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Assessments</p>
        <div class="mt-2 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600">
                <i class="bi bi-clipboard-check text-lg"></i>
            </div>
            <span class="text-3xl font-bold text-indigo-600"><?= (int)$totalAssessments ?></span>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Active Assessments</p>
        <div class="mt-2 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600">
                <i class="bi bi-check-circle text-lg"></i>
            </div>
            <span class="text-3xl font-bold text-emerald-600"><?= (int)$activeAssessments ?></span>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Questions</p>
        <div class="mt-2 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-50 text-amber-600">
                <i class="bi bi-question-circle text-lg"></i>
            </div>
            <span class="text-3xl font-bold text-amber-600"><?= (int)$totalQuestions ?></span>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5">
    <form method="get" class="flex flex-col sm:flex-row items-end gap-4 w-full m-0">
        <input type="hidden" name="page" value="admin-assessments">
        <div class="w-full flex-1">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Search by name</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                    <i class="bi bi-search text-sm"></i>
                </span>
                <input type="text" name="search"
                       class="block w-full pl-11 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                       value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search assessments...">
            </div>
        </div>
        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 border-0 outline-none whitespace-nowrap">
            <i class="bi bi-search mr-2"></i>
            Search
        </button>
        <?php if ($search !== ''): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-assessments" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 no-underline">
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
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Assessment</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-center">Questions</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <?php if ($assessments === []): ?>
                    <tr>
                        <td colspan="6" class="text-center py-16 text-slate-400 bg-white">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="bi bi-clipboard-check text-4xl text-slate-200"></i>
                                <span class="text-sm">No assessments found.</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($assessments as $assessment): ?>
                        <?php
                        $assessmentId = (int)($assessment['assessment_id'] ?? 0);
                        $title = htmlspecialchars((string)($assessment['title'] ?? ''));
                        $description = htmlspecialchars((string)($assessment['description'] ?? ''));
                        $questionCount = (int)($assessment['total_questions'] ?? 0);
                        $status = strtolower((string)($assessment['status'] ?? 'active'));
                        $isActive = $status === 'active';
                        $createdAt = htmlspecialchars(date('M d, Y', strtotime((string)($assessment['created_at'] ?? date('Y-m-d')))));
                        ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-sm shrink-0">
                                        <?= htmlspecialchars(substr($title, 0, 1)) ?>
                                    </div>
                                    <span class="font-semibold text-slate-800"><?= $title ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-500 max-w-[240px] truncate"><?= $description ?></td>
                            <td class="px-5 py-4 text-center text-slate-700 font-semibold"><?= $questionCount ?></td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide
                                    <?= $isActive ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' ?>">
                                    <span class="inline-block h-1.5 w-1.5 rounded-full <?= $isActive ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
                                    <?= ucfirst($status) ?>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-400 whitespace-nowrap"><?= $createdAt ?></td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1">
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-assessments-view&id=<?= $assessmentId ?>"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-150 no-underline" title="View Details">
                                        <i class="bi bi-eye text-base"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-assessments-edit&id=<?= $assessmentId ?>"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-150 no-underline" title="Edit Assessment">
                                        <i class="bi bi-pencil text-sm"></i>
                                    </a>
                                    <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-assessments-toggle-status" class="inline m-0 p-0">
                                        <input type="hidden" name="id" value="<?= $assessmentId ?>">
                                        <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-0 bg-transparent transition-all duration-150 outline-none p-0 cursor-pointer
                                            <?= $isActive ? 'text-slate-400 hover:text-amber-600 hover:bg-amber-50' : 'text-slate-400 hover:text-emerald-600 hover:bg-emerald-50' ?>"
                                                    type="submit" title="<?= $isActive ? 'Deactivate' : 'Activate' ?>">
                                                <i class="bi <?= $isActive ? 'bi-pause-circle' : 'bi-play-circle' ?> text-base"></i>
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

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

<?php
$career = $career ?? [];
$recommendationStudents = $recommendationStudents ?? [];

$pageTitle = 'Career Details';
$headerTitle = 'Career Details';
$activeMenu = 'careers';

ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 m-0">Career Details</h1>
        <p class="text-sm text-slate-500 m-0 mt-1">View the selected career path information.</p>
    </div>
    <div class="flex gap-2">
        <a href="<?= BASE_URL ?>/index.php?page=admin-careers"
           class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 no-underline">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Career Name</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['career_name'] ?? '')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Status</label>
                <div class="mt-1">
                    <?php $status = $career['status'] ?? 'active'; ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide <?= $status === 'active' ? 'text-emerald-700 bg-emerald-50' : 'text-slate-500 bg-slate-100' ?>">
                        <span class="w-1.5 h-1.5 rounded-full <?= $status === 'active' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                        <?= $status === 'active' ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Average Salary</label>
                <div class="font-semibold text-slate-700 mt-1">
                    <?php
                    $salary = (string)($career['average_salary'] ?? '');
                    $salaryNum = preg_replace('/[^0-9.]/', '', $salary);
                    echo $salaryNum !== '' && $salaryNum !== '0' ? 'PHP ' . number_format((float)$salaryNum) : '—';
                    ?>
                </div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Growth Rate (Job Outlook)</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['growth_rate'] ?? '—')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Education Required</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['education_required'] ?? '—')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Recommendation Count</label>
                <div class="font-semibold text-slate-700 mt-1"><?= (int)($career['recommendation_count'] ?? 0) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Personality Type</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['personality_type'] ?? '—')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Interest Type</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['interest_type'] ?? '—')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Aptitude Type</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['aptitude_type'] ?? '—')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Values Type</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['values_type'] ?? '—')) ?></div>
            </div>
            <div class="md:col-span-2">
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Description</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['description'] ?? 'No description')) ?></div>
            </div>
            <div class="md:col-span-2">
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Required Skills</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['required_skills'] ?? 'None specified')) ?></div>
            </div>
        </div>
    </div>
</div>

<?php if ($recommendationStudents !== []): ?>
<div class="bg-white rounded-2xl shadow-sm overflow-hidden mt-6">
    <div class="p-6">
        <h3 class="text-base font-bold text-slate-800 mb-1">Students Who Received This Recommendation</h3>
        <p class="text-sm text-slate-500 mb-4">List of students who were recommended this career path.</p>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse align-middle">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="whitespace-nowrap px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="whitespace-nowrap px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Match Score</th>
                        <th class="whitespace-nowrap px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Recommendation Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php foreach ($recommendationStudents as $rs): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-semibold text-slate-700"><?= htmlspecialchars((string)($rs['username'] ?? $rs['email'] ?? 'Unknown')) ?></span>
                                <div class="text-xs text-slate-400"><?= htmlspecialchars((string)($rs['email'] ?? '')) ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <?php if (isset($rs['match_score'])): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold <?= (float)$rs['match_score'] >= 80 ? 'text-emerald-600 bg-emerald-50' : ((float)$rs['match_score'] >= 60 ? 'text-amber-600 bg-amber-50' : 'text-slate-600 bg-slate-50') ?>">
                                        <?= htmlspecialchars((string)$rs['match_score']) ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-slate-500">
                                <?php if (isset($rs['created_at'])): ?>
                                    <?= date('M j, Y g:i A', strtotime($rs['created_at'])) ?>
                                <?php else: ?>
                                    <span class="text-slate-400">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="flex gap-3 mt-6">
    <a href="<?= BASE_URL ?>/index.php?page=admin-careers-edit&id=<?= (int)($career['career_id'] ?? 0) ?>"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 no-underline">
        <i class="bi bi-pencil"></i>
        Edit Career
    </a>
    <a href="<?= BASE_URL ?>/index.php?page=admin-careers"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 no-underline">
        <i class="bi bi-arrow-left"></i>
        Back to List
    </a>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
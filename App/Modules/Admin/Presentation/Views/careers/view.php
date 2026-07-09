<?php
$career = $career ?? [];

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
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Average Salary</label>
                <div class="font-semibold text-slate-700 mt-1">
                    <?php
                    $salary = (string)($career['average_salary'] ?? '');
                    echo $salary !== '' ? 'PHP ' . htmlspecialchars($salary) : '—';
                    ?>
                </div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Growth Rate</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['growth_rate'] ?? '—')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Education Required</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($career['education_required'] ?? '—')) ?></div>
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

<?php
$assessment = $assessment ?? [];

$pageTitle = 'Assessment Details';
$headerTitle = 'Assessment Details';
$activeMenu = 'assessments';

ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 m-0">Assessment Details</h1>
        <p class="text-sm text-slate-500 m-0 mt-1">View the selected assessment information.</p>
    </div>
    <div class="flex gap-2">
        <a href="<?= BASE_URL ?>/index.php?page=admin-assessments"
           class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 no-underline">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Assessment Name</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($assessment['title'] ?? '')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Category</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($assessment['category'] ?? '')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Status</label>
                <div class="font-semibold text-slate-700 mt-1">
                    <?php $isActive = strtolower((string)($assessment['status'] ?? 'active')) === 'active'; ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold tracking-wide
                        <?= $isActive ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' ?>">
                        <span class="inline-block h-1.5 w-1.5 rounded-full <?= $isActive ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
                        <?= ucfirst((string)($assessment['status'] ?? 'active')) ?>
                    </span>
                </div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Total Questions</label>
                <div class="font-semibold text-slate-700 mt-1"><?= (int)($assessment['total_questions'] ?? 0) ?></div>
            </div>
            <div class="md:col-span-2">
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Description</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($assessment['description'] ?? 'No description')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Created Date</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars(date('M d, Y', strtotime((string)($assessment['created_at'] ?? date('Y-m-d'))))) ?></div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

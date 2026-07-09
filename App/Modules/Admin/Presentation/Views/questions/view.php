<?php
$question = $question ?? [];
$options = $options ?? [];

$pageTitle = 'Question Details';
$headerTitle = 'Question Details';
$activeMenu = 'questions';

ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 m-0">Question Details</h1>
        <p class="text-sm text-slate-500 m-0 mt-1">View the selected question and its answer options.</p>
    </div>
    <div class="flex gap-2">
        <a href="<?= BASE_URL ?>/index.php?page=admin-questions"
           class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 no-underline">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Question Text</label>
                <div class="font-semibold text-slate-700 mt-1 text-base"><?= htmlspecialchars((string)($question['question_text'] ?? '')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Assessment</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars((string)($question['assessment_title'] ?? '')) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Question Type</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', (string)($question['question_type'] ?? 'single_choice')))) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Order Number</label>
                <div class="font-semibold text-slate-700 mt-1"><?= (int)($question['question_order'] ?? 0) ?></div>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-medium uppercase tracking-wide">Created Date</label>
                <div class="font-semibold text-slate-700 mt-1"><?= htmlspecialchars(date('M d, Y', strtotime((string)($question['created_at'] ?? date('Y-m-d'))))) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden mt-6">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="text-base font-bold text-slate-800 m-0">Answer Options (<?= count($options) ?>)</h3>
    </div>
    <?php if ($options === []): ?>
        <div class="p-6 text-center text-slate-400 text-sm">No options defined for this question.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse align-middle">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="whitespace-nowrap px-5 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="whitespace-nowrap px-5 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Option Text</th>
                        <th class="whitespace-nowrap px-5 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-center">Value</th>
                        <th class="whitespace-nowrap px-5 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-center">Order</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php foreach ($options as $i => $opt): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <td class="px-5 py-3 text-slate-400"><?= $i + 1 ?></td>
                            <td class="px-5 py-3 font-medium text-slate-700"><?= htmlspecialchars((string)($opt['option_text'] ?? '')) ?></td>
                            <td class="px-5 py-3 text-center text-slate-700"><?= number_format((float)($opt['option_value'] ?? 0), 2) ?></td>
                            <td class="px-5 py-3 text-center text-slate-700"><?= (int)($opt['option_order'] ?? $i + 1) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="flex gap-3 mt-6">
    <a href="<?= BASE_URL ?>/index.php?page=admin-questions-edit&id=<?= (int)($question['question_id'] ?? 0) ?>"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 no-underline">
        <i class="bi bi-pencil"></i>
        Edit Question
    </a>
    <a href="<?= BASE_URL ?>/index.php?page=admin-questions"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 no-underline">
        <i class="bi bi-arrow-left"></i>
        Back to List
    </a>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

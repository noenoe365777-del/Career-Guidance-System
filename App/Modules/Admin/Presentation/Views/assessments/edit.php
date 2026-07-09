<?php
$assessment = $assessment ?? [];
$errors = $errors ?? [];

$pageTitle = 'Edit Assessment';
$headerTitle = 'Edit Assessment';
$activeMenu = 'assessments';

ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 m-0">Edit Assessment</h1>
        <p class="text-sm text-slate-500 m-0 mt-1">Update the assessment title and description.</p>
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
        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-assessments-update" class="space-y-5">
            <input type="hidden" name="id" value="<?= (int)($assessment['assessment_id'] ?? 0) ?>">

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="title">Assessment Title</label>
                <input type="text" id="title" name="title"
                       class="block w-full px-4 py-2.5 text-sm bg-white border <?= isset($errors['title']) ? 'border-red-300' : 'border-slate-200' ?> rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                       value="<?= htmlspecialchars((string)($assessment['title'] ?? '')) ?>"
                       placeholder="Enter assessment title">
                <?php if (isset($errors['title'])): ?>
                    <p class="mt-1.5 text-xs font-medium text-red-500"><?= htmlspecialchars($errors['title']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="description">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                          placeholder="Enter assessment description"><?= htmlspecialchars((string)($assessment['description'] ?? '')) ?></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 border-0 outline-none">
                    <i class="bi bi-check-lg mr-2"></i>
                    Update Assessment
                </button>
                <a href="<?= BASE_URL ?>/index.php?page=admin-assessments"
                   class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 no-underline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

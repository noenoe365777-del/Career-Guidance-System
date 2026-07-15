<?php
$assessment = $result['assessment'] ?? [];
$attempt = $result['attempt'] ?? [];
$score = $result['score'] ?? 0;
$typeLabel = $result['type_label'] ?? 'Not Available';
$interpretation = $result['interpretation'] ?? '';
$slug = $assessment['slug'] ?? ($_GET['slug'] ?? '');
$completedAt = isset($attempt['completed_at']) ? date('F j, Y, g:i a', strtotime($attempt['completed_at'])) : 'N/A';
$maxScore = 100;
$percentage = $maxScore > 0 ? (int)round(($score / $maxScore) * 100) : 0;
?>

<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6">
    <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2"
       class="inline-flex items-center text-sm font-bold text-blue-600 transition-colors hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Assessments
    </a>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-100">Assessment Complete</p>
            <h1 class="mt-1 text-2xl font-extrabold sm:text-3xl"><?= htmlspecialchars($assessment['title'] ?? '') ?></h1>
        </div>

        <div class="grid gap-6 p-8 sm:grid-cols-2">
            <div class="rounded-xl bg-slate-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Percentage Score</p>
                <p class="mt-1 text-3xl font-extrabold text-blue-600"><?= $percentage ?>%</p>
            </div>
            <div class="rounded-xl bg-slate-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Score</p>
                <p class="mt-1 text-3xl font-extrabold text-slate-900"><?= $score ?> / <?= $maxScore ?></p>
            </div>
            <div class="rounded-xl bg-slate-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Completed</p>
                <p class="mt-1 text-lg font-semibold text-slate-700"><?= htmlspecialchars($completedAt) ?></p>
            </div>
            <div class="rounded-xl bg-slate-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Result Type</p>
                <span class="mt-1 inline-block rounded-full bg-blue-100 px-4 py-1.5 text-sm font-semibold text-blue-700">
                    <?= htmlspecialchars($typeLabel) ?>
                </span>
            </div>
        </div>

        <?php if ($interpretation): ?>
            <div class="border-t border-slate-100 px-8 pb-8">
                <div class="rounded-xl bg-indigo-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-indigo-500">Interpretation</p>
                    <p class="mt-2 text-sm leading-relaxed text-slate-700"><?= htmlspecialchars($interpretation) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="border-t border-slate-100 px-8 pb-8">
            <a href="<?= BASE_URL ?>/index.php?page=assessment-detailed-answers&slug=<?= htmlspecialchars($slug) ?>"
               class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                <i class="fas fa-eye"></i>
                View Detailed Answers
            </a>
        </div>
    </div>
</div>

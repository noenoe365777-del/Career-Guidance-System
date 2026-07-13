<?php
$completed = count(array_filter($assessments, static function ($assessment): bool {
    return (($assessment['progress']['status'] ?? '') === 'completed');
}));
$progressPercent = count($assessments) > 0 ? (int)round(($completed / count($assessments)) * 100) : 0;
$allCompleted = count($assessments) > 0 && $completed === count($assessments);

function statusBadge(string $status): string
{
    return match (strtolower($status)) {
        'completed' => '<span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Completed</span>',
        'in_progress', 'in progress' => '<span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>In Progress</span>',
        'not_started', 'not started' => '<span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-600">Not Started</span>',
        default => '<span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-500">Locked</span>',
    };
}

function formatCompletedDate(?string $value): string
{
    if (empty($value)) {
        return '';
    }

    $timestamp = strtotime($value);
    return $timestamp ? date('M j, Y', $timestamp) : '';
}
?>

<div class="mx-auto w-full max-w-7xl overflow-x-hidden px-4 py-6 sm:px-6 lg:px-8">


    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <?php foreach ($assessments as $assessment): ?>
            <?php
            $status = $assessment['progress']['status'] ?? 'not_started';
            $completedAt = $assessment['progress']['completed_at'] ?? null;
            $answeredCount = (int)($assessment['progress']['answered'] ?? 0);
            $totalQuestions = (int)($assessment['questions_count'] ?? 0);
            $progressPct = $totalQuestions > 0 ? min(100, (int)round(($answeredCount / $totalQuestions) * 100)) : 0;
            $actionLabel = $status === 'completed' ? 'View Result' : ($status === 'in_progress' ? 'Continue' : 'Start');
            $actionIcon = $status === 'completed' ? 'fa-chart-bar' : 'fa-play';
            ?>
            <div class="flex h-full flex-col rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl <?= $assessment['iconBg']; ?> shadow-sm">
                        <i class="<?= $assessment['icon']; ?> <?= $assessment['iconColor']; ?> text-xl"></i>
                    </div>
                    <div class="min-w-0">
                        <h2 class="truncate text-base font-semibold text-slate-900">
                            <?= htmlspecialchars($assessment['title']); ?>
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            <?= htmlspecialchars($assessment['questions']); ?>
                        </p>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between gap-2">
                    <?= statusBadge($status); ?>
                    <?php if ($status === 'completed' && !empty($completedAt)): ?>
                        <span class="text-[11px] font-medium text-slate-400">Done</span>
                    <?php endif; ?>
                </div>

                <?php if ($status === 'completed' && !empty($completedAt)): ?>
                    <p class="mt-3 text-xs text-slate-500">
                        Completed <?= htmlspecialchars(formatCompletedDate($completedAt)); ?>
                    </p>
                <?php elseif ($status === 'in_progress' && $totalQuestions > 0): ?>
                    <div class="mt-3">
                        <div class="mb-1 flex items-center justify-between text-[11px] text-slate-500">
                            <span>Progress</span>
                            <span class="font-semibold text-slate-600"><?= $answeredCount; ?>/<?= $totalQuestions; ?></span>
                        </div>
                        <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-gradient-to-r <?= $assessment['button']; ?>" style="width: <?= $progressPct; ?>%"></div>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="mt-3 text-xs text-slate-500">Ready to begin when you’re ready.</p>
                <?php endif; ?>

                <?php
                $linkPage = $status === 'completed'
                    ? 'assessment-result&slug=' . $assessment['slug']
                    : $assessment['page'];
                ?>
                <a href="<?= BASE_URL ?>/index.php?page=<?= htmlspecialchars($linkPage); ?>"
                   class="mt-5 inline-flex w-full items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition duration-200 <?= $assessment['button']; ?>">
                    <i class="fas <?= $actionIcon; ?> mr-2"></i>
                    <?= $actionLabel; ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <section class="mt-6 rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-700">
                    Career Recommendation
                </span>
                <?php if ($allCompleted): ?>
                    <h3 class="mt-3 text-lg font-semibold text-slate-900">Your recommendation is ready</h3>
                    <p class="mt-2 text-sm text-slate-500">You’ve completed every assessment and can now view your personalized career matches.</p>
                <?php else: ?>
                    <h3 class="mt-3 text-lg font-semibold text-slate-900">Unlocks after all assessments</h3>
                    <p class="mt-2 text-sm text-slate-500">You’re <?= $completed; ?>/<?= count($assessments); ?> assessments complete. Finish the remaining ones to unlock your recommendation.</p>
                <?php endif; ?>
            </div>

            <?php if ($allCompleted): ?>
                <a href="<?= BASE_URL ?>/index.php?page=recommendation"
                   class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:from-indigo-700 hover:to-violet-700">
                    <i class="fas fa-arrow-right mr-2"></i> View Recommendation
                </a>
            <?php else: ?>
                <div class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-500">
                    Locked until complete
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

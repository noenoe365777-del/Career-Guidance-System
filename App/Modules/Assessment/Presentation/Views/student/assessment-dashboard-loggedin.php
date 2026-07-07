<?php
$completed = count(array_filter($assessments, static function ($assessment): bool {
    return (($assessment['progress']['status'] ?? '') === 'completed');
}));
$progressPercent = count($assessments) > 0 ? (int)round(($completed / count($assessments)) * 100) : 0;
$allCompleted = count($assessments) > 0 && $completed === count($assessments);
?>

<div class="mx-auto max-w-7xl px-6 py-16">
    <div class="mb-10 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Assessment Hub</p>
                <h1 class="mt-2 text-4xl font-extrabold text-blue-900">My Assessments</h1>
                <p class="mt-3 max-w-2xl text-gray-600">
                    Welcome back, <?= htmlspecialchars($user['full_name'] ?? $user['name'] ?? 'student'); ?>. Complete each assessment to build a stronger career profile.
                </p>
            </div>
            <div class="rounded-2xl bg-slate-50 px-5 py-4 text-sm text-slate-600">
                <div class="text-3xl font-bold text-slate-900"><?= $progressPercent; ?>%</div>
                <div>of your assessments completed</div>
            </div>
        </div>

        <div class="mt-6 h-2.5 rounded-full bg-slate-100">
            <div class="h-2.5 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500" style="width: <?= $progressPercent; ?>%"></div>
        </div>

        <?php if ($allCompleted): ?>
            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="<?= BASE_URL ?>/index.php?page=recommendation"
                   class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    <i class="fas fa-star mr-2"></i> View Final Career Recommendation
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-4">
        <?php foreach ($assessments as $assessment): ?>
            <?php $status = $assessment['progress']['status'] ?? 'not_started'; ?>
            <div class="flex flex-col items-center rounded-3xl border border-gray-100 bg-white p-8 shadow-lg transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl">
                <div class="mb-6 flex h-24 w-24 items-center justify-center rounded-full <?= $assessment['iconBg']; ?> transition duration-500 hover:scale-110">
                    <i class="<?= $assessment['icon']; ?> <?= $assessment['iconColor']; ?> text-5xl"></i>
                </div>

                <h2 class="text-center text-2xl font-bold text-gray-800">
                    <?= htmlspecialchars($assessment['title']); ?>
                </h2>

                <p class="mt-4 flex-grow text-center leading-7 text-gray-600">
                    <?= htmlspecialchars($assessment['description']); ?>
                </p>

                <div class="mt-6">
                    <span class="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium">
                        <?= htmlspecialchars($assessment['questions']); ?>
                    </span>
                </div>

                <div class="mt-4 text-sm font-medium text-slate-500">
                    <?php if ($status === 'completed'): ?>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-700">Completed</span>
                    <?php else: ?>
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-700">In progress</span>
                    <?php endif; ?>
                </div>

                <a href="<?= BASE_URL ?>/index.php?page=<?= htmlspecialchars($assessment['page']); ?>"
                   class="<?= $assessment['button']; ?> mt-8 w-full rounded-xl py-3 text-center font-semibold text-white transition duration-300 hover:scale-105">
                    <i class="fas fa-play mr-2"></i>
                    <?= $status === 'completed' ? 'Review Again' : 'Start Assessment' ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

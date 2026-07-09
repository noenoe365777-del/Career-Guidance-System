<?php
$recommendations = $recommendations ?? [];
$hasRecommendations = count($recommendations) > 0;
?>

<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Career Recommendation</p>
                <h1 class="mt-2 text-3xl font-extrabold text-slate-900">Your Top Career Matches</h1>
                <p class="mt-3 max-w-2xl text-sm text-slate-600">
                    Based on your assessment results and education level, here are the careers that match you best.
                </p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=student-assessments"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                <i class="fas fa-arrow-left"></i>
                Back to Assessments
            </a>
        </div>

        <?php if ($hasRecommendations): ?>
            <div class="mt-8 space-y-6">
                <?php foreach ($recommendations as $index => $rec): ?>
                    <?php
                    $rank = $index + 1;
                    $borderColors = ['border-emerald-400', 'border-blue-400', 'border-violet-400', 'border-amber-400', 'border-slate-400'];
                    $badgeColors = ['bg-emerald-600', 'bg-blue-600', 'bg-violet-600', 'bg-amber-600', 'bg-slate-600'];
                    $borderColor = $borderColors[$index] ?? 'border-slate-300';
                    $badgeColor = $badgeColors[$index] ?? 'bg-slate-500';
                    ?>

                    <div class="rounded-2xl border-l-4 <?= $borderColor ?> border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold text-white <?= $badgeColor ?>">
                                        <?= $rank ?>
                                    </span>
                                    <h2 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($rec->careerName) ?></h2>
                                </div>
                                <p class="mt-3 text-sm leading-relaxed text-slate-600"><?= htmlspecialchars($rec->description) ?></p>
                            </div>
                            <div class="shrink-0 rounded-xl bg-slate-50 px-5 py-3 text-center">
                                <div class="text-2xl font-extrabold text-emerald-600"><?= (int)$rec->matchPercent ?>%</div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Match</div>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Required Skills</p>
                                <p class="mt-1 text-sm text-slate-700"><?= htmlspecialchars($rec->requiredSkills ?: 'Not specified') ?></p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Education Required</p>
                                <p class="mt-1 text-sm text-slate-700"><?= htmlspecialchars($rec->educationRequired ?: 'Not specified') ?></p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Average Salary</p>
                                <p class="mt-1 text-sm text-slate-700"><?= htmlspecialchars($rec->averageSalary ?: 'N/A') ?></p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Growth Rate</p>
                                <p class="mt-1 text-sm text-slate-700"><?= htmlspecialchars($rec->growthRate ?: 'N/A') ?></p>
                            </div>
                        </div>

                        <div class="mt-4 rounded-lg bg-indigo-50 p-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-500">Why This Career</p>
                            <p class="mt-1 text-sm text-slate-700"><?= htmlspecialchars($rec->reason) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-8 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-amber-100">
                    <i class="fas fa-clipboard-list text-2xl text-amber-500"></i>
                </div>
                <p class="text-sm font-semibold text-amber-800">Complete all four assessments to generate your career recommendations.</p>
                <p class="mt-2 text-sm text-amber-600">Take the Personality, Interest, Aptitude, and Values assessments first.</p>
                <a href="<?= BASE_URL ?>/index.php?page=student-assessments"
                   class="mt-6 inline-flex items-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700">
                    <i class="fas fa-arrow-right"></i>
                    Go to Assessments
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

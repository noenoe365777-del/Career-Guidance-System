<?php
$recommendation = $recommendation ?? null;
?>

<div class="mx-auto max-w-6xl px-6 py-16">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Career Recommendation</p>
                <h1 class="mt-2 text-3xl font-extrabold text-slate-900">Your Personalized Career Match</h1>
                <p class="mt-3 max-w-2xl text-slate-600">
                    This report combines your assessment results with your education level to suggest the career that fits you best.
                </p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=assessments"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                <i class="fas fa-arrow-left mr-2"></i> Back to Assessments
            </a>
        </div>

        <?php if ($recommendation): ?>
            <div class="mt-8 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-700">Top Match</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-900"><?= htmlspecialchars($recommendation->careerName ?? 'Career'); ?></h2>
                        </div>
                        <div class="rounded-2xl bg-white px-4 py-3 text-center shadow-sm">
                            <div class="text-2xl font-bold text-emerald-700"><?= (int)($recommendation->matchPercent ?? 0); ?>%</div>
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Match</div>
                        </div>
                    </div>

                    <p class="mt-5 text-sm leading-7 text-slate-700">
                        <?= htmlspecialchars($recommendation->description ?? 'Based on your assessment scores, this career is a strong fit.'); ?>
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Recommended Skills</h3>
                    <ul class="mt-4 space-y-2 text-sm text-slate-700">
                        <?php foreach ($recommendation->skills ?? [] as $skill): ?>
                            <li class="rounded-lg bg-white px-3 py-2 shadow-sm">• <?= htmlspecialchars($skill); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Suggested University Majors</h3>
                    <ul class="mt-4 list-disc space-y-2 pl-5 text-sm text-slate-700">
                        <?php foreach ($recommendation->recommendedMajors ?? [] as $major): ?>
                            <li><?= htmlspecialchars($major); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Suggested Learning Resources</h3>
                    <ul class="mt-4 space-y-2 text-sm text-slate-700">
                        <?php foreach ($recommendation->resources ?? [] as $resource): ?>
                            <li>
                                <a href="<?= htmlspecialchars($resource); ?>" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">
                                    <?= htmlspecialchars($resource); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-800">
                Complete all four assessments first to generate your final career recommendation.
            </div>
        <?php endif; ?>
    </div>
</div>


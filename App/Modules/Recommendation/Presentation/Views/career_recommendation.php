<?php
$recommendations = $recommendations ?? [];
$hasRecommendations = $hasRecommendations ?? false;
$interpretation = $interpretation ?? [];
$strengths = $strengths ?? [];
$growthAreas = $growthAreas ?? [];

function matchLevelLabel(float $pct): string
{
    return match (true) {
        $pct >= 90 => 'Excellent Match',
        $pct >= 75 => 'Strong Match',
        $pct >= 60 => 'Good Match',
        default => 'Average Match',
    };
}

function matchBadgeClass(float $pct): string
{
    return match (true) {
        $pct >= 90 => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        $pct >= 75 => 'bg-blue-100 text-blue-700 border-blue-200',
        $pct >= 60 => 'bg-violet-100 text-violet-700 border-violet-200',
        default => 'bg-slate-100 text-slate-600 border-slate-200',
    };
}

function matchDotClass(float $pct): string
{
    return match (true) {
        $pct >= 90 => 'bg-emerald-500',
        $pct >= 75 => 'bg-blue-500',
        $pct >= 60 => 'bg-violet-500',
        default => 'bg-slate-400',
    };
}

$careerColors = [
    'Software Engineer' => 'indigo',
    'Data Analyst' => 'indigo',
    'Graphic Designer' => 'pink',
    'Teacher' => 'purple',
    'Doctor' => 'emerald',
    'Accountant' => 'blue',
    'Civil Engineer' => 'cyan',
    'Mechanical Engineer' => 'cyan',
    'Marketing Specialist' => 'orange',
    'Nurse' => 'emerald',
    'Electrician' => 'amber',
    'Plumber' => 'amber',
    'Certified Nursing Assistant (CNA)' => 'emerald',
    'Retail Manager' => 'orange',
    'HVAC Technician' => 'cyan',
    'Administrative Assistant' => 'slate',
    'Security Guard' => 'slate',
    'Chef / Cook' => 'orange',
];

$colorMap = [
    'indigo' => ['bg' => 'indigo', 'text' => 'indigo', 'grad' => 'from-indigo-500 to-indigo-600'],
    'emerald' => ['bg' => 'emerald', 'text' => 'emerald', 'grad' => 'from-emerald-500 to-emerald-600'],
    'blue' => ['bg' => 'blue', 'text' => 'blue', 'grad' => 'from-blue-500 to-blue-600'],
    'orange' => ['bg' => 'amber', 'text' => 'amber', 'grad' => 'from-amber-500 to-orange-500'],
    'cyan' => ['bg' => 'cyan', 'text' => 'cyan', 'grad' => 'from-cyan-500 to-cyan-600'],
    'purple' => ['bg' => 'purple', 'text' => 'purple', 'grad' => 'from-purple-500 to-purple-600'],
    'pink' => ['bg' => 'pink', 'text' => 'pink', 'grad' => 'from-pink-500 to-pink-600'],
    'amber' => ['bg' => 'amber', 'text' => 'amber', 'grad' => 'from-amber-500 to-amber-600'],
    'slate' => ['bg' => 'slate', 'text' => 'slate', 'grad' => 'from-slate-500 to-slate-600'],
];
?>
<div class="mx-auto max-w-6xl overflow-x-hidden px-4 py-8 sm:px-6">
    <section class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Career Recommendation</p>
            <h1 class="mt-2 text-2xl font-extrabold text-slate-900 sm:text-3xl">Your Top Career Matches</h1>
            <p class="mt-2 max-w-xl text-sm text-slate-500">Based on your assessment results and education level, here are the careers that match you best.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 no-underline transition-all duration-200 hover:border-slate-300 hover:bg-slate-50">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to Assessments
        </a>
    </section>

    <?php if ($hasRecommendations): ?>

    <?php if ($interpretation): ?>
    <div class="mb-8 grid gap-6 md:grid-cols-3">
        <div class="rounded-xl border border-slate-100 bg-white/80 p-5 md:col-span-1">
            <div class="flex items-center gap-2 mb-3">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600"><i class="bi bi-info-circle text-sm"></i></span>
                <h3 class="text-sm font-bold text-slate-900">Interpretation</h3>
            </div>
            <?php if (!empty($interpretation['level'])): ?>
            <span class="inline-block rounded-full bg-<?= $interpretation['color'] ?? 'slate' ?>-100 px-3 py-1 text-xs font-bold text-<?= $interpretation['color'] ?? 'slate' ?>-700 mb-2"><?= htmlspecialchars($interpretation['level']) ?></span>
            <p class="text-sm text-slate-600 leading-relaxed"><?= htmlspecialchars($interpretation['text'] ?? '') ?></p>
            <?php endif; ?>
        </div>

        <div class="rounded-xl border border-slate-100 bg-white/80 p-5">
            <div class="flex items-center gap-2 mb-3">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600"><i class="bi bi-star text-sm"></i></span>
                <h3 class="text-sm font-bold text-slate-900">Strengths</h3>
            </div>
            <?php if ($strengths): ?>
            <ul class="space-y-2">
                <?php foreach ($strengths as $s): ?>
                <li class="flex items-start gap-2 text-sm text-slate-600">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"><i class="bi bi-check text-xs"></i></span>
                    <?= htmlspecialchars($s) ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p class="text-sm text-slate-500">Continue exploring your strengths across all assessment areas.</p>
            <?php endif; ?>
        </div>

        <div class="rounded-xl border border-slate-100 bg-white/80 p-5">
            <div class="flex items-center gap-2 mb-3">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-600"><i class="bi bi-lightning text-sm"></i></span>
                <h3 class="text-sm font-bold text-slate-900">Areas for Growth</h3>
            </div>
            <?php if ($growthAreas): ?>
            <ul class="space-y-2">
                <?php foreach ($growthAreas as $g): ?>
                <li class="flex items-start gap-2 text-sm text-slate-600">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600"><i class="bi bi-arrow-up text-xs"></i></span>
                    <?= htmlspecialchars($g) ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p class="text-sm text-slate-500">Keep up the great work across all areas!</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="space-y-6">
        <?php foreach ($recommendations as $rec):
            $colorKey = $careerColors[$rec->careerName] ?? 'slate';
            $c = $colorMap[$colorKey];
            $pct = (int)$rec->matchPercent;
            $levelLabel = matchLevelLabel($pct);
            $badgeBg = matchBadgeClass($pct);
            $dotBg = matchDotClass($pct);
            $icon = $rec->careerIcon ?: 'fa-briefcase';
        ?>
        <div class="career-card group bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 hover:border-<?= $c['bg'] ?>-200 transition-all duration-300 flex flex-col overflow-hidden relative">
            <div class="absolute top-0 right-0 w-48 h-48 bg-<?= $c['bg'] ?>-50/50 rounded-full -mr-20 -mt-20 blur-3xl pointer-events-none group-hover:opacity-100 opacity-0 transition-opacity duration-300"></div>
            <div class="p-5 sm:p-7 flex flex-col flex-1 relative">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 shrink-0 rounded-xl bg-gradient-to-br <?= $c['grad'] ?> flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas <?= $icon ?> text-white text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="inline-block self-start px-2.5 py-1 rounded-full bg-<?= $c['bg'] ?>-100 text-<?= $c['text'] ?>-700 text-[10px] font-semibold uppercase tracking-wider mb-1.5"><?= htmlspecialchars($rec->educationRequired ?: 'Career') ?></span>
                            <h3 class="text-base font-bold text-slate-900"><?= htmlspecialchars($rec->careerName) ?></h3>
                        </div>
                    </div>
                    <div class="flex shrink-0 flex-col items-center gap-1 rounded-xl px-4 py-2.5 <?= $badgeBg ?> border">
                        <div class="flex items-center gap-1.5">
                            <span class="inline-flex w-2.5 h-2.5 rounded-full <?= $dotBg ?>"></span>
                            <span class="text-lg font-extrabold text-slate-900"><?= $pct ?>%</span>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider"><?= $levelLabel ?></span>
                    </div>
                </div>

                <p class="mt-3 text-sm text-slate-500 leading-relaxed"><?= htmlspecialchars($rec->description) ?></p>

                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Required Skills</p>
                        <p class="mt-1 text-sm font-medium text-slate-700"><?= htmlspecialchars($rec->requiredSkills ?: 'Not specified') ?></p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Education Required</p>
                        <p class="mt-1 text-sm font-medium text-slate-700"><?= htmlspecialchars($rec->educationRequired ?: 'Not specified') ?></p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Average Salary</p>
                        <p class="mt-1 text-sm font-medium text-slate-700"><?= htmlspecialchars($rec->averageSalary ?: 'N/A') ?></p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Growth Rate</p>
                        <p class="mt-1 text-sm font-medium text-slate-700"><?= htmlspecialchars($rec->growthRate ?: 'N/A') ?></p>
                    </div>
                </div>

                <div class="mt-4 rounded-xl border border-<?= $c['bg'] ?>-100 bg-<?= $c['bg'] ?>-50/70 p-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-<?= $c['text'] ?>-600">Why This Career Matches You</p>
                    <div class="mt-2 grid gap-2 sm:grid-cols-2">
                        <?php foreach ($rec->matchedDimensions as $dim): ?>
                        <div class="flex items-center gap-2 text-sm">
                            <?php if ($dim['matched']): ?>
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"><i class="fas fa-check text-[9px] font-bold"></i></span>
                            <span><span class="font-semibold text-slate-800"><?= htmlspecialchars($dim['dimension']) ?></span> <span class="text-slate-500">matched</span></span>
                            <?php else: ?>
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-200 text-slate-400"><i class="fas fa-times text-[9px]"></i></span>
                            <span class="text-slate-400"><span class="font-medium"><?= htmlspecialchars($dim['dimension']) ?></span> not matched</span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <section class="w-full min-w-0 rounded-2xl border border-slate-200 bg-white/80 p-12 text-center shadow-sm">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
            <i class="fas fa-briefcase text-2xl text-slate-400"></i>
        </div>
        <h2 class="text-lg font-bold text-slate-900">No career recommendations available yet</h2>
        <p class="mt-2 text-sm text-slate-500">Your assessment results are still being processed. Please check back later.</p>
        <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white no-underline transition-all duration-200 hover:bg-indigo-700">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to Assessments
        </a>
    </section>
    <?php endif; ?>
</div>

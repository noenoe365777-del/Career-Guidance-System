<?php
$totalAssessments = $totalAssessments ?? 4;
$completedAssessments = $completedAssessments ?? 0;
$percentage = $percentage ?? 0;
$statusMap = $statusMap ?? [];
$recommendation = $recommendation ?? null;
$allCompleted = $allCompleted ?? ($completedAssessments >= $totalAssessments);

$studentName = htmlspecialchars($user['full_name'] ?? $user['name'] ?? $user['username'] ?? 'Student');

$assessmentSlugs = ['personality', 'interest', 'aptitude', 'values'];

$faIcons = [
    'personality' => 'fa-brain',
    'interest'    => 'fa-heart',
    'aptitude'    => 'fa-chart-line',
    'values'      => 'fa-bullseye',
];

$assessmentLabels = [
    'personality' => 'Personality Assessment',
    'interest'    => 'Interest Assessment',
    'aptitude'    => 'Aptitude Assessment',
    'values'      => 'Career Values Assessment',
];

$questionCounts = [
    'personality' => 25,
    'interest'    => 25,
    'aptitude'    => 15,
    'values'      => 20,
];

$assessmentColors = [
    'personality' => ['bg' => 'bg-indigo-50', 'icon' => 'text-indigo-600', 'btn' => 'bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800'],
    'interest'    => ['bg' => 'bg-pink-50', 'icon' => 'text-pink-600', 'btn' => 'bg-gradient-to-r from-pink-600 to-pink-700 hover:from-pink-700 hover:to-pink-800'],
    'aptitude'    => ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-600', 'btn' => 'bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800'],
    'values'      => ['bg' => 'bg-amber-50', 'icon' => 'text-amber-600', 'btn' => 'bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800'],
];

$nextSlug = null;
foreach ($assessmentSlugs as $slug) {
    $s = $statusMap[$slug] ?? ['status' => 'Locked', 'completed_at' => null];
    if (strtolower($s['status']) !== 'completed') {
        $nextSlug = $slug;
        break;
    }
}

$careerIconMap = [
    'Software Engineer' => 'fa-code',
    'Data Analyst' => 'fa-chart-bar',
    'Graphic Designer' => 'fa-paintbrush',
    'Teacher' => 'fa-chalkboard-user',
    'Doctor' => 'fa-user-doctor',
    'Accountant' => 'fa-calculator',
    'Civil Engineer' => 'fa-helmet-safety',
    'Mechanical Engineer' => 'fa-gears',
    'Marketing Specialist' => 'fa-bullhorn',
    'Nurse' => 'fa-user-nurse',
    'Electrician' => 'fa-bolt',
    'Plumber' => 'fa-wrench',
    'Certified Nursing Assistant (CNA)' => 'fa-heart-pulse',
    'Retail Manager' => 'fa-store',
    'HVAC Technician' => 'fa-snowflake',
    'Administrative Assistant' => 'fa-file-lines',
    'Security Guard' => 'fa-shield-halved',
    'Chef / Cook' => 'fa-utensils',
];

$recommendationIcon = 'fa-trophy';
if ($recommendation && isset($careerIconMap[$recommendation['career_name']])) {
    $recommendationIcon = $careerIconMap[$recommendation['career_name']];
}

$dash = 408;
$offset = max(0, (int)round($dash - ($dash * ($percentage / 100))));

function dashboardStatusBadge(string $status): string
{
    return match (strtolower($status)) {
        'completed' => '<span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Completed</span>',
        'in_progress', 'in progress' => '<span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>In Progress</span>',
        'not_started', 'not started' => '<span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-600">Not Started</span>',
        default => '<span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-500"><i class="fas fa-lock text-[9px]"></i> Locked</span>',
    };
}

function dashboardFormatDate(?string $value): string
{
    if (empty($value)) return '—';
    $ts = strtotime($value);
    return $ts ? date('M j, Y', $ts) : '—';
}
?>
<div class="mx-auto w-full max-w-6xl overflow-x-hidden px-4 py-6 sm:px-6 sm:py-8 lg:px-8 space-y-6">

    <!-- Welcome Card -->
    <section class="relative overflow-hidden rounded-[20px] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="absolute -right-20 -top-20 h-48 w-48 rounded-full bg-gradient-to-br from-indigo-100/60 to-violet-100/60 blur-3xl"></div>
        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-sm">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Welcome back, <?= $studentName ?>!👋</h1>
                        <p class="mt-0.5 text-sm text-slate-500">Complete your assessments to unlock personalized career recommendations.</p>
                    </div>
                </div>
            </div>
            <?php if ($allCompleted): ?>
               
            <?php else: ?>
               
            <?php endif; ?>
        </div>
    </section>

    <!-- Quick Statistics -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-[20px] border border-slate-200/70 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Completed</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-500">
                    <i class="fas fa-check text-xs"></i>
                </div>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-900"><?= (int)$completedAssessments ?></p>
            <p class="text-xs text-slate-400">Assessments</p>
        </div>
        <div class="rounded-[20px] border border-slate-200/70 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Total</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                    <i class="fas fa-list-check text-xs"></i>
                </div>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-900"><?= (int)$totalAssessments ?></p>
            <p class="text-xs text-slate-400">Assessments</p>
        </div>
        <div class="rounded-[20px] border border-slate-200/70 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Education</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-50 text-cyan-500">
                    <i class="fas fa-graduation-cap text-xs"></i>
                </div>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-900"><?= htmlspecialchars($recommendation['education_required'] ?? '—') ?></p>
            <p class="text-xs text-slate-400">Level</p>
        </div>
        <div class="rounded-[20px] border border-slate-200/70 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Top Match</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-500">
                    <i class="fas fa-trophy text-xs"></i>
                </div>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-900"><?= $recommendation ? (int)$recommendation['match_score'] . '%' : '—' ?></p>
            <p class="text-xs text-slate-400">Score</p>
        </div>
    </div>
    
    <!-- Progress + Next Assessment -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[280px_1fr]">

        <!-- Circular Progress -->
        <section class="flex w-full min-w-0 flex-col items-center rounded-[20px] border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="flex w-full items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">Progress</h2>
                <i class="fas fa-chart-pie text-slate-400"></i>
            </div>
            <div class="relative mt-6 h-32 w-32">
                <svg class="h-32 w-32 -rotate-90" viewBox="0 0 144 144">
                    <circle cx="72" cy="72" r="58" fill="none" stroke="#f1f5f9" stroke-width="10"/>
                    <circle cx="72" cy="72" r="58" fill="none" stroke="#4f46e5" stroke-width="10" stroke-linecap="round" stroke-dasharray="<?= $dash ?>" stroke-dashoffset="<?= $offset ?>" class="transition-all duration-1000 ease-out"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-bold text-slate-900"><?= round($percentage) ?>%</span>
                    <span class="text-xs font-medium text-slate-400"><?= (int)$completedAssessments ?> / <?= (int)$totalAssessments ?></span>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-400"><?= $allCompleted ? 'All assessments complete!' : 'Keep going to unlock your career path.' ?></p>
        </section>

        <!-- Next Assessment or All Completed -->
        <section class="flex w-full min-w-0 flex-col rounded-[20px] border border-slate-200/70 bg-white p-6 shadow-sm">
            <?php if ($nextSlug !== null): ?>
                <?php
                $slug = $nextSlug;
                $s = $statusMap[$slug] ?? ['status' => 'Locked', 'completed_at' => null];
                $status = $s['status'];
                $c = $assessmentColors[$slug];
                $icon = $faIcons[$slug];
                $label = $assessmentLabels[$slug];
                $qCount = $questionCounts[$slug] ?? 0;
                $isInProgress = in_array(strtolower($status), ['in_progress', 'in progress']);
                $btnLabel = $isInProgress ? 'Continue' : 'Start';
                ?>
                <div class="flex w-full flex-col gap-4">
                    <h2 class="text-sm font-semibold text-slate-900">Next Assessment</h2>
                    <div class="flex flex-1 flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl <?= $c['bg'] ?>">
                                <i class="fas <?= $icon ?> text-xl <?= $c['icon'] ?>"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="truncate text-base font-semibold text-slate-900"><?= htmlspecialchars($label) ?></h3>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-xs text-slate-400"><?= $qCount ?> Questions</span>
                                    <span class="text-slate-300">·</span>
                                    <?= dashboardStatusBadge($status) ?>
                                </div>
                            </div>
                        </div>
                        <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="inline-flex shrink-0 items-center gap-2 self-start rounded-xl <?= $c['btn'] ?> px-5 py-2.5 text-sm font-semibold text-white shadow-sm no-underline transition-all duration-200">
                            <i class="fas fa-play text-xs"></i>
                            <?= $btnLabel ?>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex h-full flex-col items-center justify-center gap-3 py-4 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50">
                        <i class="fas fa-check-circle text-2xl text-emerald-500"></i>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">All assessments completed</h3>
                    <p class="text-sm text-slate-500">You've finished all four assessments. View your career recommendations now.</p>
                    <a href="<?= BASE_URL ?>/index.php?page=career-recommendation" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm no-underline transition-all duration-200 hover:from-indigo-700 hover:to-violet-700">
                        View Recommendations
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <!-- Top Career Recommendation -->
    <section class="rounded-[20px] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <?php if ($recommendation): ?>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-md">
                        <i class="fas <?= $recommendationIcon ?> text-xl"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-indigo-600">Top Recommendation</p>
                        <h2 class="truncate text-lg font-bold text-slate-900"><?= htmlspecialchars($recommendation['career_name']) ?></h2>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500"><?= htmlspecialchars(mb_substr($recommendation['description'], 0, 150)) ?><?= mb_strlen($recommendation['description']) > 150 ? '...' : '' ?></p>
                    </div>
                </div>
                <div class="flex shrink-0 flex-col items-center gap-3 sm:items-end">
                    <div class="flex flex-col items-center gap-1 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-2.5">
                        <span class="text-2xl font-extrabold text-emerald-600"><?= (int)$recommendation['match_score'] ?>%</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-500">Match</span>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=career-recommendation" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 no-underline transition-all duration-200 hover:border-slate-300 hover:bg-slate-50">
                        View Details
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        <?php elseif ($allCompleted): ?>
            <div class="flex flex-col items-center gap-4 py-4 text-center sm:flex-row sm:text-left">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-amber-100 bg-amber-50 text-amber-500">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                <div class="min-w-0">
                    <h2 class="text-base font-semibold text-slate-900">Complete All Assessments</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Finish all four assessments to unlock your personalized career recommendations.</p>
                </div>
                <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm no-underline transition-all duration-200 hover:bg-amber-700 sm:ml-auto">
                    Go to Assessments
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
                    <i class="fas fa-lock text-xl"></i>
                </div>
                <div class="min-w-0">
                    <h2 class="text-base font-semibold text-slate-900">Career Recommendation</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Complete all <?= (int)$totalAssessments ?> assessments to unlock your personalized career recommendation.</p>
                </div>
                <span class="ml-auto hidden shrink-0 items-center gap-2 rounded-xl border border-slate-100 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-300 sm:inline-flex">
                    <i class="fas fa-lock text-xs"></i>
                    Locked
                </span>
            </div>
        <?php endif; ?>
    </section>


    <!-- Recent Activity -->
    <section class="rounded-[20px] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-900">Recent Activity</h2>
            <i class="fas fa-clock-rotate-left text-slate-400"></i>
        </div>
        <div class="mt-4 divide-y divide-slate-100">
            <?php foreach ($assessmentSlugs as $slug):
                $s = $statusMap[$slug] ?? ['status' => 'Locked', 'completed_at' => null];
                $statusRaw = $s['status'];
                $completedAt = $s['completed_at'] ?? null;
                $statusLower = strtolower($statusRaw);
                $isComplete = $statusLower === 'completed';
                $statusDisplay = match ($statusLower) {
                    'completed' => 'Completed',
                    'in_progress', 'in progress' => 'In Progress',
                    'not_started', 'not started' => 'Not Started',
                    default => 'Locked',
                };
                $icon = $faIcons[$slug];
                $label = $assessmentLabels[$slug];
            ?>
            <div class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl <?= $isComplete ? 'bg-emerald-50 text-emerald-500' : 'bg-slate-50 text-slate-400' ?>">
                    <i class="fas <?= $icon ?> text-sm"></i>
                </div>
                <div class="flex min-w-0 flex-1 items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-slate-800"><?= htmlspecialchars($label) ?></p>
                        <p class="text-xs text-slate-400"><?= $statusDisplay ?></p>
                    </div>
                    <span class="shrink-0 text-xs text-slate-400"><?= $isComplete ? dashboardFormatDate($completedAt) : '—' ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    
</div>

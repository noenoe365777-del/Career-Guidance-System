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
<div class="mx-auto w-full max-w-5xl overflow-x-hidden px-4 py-5 sm:px-6 sm:py-8 lg:px-8">

    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-[26px] font-bold text-slate-900 leading-tight">Welcome back, <?= $studentName ?>! 👋</h1>
        <p class="text-sm text-slate-400 mt-1"><?= date('l, F j, Y') ?></p>
        <p class="text-base text-slate-500 mt-2">Continue your career journey.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 gap-4 mb-8 sm:grid-cols-4" x-data="statCounters()" x-init="initCounters()">
        <div class="rounded-xl border border-slate-200/70 bg-white p-4 shadow-sm stat-card hover-lift hover-shadow float-icon" style="animation-delay: 0ms;">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Completed</span>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-500 stat-icon">
                    <i class="fas fa-check text-[10px]"></i>
                </div>
            </div>
            <p class="mt-2 text-xl font-bold text-slate-900 counter" data-target="<?= (int)$completedAssessments ?>" data-suffix="">0</p>
            <p class="text-[11px] text-slate-400">Assessments</p>
        </div>
        <div class="rounded-xl border border-slate-200/70 bg-white p-4 shadow-sm stat-card hover-lift hover-shadow float-icon" style="animation-delay: 100ms;">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Total</span>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500 stat-icon">
                    <i class="fas fa-list-check text-[10px]"></i>
                </div>
            </div>
            <p class="mt-2 text-xl font-bold text-slate-900 counter" data-target="<?= (int)$totalAssessments ?>" data-suffix="">0</p>
            <p class="text-[11px] text-slate-400">Assessments</p>
        </div>
        <div class="rounded-xl border border-slate-200/70 bg-white p-4 shadow-sm stat-card hover-lift hover-shadow float-icon" style="animation-delay: 200ms;">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Education</span>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-cyan-50 text-cyan-500 stat-icon">
                    <i class="fas fa-graduation-cap text-[10px]"></i>
                </div>
            </div>
            <p class="mt-2 text-xl font-bold text-slate-900"><?= htmlspecialchars($recommendation['education_required'] ?? '—') ?></p>
            <p class="text-[11px] text-slate-400">Level</p>
        </div>
        <div class="rounded-xl border border-slate-200/70 bg-white p-4 shadow-sm stat-card hover-lift hover-shadow float-icon" style="animation-delay: 300ms;">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Best Match</span>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 text-amber-500 stat-icon">
                    <i class="fas fa-trophy text-[10px]"></i>
                </div>
            </div>
            <p class="mt-2 text-xl font-bold text-slate-900 counter" data-target="<?= $recommendation ? (int)$recommendation['match_score'] : 0 ?>" data-suffix="%">0%</p>
            <p class="text-[11px] text-slate-400">Score</p>
        </div>
    </div>

    <!-- Featured Career Recommendation -->
    <div class="rounded-xl border border-slate-200/70 bg-white p-6 shadow-sm mb-8">
        <?php if ($recommendation): ?>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="flex items-start gap-4 min-w-0">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-sm">
                    <i class="fas fa-star text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-amber-600">Your Best Career Match</p>
                    <h2 class="text-xl font-bold text-slate-900 mt-1"><?= htmlspecialchars($recommendation['career_name']) ?></h2>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="text-2xl font-extrabold text-emerald-600"><?= (int)$recommendation['match_score'] ?>%</span>
                        <span class="text-xs font-medium text-slate-400">Match</span>
                    </div>
                    <p class="mt-1.5 text-sm text-slate-500 leading-relaxed max-w-lg"><?= htmlspecialchars(mb_substr($recommendation['description'], 0, 120)) ?><?= mb_strlen($recommendation['description']) > 120 ? '...' : '' ?></p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=career-recommendation" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white no-underline transition-all duration-200 hover:from-indigo-700 hover:to-violet-700 shadow-sm self-start sm:self-center">
                View Career Maps
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <?php elseif ($allCompleted): ?>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-emerald-600">All Assessments Complete</p>
                    <h2 class="text-base font-semibold text-slate-900 mt-0.5">Your career recommendations are ready</h2>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=career-recommendation" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white no-underline transition-all duration-200 hover:from-indigo-700 hover:to-violet-700 shadow-sm self-start sm:self-center">
                View Career Maps
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <?php else: ?>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                    <i class="fas fa-lock text-lg"></i>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Career Match Locked</p>
                    <h2 class="text-base font-semibold text-slate-900 mt-0.5">Complete assessments to unlock your match</h2>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-slate-600 px-5 py-2.5 text-sm font-semibold text-white no-underline transition-all duration-200 hover:bg-slate-700 shadow-sm self-start sm:self-center">
                Take Assessments
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-4 mb-8 sm:grid-cols-4">
        <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="flex flex-col items-center gap-2 rounded-xl border border-slate-200/70 bg-white p-5 shadow-sm no-underline transition-all duration-200 hover:border-indigo-200 hover:shadow-md hover:-translate-y-0.5 group">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                <i class="fas fa-pencil"></i>
            </div>
            <div class="text-center">
                <p class="text-sm font-semibold text-slate-800">Assessments</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Continue or retake</p>
            </div>
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=career-recommendation" class="flex flex-col items-center gap-2 rounded-xl border border-slate-200/70 bg-white p-5 shadow-sm no-underline transition-all duration-200 hover:border-emerald-200 hover:shadow-md hover:-translate-y-0.5 group">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                <i class="fas fa-map"></i>
            </div>
            <div class="text-center">
                <p class="text-sm font-semibold text-slate-800">Career Maps</p>
                <p class="text-[11px] text-slate-400 mt-0.5">View recommendations</p>
            </div>
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=notifications" class="flex flex-col items-center gap-2 rounded-xl border border-slate-200/70 bg-white p-5 shadow-sm no-underline transition-all duration-200 hover:border-amber-200 hover:shadow-md hover:-translate-y-0.5 group">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-100 transition-colors">
                <i class="fas fa-bell"></i>
            </div>
            <div class="text-center">
                <p class="text-sm font-semibold text-slate-800">Notifications</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Check latest updates</p>
            </div>
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=profile" class="flex flex-col items-center gap-2 rounded-xl border border-slate-200/70 bg-white p-5 shadow-sm no-underline transition-all duration-200 hover:border-sky-200 hover:shadow-md hover:-translate-y-0.5 group">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600 group-hover:bg-sky-100 transition-colors">
                <i class="fas fa-user"></i>
            </div>
            <div class="text-center">
                <p class="text-sm font-semibold text-slate-800">Profile</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Manage account</p>
            </div>
        </a>
    </div>

    <!-- Recent Activity -->
    <section class="rounded-xl border border-slate-200/70 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-slate-900">Recent Activity</h2>
            <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 no-underline transition-colors">View All &rarr;</a>
        </div>
        <div class="divide-y divide-slate-100">
            <?php $displayed = 0; foreach ($assessmentSlugs as $slug):
                if ($displayed >= 3) break;
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
                $displayed++;
            ?>
            <div class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg <?= $isComplete ? 'bg-emerald-50 text-emerald-500' : 'bg-slate-50 text-slate-400' ?>">
                    <i class="fas <?= $icon ?> text-xs"></i>
                </div>
                <div class="flex min-w-0 flex-1 items-center justify-between gap-2">
                    <p class="truncate text-sm font-medium text-slate-800"><?= htmlspecialchars($label) ?></p>
                    <span class="shrink-0 text-xs <?= $isComplete ? 'text-emerald-600 font-medium' : 'text-slate-400' ?>"><?= $isComplete ? dashboardFormatDate($completedAt) : $statusDisplay ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

<style>
/* ========================================
   SUMMARY CARDS ANIMATIONS
   ======================================== */

/* Staggered entrance: fade up */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card {
    animation: fadeInUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    opacity: 0;
}

/* Icon float animation */
@keyframes floatIcon {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    25% { transform: translateY(-3px) rotate(1deg); }
    50% { transform: translateY(-5px) rotate(0deg); }
    75% { transform: translateY(-3px) rotate(-1deg); }
}

.float-icon .stat-icon {
    animation: floatIcon 5s ease-in-out infinite;
    transform-origin: center;
    transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1);
}

.float-icon:hover .stat-icon {
    transform: scale(1.12) rotate(4deg);
}

/* Hover lift + shadow + border glow */
.hover-lift {
    transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1), 
                box-shadow 0.25s cubic-bezier(0.22, 1, 0.36, 1),
                border-color 0.25s ease;
}
.hover-lift:hover {
    transform: translateY(-4px);
}

.hover-shadow:hover {
    box-shadow: 0 16px 32px -8px rgba(15, 23, 42, 0.12), 
                0 6px 16px -4px rgba(15, 23, 42, 0.08);
    border-color: rgba(79, 70, 229, 0.3);
}

/* Ripple on click */
@keyframes rippleEffect {
    from { transform: scale(0); opacity: 0.4; }
    to { transform: scale(3); opacity: 0; }
}

.hover-lift:active::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: radial-gradient(circle at center, rgba(79,70,229,0.15) 0%, transparent 70%);
    animation: rippleEffect 0.4s ease-out forwards;
    pointer-events: none;
    z-index: -1;
}

/* Pulse on counter complete */
@keyframes pulseOnce {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    50% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.pulse-on-complete {
    animation: pulseOnce 0.6s ease-out forwards;
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .stat-card,
    .float-icon .stat-icon {
        animation: none !important;
        opacity: 1;
        transform: none;
    }
    .hover-lift:hover,
    .hover-lift:active::after {
        transform: none;
        box-shadow: none;
        border-color: inherit;
    }
    .float-icon:hover .stat-icon {
        transform: none;
    }
}
</style>

<script>
// ========================================
// Summary Cards Counter Animation
// Vanilla JS, no dependencies
// ========================================
function statCounters() {
    return {
        observer: null,
        initCounters() {
            const counters = document.querySelectorAll('.counter[data-target]');
            const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            // If reduced motion, set values immediately
            if (prefersReduced) {
                counters.forEach(el => {
                    const target = parseFloat(el.dataset.target) || 0;
                    const suffix = el.dataset.suffix || '';
                    el.textContent = target + suffix;
                });
                return;
            }

            this.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.dataset.animated) {
                        entry.target.dataset.animated = 'true';
                        this.animateCounter(entry.target);
                    }
                });
            }, { threshold: 0.4, rootMargin: '0px 0px -20px 0px' });

            counters.forEach(counter => this.observer.observe(counter));
        },
        animateCounter(el) {
            const target = parseFloat(el.dataset.target) || 0;
            const suffix = el.dataset.suffix || '';
            const duration = 1000; // 1 second
            const start = performance.now();
            const isInteger = Number.isInteger(target);

            const step = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                // Easing: cubic-bezier(0.22, 1, 0.36, 1) approximation
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = eased * target;
                el.textContent = (isInteger ? Math.round(current) : current.toFixed(1)) + suffix;
                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    el.textContent = target + suffix;
                    // Trigger subtle pulse on card
                    const card = el.closest('.stat-card');
                    if (card) {
                        card.classList.add('pulse-on-complete');
                        setTimeout(() => card.classList.remove('pulse-on-complete'), 600);
                    }
                }
            };
            requestAnimationFrame(step);
        }
    };
}
</script>
</div>
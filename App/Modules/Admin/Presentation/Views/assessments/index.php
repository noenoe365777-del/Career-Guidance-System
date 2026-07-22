<?php
$assessments = $assessments ?? [];
$totalAssessments = $totalAssessments ?? 0;
$totalQuestions = $totalQuestions ?? 0;
$studentsCompleted = $studentsCompleted ?? 0;
$averageScore = $averageScore ?? 0;
$message = $message ?? null;

$pageTitle = 'Assessment Management';
$activeMenu = 'assessments';

function fmtDate($v): string {
    if (!$v) return '—';
    $t = strtotime((string)$v);
    return $t ? date('M j, Y', $t) : (string)$v;
}

ob_start();
if (file_exists(__DIR__ . '/../partials/summary_stat_card.php')) {
    include __DIR__ . '/../partials/summary_stat_card.php';
}

$assessmentCardDefs = [
    ['key' => 'total', 'label' => 'Total Assessments', 'count' => (int)$totalAssessments, 'icon' => 'bi-collection', 'bg' => '#eef2ff', 'color' => '#5B5FEF'],
    ['key' => 'questions', 'label' => 'Total Questions', 'count' => (int)$totalQuestions, 'icon' => 'bi-question-circle', 'bg' => '#fffbeb', 'color' => '#d97706'],
    ['key' => 'completed', 'label' => 'Students Completed', 'count' => (int)$studentsCompleted, 'icon' => 'bi-people', 'bg' => '#ecfdf5', 'color' => '#059669'],
    ['key' => 'avg_score', 'label' => 'Average Score', 'count' => (int)round((float)$averageScore), 'icon' => 'bi-graph-up', 'bg' => '#f3e8ff', 'color' => '#9333ea'],
];

$assessments = array_slice($assessments, 0, 4);

$iconData = [
    ['icon' => 'fa-solid fa-brain',    'color' => '#fff', 'bg' => 'linear-gradient(135deg, #3b82f6, #2563eb)'],
    ['icon' => 'fa-solid fa-heart',    'color' => '#fff', 'bg' => 'linear-gradient(135deg, #ec4899, #db2777)'],
    ['icon' => 'fa-solid fa-chart-line', 'color' => '#fff', 'bg' => 'linear-gradient(135deg, #22c55e, #16a34a)'],
    ['icon' => 'fa-solid fa-bullseye', 'color' => '#fff', 'bg' => 'linear-gradient(135deg, #f97316, #ea580c)'],
];
?>
<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUpCard { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes iconBounce { 0% { transform: scale(1); } 25% { transform: scale(1.25) rotate(-5deg); } 50% { transform: scale(0.9) rotate(3deg); } 75% { transform: scale(1.1) rotate(-2deg); } 100% { transform: scale(1) rotate(0deg); } }

    .card-in { animation: slideUpCard 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    .d1 { animation-delay: 0.05s; }
    .d2 { animation-delay: 0.10s; }
    .d3 { animation-delay: 0.15s; }
    .d4 { animation-delay: 0.20s; }
    .d5 { animation-delay: 0.25s; }
    .d6 { animation-delay: 0.30s; }

    .stat-card {
        border-radius: 16px; padding: 24px; background: #fff; border: 1px solid #e2e8f0; cursor: pointer;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out, background-color 0.3s ease-out;
        will-change: transform, box-shadow;
    }
    .stat-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 24px 48px -16px rgba(91,95,239,0.28);
        border-color: #5B5FEF; background: #fafaff;
    }
    .stat-card:hover .card-icon-bg { transform: scale(1.15) rotate(5deg); }
    .stat-card:hover .card-number { transform: scale(1.04); }
    .stat-card:active { transform: scale(0.97); }
    .stat-card.active { border-color: #5B5FEF; background: #f8f7ff; box-shadow: 0 8px 28px -8px rgba(91,95,239,0.22); }
    .stat-card.active .card-icon-bg { background: #5B5FEF !important; color: #fff !important; }
    .card-icon-bg { transition: transform 0.3s ease-out, background-color 0.3s ease-out, color 0.3s ease-out; }
    .card-number { transition: transform 0.3s ease-out; }
    .card-icon-bg.bounce { animation: iconBounce 0.5s cubic-bezier(0.22,1,0.36,1); }

    .assess-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
    }
    .assess-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(99,102,241,0.18); border-color: rgba(99,102,241,0.25); }

    .stat-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.25rem 0.7rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 500;
    }

    .btn-action {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.4rem 1rem; border-radius: 8px; font-size: 0.82rem;
        font-weight: 500; transition: all 0.15s ease; cursor: pointer;
        border: 1px solid #e2e8f0; background: #fff; color: #475569; text-decoration: none;
    }
    .btn-action:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .btn-action.view:hover { background: #eef2ff; border-color: #a5b4fc; color: #4f46e5; }
    .btn-action.edit:hover { background: #eff6ff; border-color: #93c5fd; color: #2563eb; }
</style>

<div class="max-w-[1440px] mx-auto px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Assessment Management</h1>
            <p class="mt-1 text-sm text-slate-500">Manage career assessments, track completions, and monitor student performance.</p>
        </div>
    </div>

    <!-- Messages -->
    <?php if ($message !== null): ?>
    <div class="mb-8">
        <?php if ($message === 'created'): ?>
            <div class="flex items-center gap-3 p-4 border border-emerald-100 bg-emerald-50/50 rounded-2xl text-emerald-800 text-sm font-medium"><i class="bi bi-check-circle-fill text-base text-emerald-500"></i><div>Assessment created successfully.</div></div>
        <?php elseif ($message === 'updated'): ?>
            <div class="flex items-center gap-3 p-4 border border-blue-100 bg-blue-50/50 rounded-2xl text-blue-800 text-sm font-medium"><i class="bi bi-info-circle-fill text-base text-blue-500"></i><div>Assessment updated successfully.</div></div>
        <?php elseif ($message === 'deleted'): ?>
            <div class="flex items-center gap-3 p-4 border border-amber-100 bg-amber-50/50 rounded-2xl text-amber-800 text-sm font-medium"><i class="bi bi-exclamation-triangle-fill text-base text-amber-500"></i><div>Assessment deleted successfully.</div></div>
        <?php elseif ($message === 'not_found'): ?>
            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium"><i class="bi bi-x-circle-fill text-base text-rose-500"></i><div>Assessment not found.</div></div>
        <?php elseif ($message === 'error'): ?>
            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium"><i class="bi bi-x-circle-fill text-base text-rose-500"></i><div>An error occurred. Please try again.</div></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php foreach ($assessmentCardDefs as $i => $cd):
            $delayClass = 'd' . ($i + 1);
            $counterId = 'assessCount' . ucfirst($cd['key']);
            renderAdminSummaryCard([
                'title' => $cd['label'],
                'value' => '0',
                'valueNumber' => (int)($cd['count'] ?? 0),
                'counterId' => $counterId,
                'icon' => $cd['icon'],
                'iconBg' => $cd['bg'],
                'iconColor' => $cd['color'],
                'delayClass' => $delayClass,
                'filter' => $cd['key'],
                'active' => false,
                'extraClass' => '',
            ]);
        endforeach; ?>
    </div>

    <!-- Assessment Cards (2x2 grid) -->
    <?php if ($assessments === []): ?>
    <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-16 text-center shadow-sm">
        <div class="mx-auto h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center">
            <i class="bi bi-collection text-2xl text-slate-300"></i>
        </div>
        <h3 class="mt-4 text-lg font-semibold text-slate-800">No assessments found</h3>
        <p class="mt-2 text-sm text-slate-500">Assessment data is not available.</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <?php foreach ($assessments as $idx => $a):
            $id = (int)($a['assessment_id'] ?? 0);
            $title = htmlspecialchars((string)($a['title'] ?? ''), ENT_QUOTES, 'UTF-8');
            $desc = htmlspecialchars((string)($a['description'] ?? ''), ENT_QUOTES, 'UTF-8');
            $qCount = (int)($a['total_questions'] ?? 0);
            $sCompleted = (int)($a['students_completed'] ?? 0);
            $avgScore = (float)($a['avg_score'] ?? 0);
            $created = $a['created_at'] ?? null;
            $status = strtolower((string)($a['status'] ?? 'active'));
            $isActive = $status === 'active';
            $ic = $iconData[$idx];
        ?>
        <div class="assess-card card-in d<?= 5 + $idx ?> flex flex-col">
            <!-- Icon + Title + Status -->
            <div class="px-6 pt-6 pb-4 flex items-start gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl shadow-md" style="background:<?= $ic['bg'] ?>;color:<?= $ic['color'] ?>;">
                    <i class="<?= $ic['icon'] ?> text-white text-xl"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-lg font-semibold text-slate-900" style="margin:0 0 2px 0;"><?= $title ?></h3>
                    <span class="stat-chip inline-flex <?= $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>">
                        <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:<?= $isActive ? '#10b981' : '#94a3b8' ?>;"></span>
                        <?= $isActive ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
            </div>

            <!-- Description -->
            <div class="px-6 pb-4">
                <p class="text-sm text-slate-500 leading-relaxed" style="margin:0;"><?= $desc ?: 'No description available.' ?></p>
            </div>

            <!-- Stats -->
            <div class="mx-6 mb-4 flex items-stretch rounded-xl bg-slate-50/80 overflow-hidden">
                <div class="flex-1 flex flex-col items-center justify-center py-3 px-2 border-r border-slate-100">
                    <span class="text-xl font-bold text-slate-900" style="margin:0;"><?= $qCount ?></span>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400" style="margin:1px 0 0 0;">Questions</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-center py-3 px-2 border-r border-slate-100">
                    <span class="text-xl font-bold text-slate-900" style="margin:0;"><?= $sCompleted ?></span>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400" style="margin:1px 0 0 0;">Completed</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-center py-3 px-2">
                    <span class="text-xl font-bold text-slate-900" style="margin:0;"><?= $avgScore > 0 ? number_format($avgScore, 1) . '%' : '—' ?></span>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400" style="margin:1px 0 0 0;">Avg Score</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-auto px-6 pb-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                <span class="text-xs text-slate-400">Created <?= htmlspecialchars(fmtDate($created)) ?></span>
                <div class="flex items-center gap-2">
                    <a href="<?= BASE_URL ?>/index.php?page=admin-assessments-view&id=<?= $id ?>" class="btn-action view"><i class="bi bi-eye"></i> View</a>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-assessments-edit&id=<?= $id ?>" class="btn-action edit"><i class="bi bi-pencil"></i> Edit</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<script>
(function() {
    function animateCounter(el, target, done) {
        if (!el) return;
        var current = 0;
        var steps = 40;
        var inc = Math.max(1, Math.ceil(target / steps));
        var timer = setInterval(function() {
            current += inc;
            if (current >= target) {
                current = target;
                clearInterval(timer);
                if (done) done();
            }
            el.textContent = current.toLocaleString();
        }, 25);
    }
    setTimeout(function() {
        document.querySelectorAll('.stat-card[data-value]').forEach(function(card) {
            var el = card.querySelector('.card-number');
            var target = parseInt(card.getAttribute('data-value') || '0', 10);
            if (!el) return;
            animateCounter(el, target, function() {
                var iconBg = card.querySelector('.card-icon-bg');
                if (iconBg) iconBg.classList.add('bounce');
            });
        });
    }, 300);
})();
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

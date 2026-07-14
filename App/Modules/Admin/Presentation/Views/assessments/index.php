<?php
$assessments = $assessments ?? [];
$recentCompleted = $recentCompleted ?? [];
$search = $search ?? '';
$statusFilter = $statusFilter ?? '';
$sort = $sort ?? '';
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
?>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes slideUpCard { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes iconBounce { 0% { transform: scale(1); } 25% { transform: scale(1.25) rotate(-5deg); } 50% { transform: scale(0.9) rotate(3deg); } 75% { transform: scale(1.1) rotate(-2deg); } 100% { transform: scale(1) rotate(0deg); } }
    @keyframes slideLeft { from { opacity: 0; transform: translateX(-16px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes slideRight { from { opacity: 0; transform: translateX(16px); } to { opacity: 1; transform: translateX(0); } }

    .page-in { animation: fadeIn 0.5s ease-out both; }
    .card-in { animation: slideUpCard 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    .anim-in { animation: fadeIn 0.4s ease-out both; }
    .anim-slide-left { animation: slideLeft 0.4s ease-out both; }
    .anim-slide-right { animation: slideRight 0.4s ease-out both; }

    .d1 { animation-delay: 0.05s; }
    .d2 { animation-delay: 0.10s; }
    .d3 { animation-delay: 0.15s; }
    .d4 { animation-delay: 0.20s; }
    .d5 { animation-delay: 0.25s; }
    .d6 { animation-delay: 0.30s; }
    .d7 { animation-delay: 0.35s; }
    .d8 { animation-delay: 0.40s; }

    .stat-card {
        border-radius: 16px;
        padding: 24px;
        background: #fff;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out, background-color 0.3s ease-out, opacity 0.3s ease-out;
        will-change: transform, box-shadow, opacity;
    }
    .stat-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 24px 48px -16px rgba(91,95,239,0.28);
        border-color: #5B5FEF;
        background: #fafaff;
    }
    .stat-card:hover .card-icon-bg { transform: scale(1.15) rotate(5deg); }
    .stat-card:hover .card-number { transform: scale(1.04); }
    .stat-card:active { transform: scale(0.97); }
    .stat-card.active {
        border-color: #5B5FEF;
        background: #f8f7ff;
        box-shadow: 0 8px 28px -8px rgba(91,95,239,0.22);
    }
    .stat-card.active .card-icon-bg { background: #5B5FEF !important; color: #fff !important; }
    .card-icon-bg { transition: transform 0.3s ease-out, background-color 0.3s ease-out, color 0.3s ease-out; }
    .card-number { transition: transform 0.3s ease-out; }
    .card-icon-bg.bounce { animation: iconBounce 0.5s cubic-bezier(0.22,1,0.36,1); }

    .assessment-card {
        transition: all 0.25s ease;
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .assessment-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px -10px rgba(0,0,0,0.08);
    }

    .action-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.2rem;
        height: 2.2rem;
        border-radius: 0.6rem;
        color: #94a3b8;
        transition: all 0.15s ease;
        text-decoration: none;
        background: transparent;
        border: none;
        cursor: pointer;
    }
    .action-icon:hover { background: #f1f5f9; color: #1e293b; }
    .action-icon.view:hover { background: #eef2ff; color: #6366f1; }
    .action-icon.edit:hover { background: #eff6ff; color: #3b82f6; }
    .action-icon.questions:hover { background: #f5f3ff; color: #8b5cf6; }
    .action-icon.danger:hover { background: #fef2f2; color: #ef4444; }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.2rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 500;
        border: 1px solid transparent;
    }
    .badge-active { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
    .badge-inactive { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
    .badge-dot { display: inline-block; width: 0.4rem; height: 0.4rem; border-radius: 50%; }
    .badge-dot.active { background: #10b981; }
    .badge-dot.inactive { background: #94a3b8; }

    .input-field {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        border-radius: 0.75rem;
        padding: 0.6rem 1rem;
        font-size: 0.875rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        width: 100%;
        outline: none;
    }
    .input-field:focus { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
    .input-field::placeholder { color: #94a3b8; }

    .btn-apply {
        background: #f1f5f9; color: #1e293b; border: 1px solid #e2e8f0;
        border-radius: 0.75rem; padding: 0.6rem 1.25rem; font-size: 0.875rem;
        font-weight: 500; transition: all 0.15s ease; cursor: pointer;
    }
    .btn-apply:hover { background: #e2e8f0; border-color: #cbd5e1; }
    .btn-apply:active { transform: scale(0.97); }

    .btn-reset {
        background: transparent; color: #64748b; border: 1px solid #e2e8f0;
        border-radius: 0.75rem; padding: 0.6rem 1.25rem; font-size: 0.875rem;
        transition: all 0.15s ease; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.4rem;
    }
    .btn-reset:hover { background: #f8fafc; border-color: #cbd5e1; }

    .scrollbar-thin::-webkit-scrollbar { height: 4px; width: 4px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }
</style>

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8 space-y-8">

    <!-- Header -->
    <div class="anim-up d1">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 flex items-center gap-2.5">
                    <i class="bi bi-collection text-indigo-400"></i>
                    Assessment Management
                </h1>
                <p class="mt-1 text-sm text-slate-500">Manage career assessments, track completions, and monitor student performance.</p>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <?php if ($message !== null): ?>
    <div class="anim-up d2">
        <?php if ($message === 'created'): ?>
            <div class="flex items-center gap-3 p-4 border border-emerald-100 bg-emerald-50/50 rounded-2xl text-emerald-800 text-sm font-medium"><i class="bi bi-check-circle-fill text-base text-emerald-500"></i><div>Assessment created successfully.</div></div>
        <?php elseif ($message === 'updated'): ?>
            <div class="flex items-center gap-3 p-4 border border-blue-100 bg-blue-50/50 rounded-2xl text-blue-800 text-sm font-medium"><i class="bi bi-info-circle-fill text-base text-blue-500"></i><div>Assessment updated successfully.</div></div>
        <?php elseif ($message === 'deleted'): ?>
            <div class="flex items-center gap-3 p-4 border border-amber-100 bg-amber-50/50 rounded-2xl text-amber-800 text-sm font-medium"><i class="bi bi-exclamation-triangle-fill text-base text-amber-500"></i><div>Assessment deleted successfully.</div></div>
        <?php elseif ($message === 'not_found'): ?>
            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium"><i class="bi bi-x-circle-fill text-base text-rose-500"></i><div>Assessment not found.</div></div>
        <?php elseif ($message === 'duplicated'): ?>
            <div class="flex items-center gap-3 p-4 border border-blue-100 bg-blue-50/50 rounded-2xl text-blue-800 text-sm font-medium"><i class="bi bi-files text-base text-blue-500"></i><div>Assessment duplicated successfully.</div></div>
        <?php elseif ($message === 'error'): ?>
            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium"><i class="bi bi-x-circle-fill text-base text-rose-500"></i><div>An error occurred. Please try again.</div></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4" style="gap: 24px;">
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

    <!-- Filters -->
    <div class="anim-up d6">
        <form method="get" class="flex flex-col gap-4 lg:flex-row lg:items-end">
            <input type="hidden" name="page" value="admin-assessments">

            <div class="flex-1 min-w-0">
                <label class="mb-1.5 block text-xs font-medium text-slate-400">Search</label>
                <div class="relative">
                    <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-300"></i>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                           placeholder="Search by title..."
                           class="input-field pl-9">
                </div>
            </div>

            <div class="w-full sm:w-44">
                <label class="mb-1.5 block text-xs font-medium text-slate-400">Status</label>
                <select name="status" class="input-field">
                    <option value="">All</option>
                    <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="w-full sm:w-40">
                <label class="mb-1.5 block text-xs font-medium text-slate-400">Sort</label>
                <select name="sort" class="input-field">
                    <option value="newest" <?= $sort === 'newest' || $sort === '' ? 'selected' : '' ?>>Newest</option>
                    <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest</option>
                </select>
            </div>

            <div class="flex gap-2 items-center">
                <button type="submit" class="btn-apply"><i class="bi bi-funnel mr-1.5"></i> Apply</button>
                <?php if ($search !== '' || $statusFilter !== ''): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-assessments" class="btn-reset"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Assessment Cards -->
    <?php if ($assessments === []): ?>
    <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-16 text-center shadow-sm anim-up d7">
        <div class="mx-auto h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center">
            <i class="bi bi-collection text-2xl text-slate-300"></i>
        </div>
        <h3 class="mt-4 text-lg font-semibold text-slate-800">No assessments found</h3>
        <p class="mt-2 text-sm text-slate-500">Try adjusting your search or filter criteria.</p>
    </div>
    <?php else: ?>
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-2">
        <?php foreach ($assessments as $i => $a): ?>
        <?php
            $id = (int)($a['assessment_id'] ?? 0);
            $title = htmlspecialchars((string)($a['title'] ?? ''), ENT_QUOTES, 'UTF-8');
            $desc = htmlspecialchars((string)($a['description'] ?? ''), ENT_QUOTES, 'UTF-8');
            $qCount = (int)($a['total_questions'] ?? 0);
            $sCompleted = (int)($a['students_completed'] ?? 0);
            $avgScore = (float)($a['avg_score'] ?? 0);
            $created = $a['created_at'] ?? null;
            $status = strtolower((string)($a['status'] ?? 'active'));
            $isActive = $status === 'active';
            $icons = ['bi bi-person-badge', 'bi bi-activity', 'bi bi-cpu', 'bi bi-heart'];
            $iconColors = ['from-indigo-500 to-violet-500', 'from-emerald-500 to-teal-500', 'from-sky-500 to-blue-600', 'from-amber-500 to-orange-500'];
            $bgIdx = ($id - 1) % count($iconColors);
        ?>
        <div class="assessment-card p-5 anim-up d<?= 7 + min($i, 2) ?>" style="animation-delay: <?= min(0.3, 0.04 * $i) ?>s;">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br <?= $iconColors[$bgIdx] ?> text-white shadow-md">
                        <i class="<?= $icons[$bgIdx] ?> text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900"><?= $title ?></h3>
                        <span class="badge <?= $isActive ? 'badge-active' : 'badge-inactive' ?> mt-1">
                            <span class="badge-dot <?= $isActive ? 'active' : 'inactive' ?>"></span>
                            <?= $isActive ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                </div>
            </div>

            <p class="mt-3 text-sm leading-6 text-slate-500 line-clamp-2"><?= $desc ?: 'No description available.' ?></p>

            <div class="mt-4 grid grid-cols-3 gap-3">
                <div class="rounded-xl bg-indigo-50/70 p-3 text-center">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-indigo-500">Questions</div>
                    <div class="mt-1 text-lg font-bold text-slate-900"><?= $qCount ?></div>
                </div>
                <div class="rounded-xl bg-emerald-50/70 p-3 text-center">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-emerald-500">Completed</div>
                    <div class="mt-1 text-lg font-bold text-slate-900"><?= $sCompleted ?></div>
                </div>
                <div class="rounded-xl bg-violet-50/70 p-3 text-center">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-violet-500">Avg Score</div>
                    <div class="mt-1 text-lg font-bold text-slate-900"><?= $avgScore > 0 ? number_format($avgScore, 1) . '%' : '—' ?></div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                <span class="text-xs text-slate-400">Created <?= htmlspecialchars(fmtDate($created)) ?></span>
                <div class="flex items-center gap-1">
                    <a href="<?= BASE_URL ?>/index.php?page=admin-assessments-view&id=<?= $id ?>" title="View" class="action-icon view"><i class="bi bi-eye"></i></a>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-questions&assessment_id=<?= $id ?>" title="Questions" class="action-icon questions"><i class="bi bi-list-check"></i></a>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-assessments-edit&id=<?= $id ?>" title="Edit" class="action-icon edit"><i class="bi bi-pencil"></i></a>
                    <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-assessments-toggle-status" class="m-0 inline-flex" title="<?= $isActive ? 'Deactivate' : 'Activate' ?>">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="action-icon danger"><i class="bi <?= $isActive ? 'bi-pause-circle' : 'bi-play-circle' ?>"></i></button>
                    </form>
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

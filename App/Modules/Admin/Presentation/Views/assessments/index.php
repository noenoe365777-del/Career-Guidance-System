<?php
$assessments = $assessments ?? [];
$recentCompleted = $recentCompleted ?? [];
$search = $search ?? '';
$statusFilter = $statusFilter ?? '';
$sort = $sort ?? '';
$totalAssessments = $totalAssessments ?? 0;
$activeAssessments = $activeAssessments ?? 0;
$totalQuestions = $totalQuestions ?? 0;
$studentsCompleted = $studentsCompleted ?? 0;
$message = $message ?? null;

$pageTitle = 'Assessment Management';
$activeMenu = 'assessments';

function fmtDate($v): string {
    if (!$v) return '—';
    $t = strtotime((string)$v);
    return $t ? date('M j, Y', $t) : (string)$v;
}
function fmtDateTime($v): string {
    if (!$v) return '—';
    $t = strtotime((string)$v);
    return $t ? date('M j, Y g:i A', $t) : (string)$v;
}

ob_start();
?>

<style>
    /* === Subtle, elegant animations === */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideLeft {
        from { opacity: 0; transform: translateX(-12px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideRight {
        from { opacity: 0; transform: translateX(12px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .anim-up { animation: fadeUp 0.5s ease-out both; }
    .anim-in { animation: fadeIn 0.4s ease-out both; }
    .anim-slide-left { animation: slideLeft 0.4s ease-out both; }
    .anim-slide-right { animation: slideRight 0.4s ease-out both; }

    .d1 { animation-delay: 0.04s; }
    .d2 { animation-delay: 0.08s; }
    .d3 { animation-delay: 0.12s; }
    .d4 { animation-delay: 0.16s; }
    .d5 { animation-delay: 0.20s; }
    .d6 { animation-delay: 0.24s; }
    .d7 { animation-delay: 0.28s; }

    /* === Card styling === */
    .card {
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 1px 2px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }
    .card:hover {
        box-shadow: 0 8px 25px -8px rgba(0,0,0,0.06);
    }

    /* === Stat cards === */
    .stat-card {
        transition: all 0.2s ease;
        border: 1px solid #f1f5f9;
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -6px rgba(0,0,0,0.04);
    }

    /* === Table rows === */
    .table-row {
        transition: background 0.1s ease;
    }
    .table-row:hover {
        background: #f8fafc;
    }

    .table-wrap::-webkit-scrollbar { height: 4px; }
    .table-wrap::-webkit-scrollbar-track { background: transparent; }
    .table-wrap::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }
    .table-wrap::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

    /* === Inputs & selects === */
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
    .input-field:focus {
        border-color: #818cf8;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .input-field::placeholder {
        color: #94a3b8;
    }

    /* === Buttons (now minimal) === */
    .btn-apply {
        background: #f1f5f9;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.6rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .btn-apply:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
    }
    .btn-apply:active {
        transform: scale(0.97);
    }

    .btn-reset {
        background: transparent;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.6rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 400;
        transition: all 0.15s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .btn-reset:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* === Action icons === */
    .action-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        color: #94a3b8;
        transition: all 0.15s ease;
        text-decoration: none;
        background: transparent;
        border: none;
        cursor: pointer;
    }
    .action-icon:hover {
        background: #f1f5f9;
        color: #1e293b;
    }
    .action-icon.danger:hover {
        background: #fef2f2;
        color: #ef4444;
    }

    /* === Badges === */
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
    .badge-active {
        background: #ecfdf5;
        color: #065f46;
        border-color: #a7f3d0;
    }
    .badge-inactive {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }
    .badge-dot {
        display: inline-block;
        width: 0.4rem;
        height: 0.4rem;
        border-radius: 50%;
    }
    .badge-dot.active { background: #10b981; }
    .badge-dot.inactive { background: #94a3b8; }

    /* === Misc === */
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0f172a;
        letter-spacing: -0.01em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-sub {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 0.1rem;
    }

    /* === Scrollbar for page === */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f8fafc; }
    ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

    /* Selection */
    ::selection { background: #e0e7ff; color: #1e293b; }
</style>

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8 space-y-8">

    <!-- Header – plain, no background -->
    <div class="anim-up d1">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 flex items-center gap-2.5">
            <i class="bi bi-collection text-indigo-400"></i>
            Assessment Management
        </h1>
        <p class="mt-1 text-sm text-slate-500">Manage career assessments, track completions, and monitor student performance.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="stat-card anim-up d1">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Assessments</span>
                    <p class="mt-2 text-2xl font-bold text-slate-900"><?= number_format((int)$totalAssessments) ?></p>
                    <p class="mt-0.5 text-xs text-slate-400"><?= (int)$activeAssessments ?> active</p>
                </div>
                <div class="h-11 w-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-400">
                    <i class="bi bi-collection text-lg"></i>
                </div>
            </div>
        </div>
        <div class="stat-card anim-up d2">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Questions</span>
                    <p class="mt-2 text-2xl font-bold text-slate-900"><?= number_format((int)$totalQuestions) ?></p>
                    <p class="mt-0.5 text-xs text-slate-400">Across all assessments</p>
                </div>
                <div class="h-11 w-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-400">
                    <i class="bi bi-question-circle text-lg"></i>
                </div>
            </div>
        </div>
        <div class="stat-card anim-up d3">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Students Completed</span>
                    <p class="mt-2 text-2xl font-bold text-slate-900"><?= number_format((int)$studentsCompleted) ?></p>
                    <p class="mt-0.5 text-xs text-slate-400">At least one assessment</p>
                </div>
                <div class="h-11 w-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-400">
                    <i class="bi bi-people text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar – clean, no gradient, minimal buttons -->
    <div class="card p-5 anim-up d4">
        <form method="get" class="flex flex-col gap-4 lg:flex-row lg:items-end">
            <input type="hidden" name="page" value="admin-assessments">

            <div class="flex-1 min-w-0">
                <label class="mb-1.5 block text-xs font-medium text-slate-400">Search</label>
                <div class="relative">
                    <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-300"></i>
                    <input type="text" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
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
                <button type="submit" class="btn-apply">
                    <i class="bi bi-funnel mr-1.5"></i> Apply
                </button>
                <?php if ($search !== '' || $statusFilter !== ''): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-assessments" class="btn-reset">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Assessments Table -->
    <div class="card overflow-hidden anim-up d5">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div>
                <h2 class="section-title">
                    <i class="bi bi-table text-indigo-400"></i>
                    Assessments
                </h2>
                <p class="section-sub"><?= count($assessments) ?> assessment<?= count($assessments) === 1 ? '' : 's' ?> total</p>
            </div>
        </div>

        <?php if ($assessments === []): ?>
        <div class="py-16 text-center">
            <div class="mx-auto h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center">
                <i class="bi bi-collection text-2xl text-slate-300"></i>
            </div>
            <h3 class="mt-4 text-lg font-medium text-slate-700">No assessments found</h3>
            <p class="mt-1 text-sm text-slate-400">Try adjusting your search or filter criteria.</p>
        </div>
        <?php else: ?>
        <div class="table-wrap overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 hidden md:table-cell">Description</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Questions</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Completed</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400 hidden lg:table-cell">Avg Score</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 hidden lg:table-cell">Created</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assessments as $a): ?>
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
                    ?>
                    <tr class="table-row border-b border-slate-100/70">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-400 shrink-0">
                                    <i class="bi bi-journal-check text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-slate-700"><?= $title ?></span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-slate-500 max-w-[180px] truncate hidden md:table-cell"><?= $desc ?: '—' ?></td>
                        <td class="px-5 py-3.5 text-sm text-center text-slate-600"><?= $qCount ?></td>
                        <td class="px-5 py-3.5 text-sm text-center text-slate-600"><?= $sCompleted ?></td>
                        <td class="px-5 py-3.5 text-sm text-center text-slate-600 hidden lg:table-cell"><?= $avgScore > 0 ? number_format($avgScore, 2) : '—' ?></td>
                        <td class="px-5 py-3.5 text-sm text-slate-500 hidden lg:table-cell"><?= htmlspecialchars(fmtDate($created), ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="badge <?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
                                <span class="badge-dot <?= $isActive ? 'active' : 'inactive' ?>"></span>
                                <?= $isActive ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1">
                                <a href="<?= BASE_URL ?>/index.php?page=admin-assessments-view&id=<?= $id ?>"
                                   class="action-icon" title="View"><i class="bi bi-eye"></i></a>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-assessments-edit&id=<?= $id ?>"
                                   class="action-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-assessments-toggle-status" class="m-0 inline-flex">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button type="submit"
                                        class="action-icon danger"
                                        title="<?= $isActive ? 'Deactivate' : 'Activate' ?>">
                                        <i class="bi <?= $isActive ? 'bi-pause-circle' : 'bi-play-circle' ?>"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bottom Grid: Recent Activity + Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Activity -->
        <div class="card overflow-hidden anim-slide-left d6">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h2 class="section-title">
                        <i class="bi bi-clock-history text-indigo-400"></i>
                        Recent Activity
                    </h2>
                    <p class="section-sub">Latest completed assessments</p>
                </div>
                <span class="text-xs text-slate-400 bg-slate-50 px-2.5 py-1 rounded-full border border-slate-200 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Live
                </span>
            </div>
            <?php if ($recentCompleted === []): ?>
            <div class="py-12 text-center">
                <div class="mx-auto h-12 w-12 rounded-xl bg-slate-50 flex items-center justify-center">
                    <i class="bi bi-clock-history text-xl text-slate-300"></i>
                </div>
                <p class="mt-3 text-sm text-slate-400">No completed assessments yet.</p>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto max-h-[340px] overflow-y-auto">
                <table class="min-w-full border-collapse">
                    <thead class="sticky top-0 bg-white border-b border-slate-100 z-10">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Student</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Assessment</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Score</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">%</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 hidden sm:table-cell">Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentCompleted as $rc): ?>
                        <?php
                            $studentName = htmlspecialchars((string)($rc['student_name'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $assessName = htmlspecialchars((string)($rc['assessment_title'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $score = (float)($rc['total_score'] ?? 0);
                            $pct = (float)($rc['percentage'] ?? 0);
                            $completedAt = $rc['completed_at'] ?? null;
                        ?>
                        <tr class="table-row border-b border-slate-100/70">
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-7 w-7 rounded-full bg-indigo-50 flex items-center justify-center text-xs font-semibold text-indigo-500 shrink-0">
                                        <?= strtoupper(substr($studentName, 0, 1)) ?>
                                    </div>
                                    <span class="text-sm font-medium text-slate-700"><?= $studentName ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-sm text-slate-600"><?= $assessName ?></td>
                            <td class="px-4 py-2.5 text-sm text-center text-slate-600"><?= number_format($score, 2) ?></td>
                            <td class="px-4 py-2.5 text-sm text-center">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium <?= $pct >= 70 ? 'bg-emerald-50 text-emerald-700' : ($pct >= 40 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') ?>">
                                    <?= number_format($pct, 1) ?>%
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-400 hidden sm:table-cell"><?= htmlspecialchars(fmtDateTime($completedAt), ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <!-- Statistics -->
        <div class="card overflow-hidden anim-slide-right d7">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h2 class="section-title">
                        <i class="bi bi-bar-chart text-indigo-400"></i>
                        Assessment Statistics
                    </h2>
                    <p class="section-sub">Per-assessment performance data</p>
                </div>
            </div>
            <?php if ($assessments === []): ?>
            <div class="py-12 text-center">
                <div class="mx-auto h-12 w-12 rounded-xl bg-slate-50 flex items-center justify-center">
                    <i class="bi bi-bar-chart text-xl text-slate-300"></i>
                </div>
                <p class="mt-3 text-sm text-slate-400">No assessment data available.</p>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto max-h-[340px] overflow-y-auto">
                <table class="min-w-full border-collapse">
                    <thead class="sticky top-0 bg-white border-b border-slate-100 z-10">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Assessment</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Questions</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Completed</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Avg Score</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Avg %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assessments as $a): ?>
                        <?php
                            $title = htmlspecialchars((string)($a['title'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $qCount = (int)($a['total_questions'] ?? 0);
                            $sCompleted = (int)($a['students_completed'] ?? 0);
                            $avgScore = (float)($a['avg_score'] ?? 0);
                            $maxScore = (float)($a['max_score'] ?? 0);
                            $avgPct = $maxScore > 0 ? round(($avgScore / $maxScore) * 100, 1) : 0;
                            // Dot color
                            $t = strtolower($title);
                            if (str_contains($t, 'personality'))   $dot = 'bg-indigo-400';
                            elseif (str_contains($t, 'interest'))  $dot = 'bg-pink-400';
                            elseif (str_contains($t, 'aptitude'))  $dot = 'bg-emerald-400';
                            elseif (str_contains($t, 'value'))     $dot = 'bg-amber-400';
                            else                                   $dot = 'bg-slate-300';
                        ?>
                        <tr class="table-row border-b border-slate-100/70">
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2.5">
                                    <span class="inline-block h-2 w-2 rounded-full <?= $dot ?>"></span>
                                    <span class="text-sm font-medium text-slate-700"><?= $title ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-sm text-center text-slate-600"><?= $qCount ?></td>
                            <td class="px-4 py-2.5 text-sm text-center text-slate-600"><?= $sCompleted ?></td>
                            <td class="px-4 py-2.5 text-sm text-center text-slate-600"><?= $avgScore > 0 ? number_format($avgScore, 2) : '—' ?></td>
                            <td class="px-4 py-2.5 text-sm text-center">
                                <?php if ($avgPct > 0): ?>
                                <div class="inline-flex items-center gap-2.5">
                                    <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all <?= $avgPct >= 70 ? 'bg-emerald-400' : ($avgPct >= 40 ? 'bg-amber-400' : 'bg-red-400') ?>" style="width:<?= min(100, $avgPct) ?>%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-600"><?= number_format($avgPct, 1) ?>%</span>
                                </div>
                                <?php else: ?>
                                <span class="text-xs text-slate-400">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
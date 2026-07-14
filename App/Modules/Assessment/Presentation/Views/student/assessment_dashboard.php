<?php
$completed = count(array_filter($assessments, fn($a) => ($a['student_status'] ?? '') === 'completed'));
$total = count($assessments);
$overallPct = $total > 0 ? round(($completed / $total) * 100) : 0;
$allDone = $total > 0 && $completed === $total;

$accentColors = [
    1 => ['bg' => '#eef2ff', 'color' => '#5B5FEF', 'grad' => 'from-[#5B5FEF] to-[#7B7FF5]'],
    2 => ['bg' => '#ecfdf5', 'color' => '#059669', 'grad' => 'from-[#059669] to-[#34d399]'],
    3 => ['bg' => '#fffbeb', 'color' => '#d97706', 'grad' => 'from-[#d97706] to-[#fbbf24]'],
    4 => ['bg' => '#fdf2f8', 'color' => '#db2777', 'grad' => 'from-[#db2777] to-[#f472b6]'],
];
$slugMap = [1 => 'personality', 2 => 'interest', 3 => 'aptitude', 4 => 'values'];
?>
<div class="mx-auto w-full max-w-7xl overflow-x-hidden px-4 py-6 sm:px-6 lg:px-8 assessment-dashboard">
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">My Assessments</h1>
                <p class="mt-1.5 text-sm sm:text-base text-slate-500">Complete all four to unlock your personalized career recommendation.</p>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-3">
            <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-700 ease-out" style="width: <?= $overallPct ?>%"></div>
            </div>
            <span class="text-sm font-semibold text-slate-600 whitespace-nowrap"><?= $completed ?>/<?= $total ?></span>
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4" id="assessmentCardGrid">
        <?php foreach ($assessments as $i => $a):
            $id = (int)$a['assessment_id'];
            $status = $a['student_status'] ?? 'not_started';
            $attemptId = $a['attempt_id'] ? (int)$a['attempt_id'] : null;
            $curr = (int)($a['current_question'] ?? 0);
            $answered = 0;
            if ($attemptId) {
                try { $pdo = \App\Config\Database::getConnection(); $st=$pdo->prepare("SELECT COUNT(*) FROM student_answers WHERE student_assessment_id=:id"); $st->execute([':id'=>$attemptId]); $answered=(int)$st->fetchColumn(); } catch(\Throwable $e) {}
            }
            $totalQ = (int)($a['total_questions'] ?? 10);
            $pct = $totalQ > 0 ? min(100, round(($answered / $totalQ) * 100)) : 0;
            $ac = $accentColors[$id] ?? $accentColors[1];
            $icon = $a['icon'] ?? 'bi-collection';
            $timeLimit = (int)($a['time_limit'] ?? 15);
            $animDelay = 0.08 * ($i + 1);
        ?>
        <div class="assessment-card card-in" style="animation-delay: <?= $animDelay ?>s;">
            <div class="flex items-center gap-3.5">
                <div class="card-icon-wrap" style="background:<?= $ac['bg'] ?>;color:<?= $ac['color'] ?>">
                    <i class="<?= htmlspecialchars($icon) ?> text-xl"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-base font-bold text-slate-900 truncate"><?= htmlspecialchars($a['title']) ?></h3>
                    <p class="text-xs text-slate-400 mt-0.5"><?= $totalQ ?> questions · <?= $timeLimit ?> min</p>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <?php if ($status === 'completed'): ?>
                    <span class="status-badge completed"><span class="status-dot completed"></span>Completed</span>
                    <span class="text-[11px] font-medium text-slate-400"><?= $pct ?>%</span>
                <?php elseif ($status === 'in_progress'): ?>
                    <span class="status-badge in-progress"><span class="status-dot in-progress"></span>In Progress</span>
                    <span class="text-[11px] font-medium text-slate-500"><?= $answered ?>/<?= $totalQ ?></span>
                <?php else: ?>
                    <span class="status-badge not-started">Not Started</span>
                    <span class="text-[11px] font-medium text-slate-400">0%</span>
                <?php endif; ?>
            </div>

            <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500" style="width:<?= $pct ?>%;background:<?= $ac['color'] ?>"></div>
            </div>

            <?php if ($status === 'completed'):
                $slug = $slugMap[$id] ?? strtolower($a['title']);
            ?>
                <a href="<?= BASE_URL ?>/index.php?page=assessment-result&slug=<?= htmlspecialchars($slug) ?>"
                   class="assessment-action-btn mt-4 w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-white transition-all duration-200 active:scale-[0.97] no-underline"
                   style="background:linear-gradient(135deg,<?= $ac['color'] ?>,<?= $ac['color'] ?>dd)">
                    <i class="bi bi-eye"></i> View Result
                </a>
            <?php else: ?>
                <button type="button"
                        class="assessment-action-btn mt-4 w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-white transition-all duration-200 active:scale-[0.97]"
                        style="background:linear-gradient(135deg,<?= $ac['color'] ?>,<?= $ac['color'] ?>dd)"
                        data-assessment-id="<?= $id ?>"
                        data-attempt-id="<?= $attemptId ?? 0 ?>"
                        data-status="<?= $status ?>">
                    <i class="bi bi-play-fill"></i> Continue
                </button>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-6 rounded-[26px] border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-indigo-700">
                    <i class="bi bi-star-fill text-xs mr-1.5"></i> Career Recommendation
                </span>
                <?php if ($allDone): ?>
                    <h3 class="mt-3 text-lg font-bold text-slate-900">Your career matches are ready</h3>
                    <p class="mt-2 text-sm text-slate-500">You completed every assessment. View your personalized career recommendations now.</p>
                <?php else: ?>
                    <h3 class="mt-3 text-lg font-bold text-slate-900">Unlocks after all assessments</h3>
                    <p class="mt-2 text-sm text-slate-500">You're <?= $completed ?>/<?= $total ?> complete. Finish the rest to unlock your recommendation.</p>
                <?php endif; ?>
            </div>
            <?php if ($allDone): ?>
                <a href="<?= BASE_URL ?>/index.php?page=recommendation" class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:from-indigo-700 hover:to-violet-700 active:scale-[0.97] no-underline">
                    View Recommendation <i class="bi bi-arrow-right"></i>
                </a>
            <?php else: ?>
                <div class="shrink-0 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm font-bold text-slate-400">
                    <i class="bi bi-lock-fill"></i> Locked
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="assessmentQuestionContainer" class="hidden mx-auto w-full max-w-4xl px-4 py-6 sm:px-6 lg:px-8"><?php require __DIR__ . '/assessment_question.php'; ?></div>
<div id="assessmentCompleteContainer" class="hidden mx-auto w-full max-w-2xl px-4 py-6 sm:px-6 lg:px-8"><?php require __DIR__ . '/assessment_complete.php'; ?></div>

<script src="<?= BASE_URL ?>/assets/js/assessment-engine.js"></script>

<style>
@keyframes slideUpCard { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes pulse-glow { 0%,100%{box-shadow:0 0 0 0 rgba(91,95,239,0.4)} 50%{box-shadow:0 0 0 12px rgba(91,95,239,0)} }
.card-in { animation: slideUpCard 0.5s cubic-bezier(0.22,1,0.36,1) both; }
.assessment-dashboard .assessment-card {
    background: #fff; border-radius: 20px; border: 1px solid #e2e8f0; padding: 24px;
    transition: transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out;
    cursor: default;
}
.assessment-dashboard .assessment-card:hover {
    transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(15,23,42,0.1); border-color: #cbd5e1;
}
.assessment-dashboard .card-icon-wrap {
    width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    transition: transform 0.3s ease-out;
}
.assessment-dashboard .assessment-card:hover .card-icon-wrap { transform: scale(1.1) rotate(-3deg); }
.status-badge {
    display: inline-flex; align-items: center; gap: 6px; border-radius: 100px;
    padding: 4px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em;
}
.status-badge.completed { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.status-badge.in-progress { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.status-badge.not-started { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
.status-dot.completed { background: #10b981; }
.status-dot.in-progress { background: #f59e0b; }
.assessment-action-btn { position: relative; overflow: hidden; }
.assessment-action-btn::after {
    content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 50%);
    pointer-events: none;
}
</style>

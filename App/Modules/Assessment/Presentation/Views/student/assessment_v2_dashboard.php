<?php
$limits = [1 => 8, 2 => 8, 3 => 10, 4 => 6];
$times = [1 => 3, 2 => 3, 3 => 4, 4 => 2];
$icons = [
    1 => ['icon' => 'bi-person-badge', 'bg' => 'rgba(91,95,239,0.12)', 'color' => '#5B5FEF'],
    2 => ['icon' => 'bi-activity', 'bg' => 'rgba(5,150,105,0.12)', 'color' => '#059669'],
    3 => ['icon' => 'bi-cpu', 'bg' => 'rgba(217,119,6,0.12)', 'color' => '#d97706'],
    4 => ['icon' => 'bi-heart', 'bg' => 'rgba(219,39,119,0.12)', 'color' => '#db2777'],
];
$completed = count(array_filter($assessments, fn($a) => isset($results[(int)$a['assessment_id']]) && $results[(int)$a['assessment_id']]['status'] === 'completed'));
$total = count($assessments);
?>
<div x-data="assessmentApp()" class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

    <!-- ========== DASHBOARD VIEW ========== -->
    <div x-show="view === 'dashboard'">
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">My Assessments</h1>
            <p class="mt-2 text-slate-500 text-base">Complete all four assessments to unlock your personalized career recommendation.</p>
            <div class="mt-5 flex items-center gap-4">
                <div class="flex-1 h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-700 ease-out" style="width:<?= $total > 0 ? ($completed/$total)*100 : 0 ?>%"></div>
                </div>
                <span class="text-sm font-bold text-slate-600 whitespace-nowrap"><?= $completed ?>/<?= $total ?></span>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($assessments as $a):
                $id = (int)$a['assessment_id'];
                $limit = $limits[$id] ?? 8;
                $estTime = $times[$id] ?? 3;
                $ic = $icons[$id] ?? $icons[1];
                $hasResult = $results[$id] ?? null;
                $status = $hasResult ? $hasResult['status'] : 'not_started';
                $pct = $hasResult ? (float)$hasResult['percentage'] : 0;
            ?>
            <div class="assessment-card-v2" style="--accent:<?= $ic['color'] ?>">
                <div class="flex items-center gap-3.5 mb-4">
                    <div class="v2-icon-wrap" style="background:<?= $ic['bg'] ?>;color:<?= $ic['color'] ?>">
                        <i class="<?= $ic['icon'] ?> text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900"><?= htmlspecialchars($a['title']) ?></h3>
                        <p class="text-xs text-slate-400 mt-0.5"><?= $limit ?> questions · &asymp;<?= $estTime ?> min</p>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-3">
                    <span class="status-badge-v2 <?= $status ?>">
                        <?php if ($status === 'completed'): ?><span class="status-dot-v2 completed"></span>Completed
                        <?php elseif ($status === 'in_progress'): ?><span class="status-dot-v2 in-progress"></span>In Progress
                        <?php else: ?><span class="status-dot-v2 not-started"></span>Not Started<?php endif; ?>
                    </span>
                    <?php if ($status === 'in_progress'): ?>
                        <span class="text-xs font-semibold text-slate-500"><?= round($pct) ?>%</span>
                    <?php endif; ?>
                </div>

                <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden mb-4">
                    <div class="h-full rounded-full transition-all duration-500" style="width:<?= $pct ?>%;background:<?= $ic['color'] ?>"></div>
                </div>

                <?php if ($status === 'completed'): ?>
                    <a href="<?= BASE_URL ?>/index.php?page=assessment-v2-result&id=<?= $id ?>"
                       class="v2-action-btn text-center no-underline"
                       style="background:linear-gradient(135deg, <?= $ic['color'] ?>, <?= $ic['color'] ?>dd)">
                        <i class="bi bi-eye"></i> View Result
                    </a>
                <?php else: ?>
                    <button type="button"
                            @click="startAssessment(<?= $id ?>)"
                            class="v2-action-btn"
                            style="background:linear-gradient(135deg, <?= $ic['color'] ?>, <?= $ic['color'] ?>dd)">
                        <?php if ($status === 'in_progress'): ?>
                            <i class="bi bi-play-fill"></i> Continue
                        <?php else: ?>
                            <i class="bi bi-play-fill"></i> Start
                        <?php endif; ?>
                    </button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-8 rounded-2xl border border-slate-200 bg-white/80 backdrop-blur-sm p-6 sm:p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-indigo-200 bg-indigo-50/80 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-indigo-700">
                        <i class="bi bi-star-fill text-xs"></i> Career Recommendation
                    </span>
                    <?php if ($allDone): ?>
                        <h3 class="mt-3 text-lg font-bold text-slate-900">Your career matches are ready</h3>
                        <p class="mt-1 text-sm text-slate-500">View your personalized career recommendations now.</p>
                    <?php else: ?>
                        <h3 class="mt-3 text-lg font-bold text-slate-900">Complete all assessments to unlock</h3>
                        <p class="mt-1 text-sm text-slate-500">You're <?= $completed ?>/<?= $total ?> done.</p>
                    <?php endif; ?>
                </div>
                <?php if ($allDone): ?>
                    <a href="<?= BASE_URL ?>/index.php?page=career-recommendation" class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:shadow-md transition-all active:scale-[0.97] no-underline">
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

    <!-- ========== QUESTION VIEW ========== -->
    <div x-show="view === 'question'" class="mx-auto w-full max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="mb-5 flex items-center justify-between">
            <button type="button" @click="exitAssessment" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white/80 backdrop-blur-sm px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">
                <i class="bi bi-arrow-left"></i> Exit
            </button>
            <div class="flex items-center gap-4 text-sm font-semibold text-slate-500">
                <span x-show="elapsedTime !== '00:00'"><i class="bi bi-clock mr-1"></i><span x-text="elapsedTime"></span></span>
                <span><i class="bi bi-question-circle mr-1"></i><span x-text="currentIndex + 1 + '/' + totalQuestions"></span></span>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-1.5">
                <span>Question <span x-text="currentIndex + 1"></span> of <span x-text="totalQuestions"></span></span>
                <span x-text="Math.round(((currentIndex + 1) / totalQuestions) * 100) + '%'"></span>
            </div>
            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-500 ease-out" :style="'width:' + Math.round(((currentIndex + 1) / totalQuestions) * 100) + '%'"></div>
            </div>
        </div>

        <div class="question-card-v2">
            <div class="flex items-center justify-center min-h-[300px]" x-show="loading">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-10 h-10 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                    <p class="text-sm font-medium text-slate-400">Loading question...</p>
                </div>
            </div>

            <div x-show="!loading">
                <div class="flex items-center gap-3 mb-5">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 text-sm font-bold" x-text="'Q' + (currentIndex + 1)"></span>
                    <p class="question-text-v2" x-text="currentQuestion.text"></p>
                </div>

                <div class="space-y-3">
                    <template x-for="(opt, oi) in currentQuestion.options" :key="opt.key">
                        <div class="option-item-v2" :class="{ 'selected': selectedAnswer === opt.key }"
                             @click="selectAnswer(opt.key)"
                             :style="selectedAnswer === opt.key ? 'border-color:var(--accent);background:var(--accent-bg)' : ''">
                            <span class="option-key" :class="{ 'selected': selectedAnswer === opt.key }" x-text="opt.key"></span>
                            <span class="option-text-v2" x-text="opt.text"></span>
                            <span x-show="selectedAnswer === opt.key" class="ml-auto text-accent">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <button type="button" @click="prevQuestion" :disabled="currentIndex === 0"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-[0.97]">
                <i class="bi bi-chevron-left"></i> Previous
            </button>
            <button type="button" @click="nextQuestion"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold text-white transition-all active:scale-[0.97]"
                    :class="isLast ? 'bg-gradient-to-r from-emerald-500 to-emerald-600' : 'bg-gradient-to-r from-indigo-500 to-violet-500'"
                    x-text="isLast ? 'Finish' : 'Next'">
            </button>
        </div>
    </div>

    <!-- ========== COMPLETE VIEW ========== -->
    <div x-show="view === 'complete'" class="mx-auto w-full max-w-2xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="complete-card-v2">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 text-emerald-500 mb-5">
                    <i class="bi bi-check-circle-fill text-4xl"></i>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Assessment Completed</h2>
                <p class="mt-2 text-slate-500" x-text="'You completed the ' + assessmentName + ' assessment.'"></p>
            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="score-stat-v2">
                    <span class="score-label">Your Score</span>
                    <div class="mt-2 flex items-baseline gap-1">
                        <span class="score-value text-indigo-600" x-text="Math.round(finalPercentage)"></span>
                        <span class="text-sm font-semibold text-slate-400">%</span>
                    </div>
                </div>
                <div class="score-stat-v2">
                    <span class="score-label">Answered</span>
                    <div class="mt-2 flex items-baseline gap-1">
                        <span class="score-value text-emerald-600" x-text="finalAnswered"></span>
                        <span class="text-sm font-semibold text-slate-400">questions</span>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex items-center justify-between text-sm font-semibold text-slate-500 mb-2">
                    <span>Score</span>
                    <span x-text="Math.round(finalPercentage) + '%'"></span>
                </div>
                <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-1000 ease-out" :style="'width:' + finalPercentage + '%;background:' + (finalPercentage >= 80 ? '#059669' : finalPercentage >= 50 ? '#d97706' : '#ef4444')"></div>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all active:scale-[0.97] no-underline">
                    <i class="bi bi-grid"></i> Back to Dashboard
                </a>
                <a x-show="allCompleted" href="<?= BASE_URL ?>/index.php?page=career-recommendation" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-3 text-sm font-bold text-white shadow-sm hover:shadow-md transition-all active:scale-[0.97] no-underline">
                    View Career Recommendation <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

</div>

<script src="<?= BASE_URL ?>/assets/js/assessment-v2.js"></script>

<style>
.assessment-card-v2 {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 24px;
    border: 1px solid rgba(226,232,240,0.8);
    padding: 24px;
    transition: transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out;
    box-shadow: 0 4px 16px rgba(15,23,42,0.04);
}
.assessment-card-v2:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px -12px rgba(15,23,42,0.12);
    border-color: var(--accent);
}
.v2-icon-wrap {
    width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center;
    transition: transform 0.3s ease-out;
}
.assessment-card-v2:hover .v2-icon-wrap { transform: scale(1.1) rotate(-3deg); }
.status-badge-v2 { display: inline-flex; align-items: center; gap: 6px; border-radius: 100px; padding: 4px 12px; font-size: 11px; font-weight: 700; }
.status-badge-v2.completed { background: rgba(5,150,105,0.1); color: #065f46; border: 1px solid rgba(5,150,105,0.2); }
.status-badge-v2.in_progress { background: rgba(217,119,6,0.1); color: #92400e; border: 1px solid rgba(217,119,6,0.2); }
.status-badge-v2.not_started { background: rgba(100,116,139,0.08); color: #64748b; border: 1px solid rgba(100,116,139,0.15); }
.status-dot-v2 { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
.status-dot-v2.completed { background: #10b981; }
.status-dot-v2.in_progress { background: #f59e0b; }
.status-dot-v2.not_started { background: #94a3b8; }
.v2-action-btn {
    width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    border-radius: 12px; padding: 10px 16px; font-size: 14px; font-weight: 700; color: #fff; border: none;
    cursor: pointer; transition: all 0.2s; position: relative; overflow: hidden;
}
.v2-action-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 20px -8px rgba(0,0,0,0.2); }
.v2-action-btn:active { transform: scale(0.97); }
.v2-action-btn::after { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(255,255,255,0.15) 0%,transparent 50%); pointer-events:none; }

.question-card-v2 {
    background: rgba(255,255,255,0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
    border-radius: 24px; border: 1px solid rgba(226,232,240,0.8); padding: 28px;
    box-shadow: 0 8px 30px rgba(15,23,42,0.06);
    --accent: #5B5FEF; --accent-bg: rgba(91,95,239,0.08);
}
.question-text-v2 { font-size: 1.1rem; font-weight: 600; line-height: 1.6; color: #0f172a; }
.option-item-v2 {
    display: flex; align-items: center; gap: 14px; padding: 14px 18px; border-radius: 14px;
    border: 1.5px solid rgba(226,232,240,0.8); background: rgba(255,255,255,0.6); cursor: pointer;
    transition: all 0.2s ease-out;
}
.option-item-v2:hover { border-color: #a5b4fc; background: rgba(91,95,239,0.04); }
.option-item-v2.selected { border-color: var(--accent); background: var(--accent-bg); }
.option-key {
    width: 32px; height: 32px; border-radius: 10px; background: #f1f5f9; color: #64748b;
    display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; flex-shrink: 0;
    transition: all 0.2s;
}
.option-key.selected { background: var(--accent); color: #fff; }
.option-text-v2 { font-size: 0.95rem; font-weight: 500; color: #1e293b; }
.option-item-v2.selected .option-text-v2 { color: #0f172a; font-weight: 600; }
.text-accent { color: var(--accent); }

.complete-card-v2 {
    background: rgba(255,255,255,0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
    border-radius: 28px; border: 1px solid rgba(226,232,240,0.8); padding: 36px;
    box-shadow: 0 8px 30px rgba(15,23,42,0.06);
}
.score-stat-v2 {
    background: rgba(248,250,252,0.8); backdrop-filter: blur(8px); border-radius: 16px;
    border: 1px solid rgba(226,232,240,0.6); padding: 20px; text-align: center;
}
.score-label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; }
.score-value { font-size: 32px; font-weight: 800; line-height: 1; }
</style>

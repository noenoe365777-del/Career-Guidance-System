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

$allDone = $total > 0 && $completed === $total;
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

    <!-- ========== LOADING VIEW ========== -->
    <div x-show="view === 'loading'" class="mx-auto w-full max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex items-center justify-center min-h-[400px]">
            <div class="flex flex-col items-center gap-4">
                <div class="w-12 h-12 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                <p class="text-sm font-medium text-slate-400">Loading question...</p>
            </div>
        </div>
    </div>

    <!-- ========== TAKING VIEW ========== -->
    <div x-show="view === 'taking'" class="mx-auto w-full max-w-[800px] px-4 py-8 sm:px-6 lg:px-8">

        <!-- Top Toolbar -->
        <div class="mb-5 flex items-center justify-between">
            <button type="button" @click="exitAssessment"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 hover:border-slate-300 transition-colors duration-150">
                <i class="bi bi-arrow-left text-sm"></i>
                <span>Exit</span>
            </button>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 text-sm text-slate-400 tabular-nums">
                    <i class="bi bi-clock text-xs"></i>
                    <span x-text="elapsedTime" x-show="elapsedTime !== '00:00'">00:00</span>
                    <span x-show="elapsedTime === '00:00'">00:00</span>
                </span>
                <span class="w-px h-4 bg-slate-200"></span>
                <span class="inline-flex items-center gap-1.5 text-sm text-slate-400 tabular-nums">
                    <i class="bi bi-list-ol text-xs"></i>
                    <span><span x-text="currentIndex + 1" class="font-medium text-slate-600"></span><span class="text-slate-300">/</span><span x-text="totalQuestions" class="font-medium text-slate-600"></span></span>
                </span>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="mb-4">
            <div class="flex items-baseline justify-between mb-1.5">
                <span class="text-sm text-slate-500">Question <span x-text="currentIndex + 1" class="font-semibold text-slate-800"></span> <span class="text-slate-300">of</span> <span x-text="totalQuestions" class="font-semibold text-slate-800"></span></span>
                <span class="text-xs font-semibold text-indigo-500 tabular-nums" x-text="Math.round(((currentIndex + 1) / totalQuestions) * 100) + '%'"></span>
            </div>
            <div class="relative h-1.5 w-full rounded-full bg-[#EEF2FF] overflow-hidden">
                <div class="absolute inset-y-0 left-0 rounded-full bg-[#6366F1] transition-all duration-300 ease-out"
                     :style="'width:' + Math.round(((currentIndex + 1) / totalQuestions) * 100) + '%'"></div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="question-card-v2">
            <div class="mb-6">
                <span class="inline-flex items-center justify-center h-8 min-w-[36px] rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold tracking-wide px-2.5 border border-indigo-100 mb-4"
                      x-text="'Q' + (currentIndex + 1)"></span>
                <h2 class="text-[1.35rem] font-bold leading-relaxed text-slate-900 tracking-tight" x-text="currentQuestion.text"></h2>
            </div>

            <!-- Answer Options -->
            <div class="space-y-3">
                <template x-for="(opt, oi) in currentQuestion.options" :key="opt.key">
                    <button type="button"
                            class="option-item-v2"
                            :class="{ 'selected': selectedAnswer === opt.key }"
                            @click="selectAnswer(opt.key)"
                            :style="selectedAnswer === opt.key ? 'border-color:var(--accent);background:var(--accent-bg)' : ''">
                        <span class="option-radio" :class="{ 'active': selectedAnswer === opt.key }">
                            <span class="option-radio-dot"></span>
                        </span>
                        <span class="option-key" :class="{ 'selected': selectedAnswer === opt.key }" x-text="opt.key"></span>
                        <span class="option-text-v2" x-text="opt.text"></span>
                    </button>
                </template>
            </div>
        </div>

            <!-- Navigation Buttons -->
            <div class="mt-8 flex items-center justify-between gap-4">
                <button type="button" @click="prevQuestion" :disabled="currentIndex === 0"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:border-slate-300 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:border-slate-200 transition-all duration-200">
                    <i class="bi bi-chevron-left text-base"></i>
                    <span>Previous</span>
                </button>
                <button type="button" @click="nextQuestion"
        class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold text-white transition-all active:scale-[0.97]"
        :class="isLast ? 'bg-gradient-to-r from-[#15479A] to-blue-700 hover:from-[#134186] hover:to-blue-800' : 'bg-gradient-to-r from-indigo-500 to-violet-500 hover:from-indigo-600 hover:to-violet-600'"
        x-text="isLast ? 'Finish' : 'Next'">
</button>
            </div>
    </div>

    <!-- ========== COMPLETED VIEW ========== -->
    <div x-show="view === 'completed'" x-cloak class="mx-auto w-full max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="stats-grid mb-10" role="list" aria-label="Assessment Statistics">
                <div class="stat-card" role="listitem" x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="animation-delay: 0.3s;">
                    <div class="stat-icon" aria-hidden="true">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Your Score</p>
                        <p class="stat-value text-indigo-600" x-text="Math.round(finalPercentage) + '%'"></p>
                    </div>
                </div>
                <div class="stat-card" role="listitem" x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="animation-delay: 0.4s;">
                    <div class="stat-icon" aria-hidden="true">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Questions Answered</p>
                        <p class="stat-value text-emerald-600" x-text="finalAnswered + ' / ' + totalQuestions"></p>
                    </div>
                </div>
            </div>

            <!-- Performance Summary -->
            <div class="performance-summary mb-10" x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="animation-delay: 0.5s;">
                <div class="performance-header flex items-center gap-3 mb-4">
                    <div class="performance-icon" :class="performanceIconClass" aria-hidden="true">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.273a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.273a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" x-text="performanceTitle"></h3>
                        <p class="text-sm text-slate-500" x-text="performanceDescription"></p>
                    </div>
                </div>
                <div class="performance-stars" aria-label="Performance rating" role="img">
                    <template x-for="i in 5" :key="i">
                        <span class="star" :class="{ filled: i <= performanceStars }" aria-hidden="true">★</span>
                    </template>
                </div>
            </div>

            <!-- Recommendation Preview -->
            <div class="recommendation-preview mb-10" x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="animation-delay: 0.6s;">
                <div x-show="!hasRecommendation" x-transition class="recommendation-locked">
                    <div class="lock-icon" aria-hidden="true">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-slate-900 mb-2">Recommendation Locked</h4>
                    <p class="text-slate-500 text-sm mb-4">Complete the remaining assessments to unlock personalized career recommendations.</p>
                    <div class="progress-indicators">
                        <template x-for="assessment in allAssessments" :key="assessment.id">
                            <div class="assessment-progress-item" :class="{ completed: assessment.completed }">
                                <span class="progress-dot" :class="{ 'bg-emerald-500': assessment.completed, 'bg-slate-300': !assessment.completed }" aria-hidden="true"></span>
                                <span class="progress-label" :class="{ 'text-slate-700': assessment.completed, 'text-slate-400': !assessment.completed }" x-text="assessment.name"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="hasRecommendation" x-transition class="recommendation-card">
                    <div class="recommendation-header">
                        <div class="recommendation-badge">
                            <span class="badge-icon" aria-hidden="true">✨</span>
                            <span class="badge-text">Recommended Career</span>
                        </div>
                        <div class="match-score" :class="matchColorClass">
                            <span class="match-value" x-text="recommendation.match + '% Match'"></span>
                        </div>
                    </div>
                    <div class="recommendation-content">
                        <h4 class="recommendation-title" x-text="recommendation.title"></h4>
                        <p class="recommendation-description" x-text="recommendation.description"></p>
                        <div class="recommendation-tags" aria-label="Key skills">
                            <template x-for="tag in recommendation.tags" :key="tag">
                                <span class="tag" x-text="tag"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons" x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="animation-delay: 0.7s;">
                <a x-show="hasRecommendation" :href="recommendationUrl" class="btn-primary" x-text="hasRecommendation ? 'View Career Recommendation' : 'Back to Dashboard'"></a>
                <template x-if="hasRecommendation">
                    <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="btn-secondary">Back to Dashboard</a>
                </template>
                <template x-if="!hasRecommendation">
                    <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2" class="btn-primary">Back to Dashboard</a>
                </template>
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
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02);
    transition: box-shadow 0.3s ease;
}

.question-card-v2:hover {
    box-shadow: 0 2px 6px rgba(0,0,0,0.05), 0 8px 24px rgba(0,0,0,0.03);
}

.option-item-v2 {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 0 18px;
    height: 60px;
    border-radius: 16px;
    border: 1.5px solid #e5e7eb;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: left;
    width: 100%;
}

.option-item-v2:hover {
    border-color: #c7d2fe;
    background: #fafafe;
}

.option-item-v2.selected {
    border-color: #6366F1 !important;
    background: #EEF2FF !important;
    box-shadow: 0 0 0 4px rgba(99,102,241,0.12) !important;
}

.option-radio {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #d1d5db;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.option-radio.active {
    border-color: #6366F1;
    background: #6366F1;
}

.option-radio-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ffffff;
    opacity: 0;
    transform: scale(0);
    transition: all 0.2s ease;
}

.option-radio.active .option-radio-dot {
    opacity: 1;
    transform: scale(1);
}

.option-key {
    width: 30px;
    height: 30px;
    border-radius: 9px;
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.option-key.selected {
    background: #6366F1;
    color: #ffffff;
}

.option-text-v2 {
    font-size: 0.95rem;
    font-weight: 500;
    color: #334155;
    line-height: 1.4;
    flex: 1;
}

.option-item-v2.selected .option-text-v2 {
    color: #0f172a;
    font-weight: 600;
}

@keyframes slideUpCard {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes cardFloat {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-8px);
    }
}

@keyframes pulseGlow {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(var(--accent-rgb), 0.4);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(var(--accent-rgb), 0);
    }
}

@keyframes shimmer {
    0% {
        background-position: -100% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes gradientShift {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

@keyframes bounceSubtle {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-4px);
    }
}

@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
        transform: none !important;
    }
}

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

/* ========== PREMIUM COMPLETION VIEW STYLES ========== */
.completion-container {
    background: #fff;
    border-radius: 32px;
    border: 1px solid #E2E8F0;
    padding: 40px 32px;
    box-shadow: 0 12px 40px rgba(15,23,42,0.08);
    position: relative;
    overflow: hidden;
}

.completion-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #4F46E5, #7C3AED, #10B981);
}

/* Confetti Container */
.confetti-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    overflow: hidden;
    z-index: 10;
}

.confetti-piece {
    position: absolute;
    top: -10px;
    width: 10px;
    height: 10px;
    border-radius: 2px;
    animation: confettiFall 3s ease-in forwards;
}

@keyframes confettiFall {
    0% {
        transform: translateY(0) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}

/* Success Animation */
.success-animation-wrapper {
    position: relative;
    width: 100px;
    height: 100px;
}

.success-checkmark {
    width: 100px;
    height: 100px;
    transform: rotate(-90deg);
}

.checkmark-circle {
    stroke-dasharray: 314;
    stroke-dashoffset: 314;
    animation: circleDraw 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

.checkmark-path {
    stroke-dasharray: 100;
    stroke-dashoffset: 100;
    animation: pathDraw 0.6s cubic-bezier(0.22, 1, 0.36, 1) 0.5s forwards;
}

@keyframes circleDraw {
    to { stroke-dashoffset: 0; }
}

@keyframes pathDraw {
    to { stroke-dashoffset: 0; }
}

.success-pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100px;
    height: 100px;
    border: 3px solid #10B981;
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
    opacity: 0;
    animation: pulseRing 1.5s ease-out infinite;
}

.success-pulse-ring:nth-child(3) { animation-delay: 0.3s; }
.success-pulse-ring:nth-child(4) { animation-delay: 0.6s; }

@keyframes pulseRing {
    0% { transform: translate(-50%, -50%) scale(0); opacity: 0.6; }
    50% { opacity: 0.3; }
    100% { transform: translate(-50%, -50%) scale(1.5); opacity: 0; }
}

/* Circular Score */
.circular-score-wrapper {
    position: relative;
    width: 200px;
    height: 200px;
}

.circular-score-svg {
    transform: rotate(-90deg);
    width: 100%;
    height: 100%;
}

.score-track {
    transition: stroke 0.3s ease;
}

.score-progress {
    transition: stroke-dashoffset 1.5s cubic-bezier(0.22, 1, 0.36, 1), stroke 0.3s ease;
}

.circular-score-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    pointer-events: none;
}

.circular-score-value {
    font-size: 48px;
    font-weight: 800;
    line-height: 1;
    color: #0F172A;
    font-variant-numeric: tabular-nums;
}

.circular-score-percent {
    font-size: 24px;
    font-weight: 600;
    color: #64748B;
}

.circular-score-label {
    display: block;
    margin-top: 4px;
    font-size: 14px;
    font-weight: 500;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.stat-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px -8px rgba(15,23,42,0.1);
    border-color: #CBD5E1;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: linear-gradient(135deg, #EEF2FF, #EDE9FE);
    color: #4F46E5;
}

.stat-content {
    flex: 1;
    text-align: left;
}

.stat-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94A3B8;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 22px;
    font-weight: 800;
    line-height: 1.2;
}

/* Performance Summary */
.performance-summary {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 24px;
}

.performance-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.performance-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.performance-icon.excellent {
    background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
    color: #059669;
}

.performance-icon.good {
    background: linear-gradient(135deg, #FFF7ED, #FED7AA);
    color: #D97706;
}

.performance-icon.developing {
    background: linear-gradient(135deg, #FEF2F2, #FECACA);
    color: #DC2626;
}

.performance-icon strong {
    font-size: 18px;
}

.performance-header h3 {
    margin-bottom: 4px;
}

.performance-header p {
    margin: 0;
}

.performance-stars {
    display: flex;
    gap: 4px;
    margin-top: 12px;
}

.performance-stars .star {
    font-size: 22px;
    color: #E2E8F0;
    transition: color 0.2s, transform 0.2s;
}

.performance-stars .star.filled {
    color: #FBBF24;
    animation: starPop 0.3s cubic-bezier(0.22, 1, 0.36, 1) backwards;
}

.performance-stars .star.filled:nth-child(1) { animation-delay: 0.6s; }
.performance-stars .star.filled:nth-child(2) { animation-delay: 0.7s; }
.performance-stars .star.filled:nth-child(3) { animation-delay: 0.8s; }
.performance-stars .star.filled:nth-child(4) { animation-delay: 0.9s; }
.performance-stars .star.filled:nth-child(5) { animation-delay: 1.0s; }

@keyframes starPop {
    0% { transform: scale(0); }
    50% { transform: scale(1.3); }
    100% { transform: scale(1); }
}

/* Recommendation Preview */
.recommendation-preview {
    position: relative;
}

.recommendation-locked,
.recommendation-card {
    border-radius: 20px;
    padding: 28px;
    transition: all 0.3s ease;
}

.recommendation-locked {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border: 1px solid #E2E8F0;
    text-align: center;
}

.lock-icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    background: linear-gradient(135deg, #E2E8F0, #CBD5E1);
    color: #94A3B8;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}

.recommendation-locked h4 {
    margin-bottom: 8px;
}

.recommendation-locked p {
    margin-bottom: 20px;
    line-height: 1.6;
}

.progress-indicators {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: 280px;
    margin: 0 auto;
}

.assessment-progress-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #E2E8F0;
    text-align: left;
    transition: all 0.2s ease;
}

.assessment-progress-item.completed {
    border-color: #A7F3D0;
    background: #F0FDF4;
}

.progress-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.progress-label {
    font-size: 14px;
    font-weight: 500;
    flex: 1;
    text-align: left;
}

.recommendation-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    box-shadow: 0 8px 24px rgba(15,23,42,0.06);
}

.recommendation-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.recommendation-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: linear-gradient(135deg, #F5F3FF, #EDE9FE);
    border-radius: 100px;
    font-size: 13px;
    font-weight: 600;
    color: #7C3AED;
}

.badge-icon {
    font-size: 14px;
}

.match-score {
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 13px;
    font-weight: 700;
}

.match-score.high {
    background: #ECFDF5;
    color: #059669;
}

.match-score.medium {
    background: #FFF7ED;
    color: #D97706;
}

.match-score.low {
    background: #FEF2F2;
    color: #DC2626;
}

.match-value {
    font-variant-numeric: tabular-nums;
}

.recommendation-content {
    text-align: left;
}

.recommendation-title {
    font-size: 20px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 8px;
}

.recommendation-description {
    color: #64748B;
    line-height: 1.6;
    margin-bottom: 16px;
}

.recommendation-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: #F1F5F9;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 500;
    color: #475569;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
}

@media (min-width: 640px) {
    .action-buttons {
        flex-direction: row;
        justify-content: center;
    }
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 16px 32px;
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    border-radius: 14px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 16px rgba(79,70,229,0.3);
    min-width: 200px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(79,70,229,0.4);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 16px 32px;
    font-size: 15px;
    font-weight: 600;
    color: #4F46E5;
    background: #fff;
    border: 2px solid #E0E7FF;
    border-radius: 14px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
    min-width: 200px;
}

.btn-secondary:hover {
    background: #EEF2FF;
    border-color: #C7D2FE;
    transform: translateY(-2px);
}

.btn-secondary:active {
    transform: translateY(0);
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .confetti-piece,
    .success-pulse-ring,
    .checkmark-circle,
    .checkmark-path,
    .score-progress,
    .stat-card,
    .performance-stars .star,
    .btn-primary,
    .btn-secondary {
        animation: none !important;
        transition: none !important;
    }
    
    .confetti-piece {
        display: none;
    }
}
</style>

<?php
$assessment = $assessment ?? [];
$answers = $answers ?? [];
$scoreData = $scoreData ?? [];
$assessmentType = $assessmentType ?? 'Assessment';
$summary = $summary ?? [];

$pct = (float)($scoreData['percentage'] ?? 0);
$answered = (int)($scoreData['answered'] ?? 0);
$total = (int)($scoreData['total'] ?? 0);
$correct = (int)($scoreData['correct'] ?? 0);

$aid = (int)($assessment['assessment_id'] ?? 0);
$isAptitude = $aid === 3;
$barColor = $pct >= 80 ? '#059669' : ($pct >= 60 ? '#d97706' : '#ef4444');
$barLabel = $pct >= 80 ? 'Excellent' : ($pct >= 60 ? 'Good' : ($pct >= 40 ? 'Moderate' : 'Needs Improvement'));
?>
<div class="mx-auto w-full max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
    <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2"
       class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors no-underline mb-6">
        <i class="bi bi-arrow-left"></i> Back to Assessments
    </a>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-8 sm:px-8 text-white">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-[11px] font-bold uppercase tracking-wider backdrop-blur-sm"><?= htmlspecialchars($assessmentType) ?></span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-[11px] font-bold uppercase tracking-wider backdrop-blur-sm">Completed</span>
            </div>
            <h1 class="text-2xl font-extrabold sm:text-3xl mt-2"><?= htmlspecialchars($assessment['assessment_name'] ?? 'Assessment Result') ?></h1>
            <p class="mt-2 text-indigo-200 text-sm">Review your performance and answers below.</p>
        </div>

        <div class="p-6 sm:p-8 space-y-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-5 text-center">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Score</p>
                    <p class="mt-1 text-3xl font-extrabold text-indigo-600"><?= $pct ?>%</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-5 text-center">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Questions</p>
                    <p class="mt-1 text-3xl font-extrabold text-slate-900"><?= $total ?></p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-5 text-center">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Answered</p>
                    <p class="mt-1 text-3xl font-extrabold text-emerald-600"><?= $answered ?></p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-5 text-center">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Correct</p>
                    <p class="mt-1 text-3xl font-extrabold text-sky-600"><?= $correct ?></p>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between text-sm font-semibold text-slate-500 mb-2">
                    <span>Progress</span>
                    <span><?= $pct ?>%</span>
                </div>
                <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-1000 ease-out" style="width:<?= $pct ?>%;background:<?= $barColor ?>"></div>
                </div>
                <p class="mt-2 text-xs font-semibold" style="color:<?= $barColor ?>"><?= $barLabel ?></p>
            </div>

            <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600"><i class="bi bi-info-circle text-sm"></i></span>
                    <h3 class="text-sm font-bold text-slate-900"><?= htmlspecialchars($summary['title'] ?? 'Summary') ?></h3>
                </div>
                <?php if ($summary && !empty($summary['value'])): ?>
                <div class="inline-block rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700 mb-3"><?= htmlspecialchars($summary['value']) ?></div>
                <p class="text-sm text-slate-600 leading-relaxed"><?= htmlspecialchars($summary['description'] ?? '') ?></p>
                <?php else: ?>
                <p class="text-sm text-slate-500">Your results are being processed.</p>
                <?php endif; ?>
            </div>

            <?php if ($answers): ?>
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-4">Question Review</h3>
                <div class="space-y-3">
                    <?php foreach ($answers as $i => $a):
                        $isCorrect = $a['correct_answer'] !== null && strtoupper($a['selected_answer']) === strtoupper($a['correct_answer']);
                        $optionLabels = ['A' => $a['option_a'], 'B' => $a['option_b'], 'C' => $a['option_c'], 'D' => $a['option_d']];
                        $selectedText = $optionLabels[strtoupper($a['selected_answer'])] ?? 'N/A';
                    ?>
                    <div class="rounded-xl border border-slate-100 bg-white p-4 <?= $isCorrect ? 'border-l-4 border-l-emerald-500' : ($a['correct_answer'] !== null ? 'border-l-4 border-l-red-400' : '') ?>">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-800">Q<?= $i + 1 ?>. <?= htmlspecialchars($a['question']) ?></p>
                                <p class="mt-1 text-xs text-slate-500">Your answer: <span class="font-semibold <?= $isCorrect ? 'text-emerald-600' : ($a['correct_answer'] !== null ? 'text-red-500' : 'text-slate-700') ?>"><?= htmlspecialchars($selectedText) ?></span></p>
                                <?php if ($a['correct_answer'] !== null && !$isCorrect): ?>
                                <p class="text-xs text-emerald-600 font-medium">Correct answer: <?= htmlspecialchars($optionLabels[strtoupper($a['correct_answer'])] ?? $a['correct_answer']) ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="shrink-0 text-xs font-bold <?= $isCorrect ? 'text-emerald-600' : ($a['correct_answer'] !== null ? 'text-red-400' : 'text-slate-400') ?>">
                                <?php if ($a['correct_answer'] !== null): ?>
                                    <?= $isCorrect ? 'Correct' : 'Incorrect' ?>
                                <?php else: ?>
                                    <?= round((float)$a['answer_score'], 1) ?> pts
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

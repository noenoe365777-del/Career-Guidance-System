<?php
$maxScore = $score ?? 0;
?>

<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6">
    <a href="<?= BASE_URL ?>/index.php?page=assessment-result&slug=<?= htmlspecialchars($_GET['slug'] ?? '') ?>"
       class="inline-flex items-center text-sm font-bold text-blue-600 transition-colors hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Results
    </a>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Detailed Answers</p>
                <h1 class="mt-1 text-2xl font-extrabold text-slate-900"><?= htmlspecialchars($assessment['title'] ?? '') ?></h1>
            </div>
            <div class="flex items-center gap-2 rounded-xl bg-slate-50 px-5 py-3 text-sm">
                <span class="font-medium text-slate-500">Score:</span>
                <span class="text-xl font-bold text-blue-600"><?= $maxScore ?></span>
            </div>
        </div>
    </div>

    <div class="mt-6 space-y-4">
        <?php if (count($detailed ?? []) > 0): ?>
            <?php foreach ($detailed as $i => $d): ?>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300 sm:p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                            <?= $i + 1 ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800 sm:text-base">
                                <?= htmlspecialchars($d['question']) ?>
                            </p>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <span class="rounded-lg bg-slate-100 px-4 py-1.5 text-xs font-semibold text-slate-600">
                                    Value: <?= (int)$d['answer_value'] ?>
                                </span>
                                <span class="rounded-lg bg-blue-50 px-4 py-1.5 text-xs font-semibold text-blue-700">
                                    <?= htmlspecialchars($d['answer_label']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                    <i class="fas fa-question text-2xl text-slate-400"></i>
                </div>
                <p class="text-sm font-medium text-slate-500">No answers found for this assessment attempt.</p>
            </div>
        <?php endif; ?>
    </div>
            
</div>

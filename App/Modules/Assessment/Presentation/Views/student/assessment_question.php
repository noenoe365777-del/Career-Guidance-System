<?php
$guestMode = $guestMode ?? false;
$assessmentId = $assessmentId ?? 0;
?>
<div class="assessment-question-page">
    <div class="mb-6 flex items-center justify-between">
        <button type="button" id="exitAssessmentBtn" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-600 transition-all hover:bg-slate-50 active:scale-[0.97]">
            <i class="bi bi-arrow-left"></i> Exit
        </button>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5 text-sm font-semibold text-slate-400">
                <i class="bi bi-clock"></i>
                <span id="timerDisplay">--:--</span>
            </div>
            <div class="flex items-center gap-1.5 text-sm font-semibold text-slate-500">
                <i class="bi bi-question-circle"></i>
                <span id="qCounterDisplay">0/0</span>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-1.5">
            <span id="progressLabel">Progress</span>
            <span id="progressPercent">0%</span>
        </div>
        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
            <div id="progressBarFill" class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-500 ease-out" style="width:0%"></div>
        </div>
    </div>

    <div class="question-card">
        <div id="questionContent">
            <div class="flex items-center justify-center py-16">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-10 h-10 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                    <p class="text-sm font-medium text-slate-400">Loading question...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 flex items-center justify-between">
        <button type="button" id="prevBtn" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 transition-all hover:bg-slate-50 hover:border-slate-300 disabled:opacity-30 disabled:cursor-not-allowed active:scale-[0.97]" disabled>
            <i class="bi bi-chevron-left"></i> Previous
        </button>
        <button type="button" id="nextBtn" class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold text-white transition-all active:scale-[0.97] disabled:opacity-50 disabled:cursor-not-allowed" style="background:linear-gradient(135deg,#5B5FEF,#7B7FF5)" disabled>
            Next <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>

<style>
@keyframes questionSlideIn {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes questionSlideOut {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(-30px); }
}
@keyframes optionFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.question-card {
    background: #fff; border-radius: 24px; border: 1px solid #e2e8f0; padding: 28px;
    box-shadow: 0 4px 16px rgba(15,23,42,0.04);
}
.question-text {
    font-size: 1.25rem; font-weight: 700; line-height: 1.5; color: #0f172a; margin-bottom: 24px;
}
.option-item {
    display: flex; align-items: center; gap: 14px; padding: 14px 18px; margin-bottom: 10px;
    border-radius: 14px; border: 1.5px solid #e2e8f0; background: #fff; cursor: pointer;
    transition: all 0.2s ease-out;
}
.option-item:hover { border-color: #a5b4fc; background: #f8f7ff; }
.option-item.selected { border-color: #5B5FEF; background: #eef2ff; box-shadow: 0 0 0 3px rgba(91,95,239,0.12); }
.option-radio {
    width: 20px; height: 20px; border-radius: 50%; border: 2px solid #cbd5e1; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; transition: all 0.2s;
}
.option-item.selected .option-radio { border-color: #5B5FEF; background: #5B5FEF; }
.option-radio-inner { width: 8px; height: 8px; border-radius: 50%; background: #fff; opacity: 0; transition: opacity 0.2s; }
.option-item.selected .option-radio-inner { opacity: 1; }
.option-text { font-size: 0.95rem; font-weight: 500; color: #1e293b; }
.option-item.selected .option-text { color: #0f172a; font-weight: 600; }
.option-letter {
    width: 28px; height: 28px; border-radius: 8px; background: #f1f5f9; color: #64748b;
    display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; flex-shrink: 0;
    transition: all 0.2s;
}
.option-item.selected .option-letter { background: #5B5FEF; color: #fff; }
</style>

<?php if ($guestMode): ?>
<script src="<?= BASE_URL ?>/assets/js/assessment-engine.js"></script>
<script>
(function () {
    'use strict';

    var assessmentId = <?= (int)$assessmentId ?>;

    function init() {
        if (!window.assessmentEngine) {
            console.error('Assessment engine not loaded');
            return;
        }
        window.assessmentEngine.setGuestMode(true);
        setTimeout(function () {
            window.assessmentEngine.startAssessment(assessmentId);
        }, 50);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
<?php else: ?>
<script src="<?= BASE_URL ?>/assets/js/assessment-engine.js"></script>
<?php endif; ?>

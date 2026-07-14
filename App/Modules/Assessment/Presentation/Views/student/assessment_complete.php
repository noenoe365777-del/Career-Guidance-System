<div class="assessment-complete-page">
    <div class="complete-card">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 text-emerald-500 mb-5">
                <i class="bi bi-check-circle-fill text-4xl"></i>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Assessment Completed</h2>
            <p class="mt-2 text-slate-500 text-sm sm:text-base" id="completeAssessmentName">Your assessment has been submitted successfully.</p>
        </div>

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="score-stat-card">
                <span class="score-stat-label">Your Score</span>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="score-stat-value text-indigo-600" id="finalScore">0</span>
                    <span class="text-sm font-semibold text-slate-400">%</span>
                </div>
            </div>
            <div class="score-stat-card">
                <span class="score-stat-label">Completed</span>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="score-stat-value text-emerald-600" id="finalAnswered">0</span>
                    <span class="text-sm font-semibold text-slate-400">/ <span id="finalTotal">0</span></span>
                </div>
            </div>
            <div class="score-stat-card">
                <span class="score-stat-label">Time Taken</span>
                <div class="mt-2">
                    <span class="score-stat-value text-amber-600" id="finalTime">0 sec</span>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div class="flex items-center justify-between text-sm font-semibold text-slate-500 mb-2">
                <span>Overall Score</span>
                <span id="scorePercentLabel">0%</span>
            </div>
            <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                <div id="scoreBar" class="h-full rounded-full transition-all duration-1000 ease-out" style="width:0%"></div>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="<?= BASE_URL ?>/index.php?page=student-assessments"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 transition-all hover:bg-slate-50 active:scale-[0.97] no-underline">
                <i class="bi bi-grid"></i> Back to Dashboard
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=recommendation"
               class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-bold text-white transition-all active:scale-[0.97] no-underline"
               style="background:linear-gradient(135deg,#5B5FEF,#7B7FF5)">
                View Career Recommendation <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<style>
@keyframes scaleIn { from { opacity:0; transform: scale(0.92); } to { opacity:1; transform: scale(1); } }
@keyframes fadeUp { from { opacity:0; transform: translateY(16px); } to { opacity:1; transform: translateY(0); } }
.complete-card {
    background: #fff; border-radius: 28px; border: 1px solid #e2e8f0; padding: 36px;
    box-shadow: 0 8px 30px rgba(15,23,42,0.06);
    animation: scaleIn 0.5s cubic-bezier(0.22,1,0.36,1) both;
}
.score-stat-card {
    background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0; padding: 20px; text-align: center;
    animation: fadeUp 0.4s ease-out both;
}
.score-stat-card:nth-child(1) { animation-delay: 0.1s; }
.score-stat-card:nth-child(2) { animation-delay: 0.2s; }
.score-stat-card:nth-child(3) { animation-delay: 0.3s; }
.score-stat-label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; }
.score-stat-value { font-size: 32px; font-weight: 800; line-height: 1; }
</style>

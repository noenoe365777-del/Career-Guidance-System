<?php
$pageTitle = $pageTitle ?? 'Reports';
$activeMenu = $activeMenu ?? 'reports';
$totalStudents = $totalStudents ?? 0;
$assessmentCompletions = $assessmentCompletions ?? 0;
$totalRecommendations = $totalRecommendations ?? 0;
$reportsGenerated = $reportsGenerated ?? 0;
$assessmentStats = $assessmentStats ?? [];
$topCareers = $topCareers ?? [];
$educationDistribution = $educationDistribution ?? [];

ob_start();
if (file_exists(__DIR__ . '/../partials/summary_stat_card.php')) {
    include __DIR__ . '/../partials/summary_stat_card.php';
}
$reportCardDefs = [
    ['key' => 'students', 'label' => 'Total Students', 'count' => $totalStudents, 'icon' => 'bi-people', 'bg' => '#eef2ff', 'color' => '#5B5FEF', 'hint' => 'Registered students'],
    ['key' => 'completions', 'label' => 'Assessment Completions', 'count' => $assessmentCompletions, 'icon' => 'bi-check-circle', 'bg' => '#ecfdf5', 'color' => '#059669', 'hint' => 'Total completed'],
    ['key' => 'recommendations', 'label' => 'Total Recommendations', 'count' => $totalRecommendations, 'icon' => 'bi-star', 'bg' => '#fffbeb', 'color' => '#d97706', 'hint' => 'Careers recommended'],
    ['key' => 'reports', 'label' => 'Reports Generated', 'count' => $reportsGenerated, 'icon' => 'bi-file-text', 'bg' => '#f3e8ff', 'color' => '#7c3aed', 'hint' => 'Students assessed'],
];
?>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes slideUpCard { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes iconBounce { 0% { transform: scale(1); } 25% { transform: scale(1.25) rotate(-5deg); } 50% { transform: scale(0.9) rotate(3deg); } 75% { transform: scale(1.1) rotate(-2deg); } 100% { transform: scale(1) rotate(0deg); } }
    @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

    .page-in { animation: fadeIn 0.5s ease-out both; }
    .card-in { animation: slideUpCard 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    .anim-in { animation: fadeIn 0.4s ease-out both; }
    .anim-scale { animation: scaleIn 0.25s ease-out both; }

    .d1 { animation-delay: 0.05s; }
    .d2 { animation-delay: 0.10s; }
    .d3 { animation-delay: 0.15s; }
    .d4 { animation-delay: 0.20s; }
    .d5 { animation-delay: 0.25s; }
    .d6 { animation-delay: 0.30s; }
    .d7 { animation-delay: 0.35s; }
    .d8 { animation-delay: 0.40s; }
    .d9 { animation-delay: 0.45s; }
    .d10 { animation-delay: 0.50s; }

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
    .stat-card.active .card-number { color: #5B5FEF !important; }
    .card-icon-bg { transition: transform 0.3s ease-out, background-color 0.3s ease-out, color 0.3s ease-out; }
    .card-number { transition: transform 0.3s ease-out; }
    .card-icon-bg.bounce { animation: iconBounce 0.5s cubic-bezier(0.22,1,0.36,1); }

    .assess-card {
        transition: all 0.2s ease;
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid #f1f5f9;
        padding: 1.25rem;
    }
    .assess-card:hover {
        box-shadow: 0 8px 20px -6px rgba(0,0,0,0.04);
    }

    .career-row {
        transition: all 0.15s ease;
        padding: 0.75rem;
        border-radius: 0.75rem;
        border: 1px solid #f1f5f9;
        background: #ffffff;
    }
    .career-row:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .edu-chip {
        padding: 0.6rem 1rem;
        border-radius: 0.75rem;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .progress-track {
        height: 0.5rem;
        border-radius: 9999px;
        background: #f1f5f9;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 1s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        border-radius: 0.65rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.15s ease;
        cursor: pointer;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-export:hover { background: #f1f5f9; border-color: #cbd5e1; transform: scale(1.03); }
    .btn-export:active { transform: scale(0.97); }
    .btn-export.primary { background: #6366f1; color: #fff; border-color: #6366f1; }
    .btn-export.primary:hover { background: #4f46e5; }

    .section-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
    }
</style>

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8 space-y-8">

    <!-- Header -->
    <div class="anim-up d1">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; letter-spacing: -0.02em;" class="text-slate-900">Reports</h1>
                <p style="font-size: 0.95rem;" class="mt-1.5 text-slate-500">Monitor student assessments and career recommendations.</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="btn-export"><i class="bi bi-filetype-pdf"></i> Export PDF</button>
                <button onclick="exportExcel()" class="btn-export"><i class="bi bi-file-earmark-spreadsheet"></i> Export Excel</button>
                <button onclick="generateReport()" class="btn-export primary"><i class="bi bi-file-earmark-plus"></i> Generate Report</button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4" style="gap: 24px;">
        <?php foreach ($reportCardDefs as $i => $cd):
            $delayClass = 'd' . ($i + 1);
            $counterId = 'reportCount' . ucfirst($cd['key']);
            renderAdminSummaryCard([
                'title' => $cd['label'],
                'value' => '0',
                'valueNumber' => (int)($cd['count'] ?? 0),
                'counterId' => $counterId,
                'icon' => $cd['icon'],
                'iconBg' => $cd['bg'],
                'iconColor' => $cd['color'],
                'hint' => $cd['hint'] ?? '',
                'delayClass' => $delayClass,
                'filter' => $cd['key'],
                'active' => false,
                'extraClass' => '',
            ]);
        endforeach; ?>
    </div>

    <!-- Assessment Completion -->
    <div class="anim-up d6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 style="font-size: 1.15rem; font-weight: 600;" class="text-slate-800">Assessment Completion</h2>
                <p style="font-size: 0.85rem;" class="text-slate-400 mt-0.5">Completed students per assessment type</p>
            </div>
        </div>
        <?php if (empty($assessmentStats)): ?>
        <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-12 text-center">
            <div class="mx-auto h-12 w-12 rounded-xl bg-slate-50 flex items-center justify-center"><i class="bi bi-journal-check text-xl text-slate-300"></i></div>
            <p style="font-size: 0.9rem;" class="mt-3 text-slate-500">No completion data available.</p>
        </div>
        <?php else: ?>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <?php
            $icons = ['bi bi-activity', 'bi bi-person-badge', 'bi bi-cpu', 'bi bi-heart'];
            $colors = ['#10b981', '#6366f1', '#06b6d4', '#f59e0b'];
            $bgColors = ['from-emerald-500 to-emerald-600', 'from-indigo-500 to-indigo-600', 'from-cyan-500 to-cyan-600', 'from-amber-500 to-amber-600'];
            $maxCompleted = max(array_map(fn($a) => (int)($a['completed'] ?? 0), $assessmentStats) ?: [1]);
            foreach ($assessmentStats as $idx => $as):
                $title = (string)($as['title'] ?? '');
                $completed = (int)($as['completed'] ?? 0);
                $taken = (int)($as['total_taken'] ?? 0);
                $rate = (float)($as['completion_rate'] ?? 0);
                $pct = $maxCompleted > 0 ? round(($completed / $maxCompleted) * 100) : 0;
            ?>
            <div class="assess-card anim-up d<?= 7 + $idx ?>" style="animation-delay: <?= 0.06 * $idx ?>s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br <?= $bgColors[$idx % count($bgColors)] ?> text-white shadow-sm">
                        <i class="<?= $icons[$idx % count($icons)] ?> text-sm"></i>
                    </div>
                    <h3 style="font-size: 0.95rem; font-weight: 600;" class="text-slate-800"><?= htmlspecialchars($title) ?></h3>
                </div>
                <div class="flex items-end justify-between gap-2 mb-3">
                    <div>
                        <p style="font-size: 1.75rem; font-weight: 700;" class="text-slate-900"><?= number_format($completed) ?></p>
                        <p style="font-size: 0.8rem;" class="text-slate-400">Completed</p>
                    </div>
                    <div class="text-right">
                        <p style="font-size: 1rem; font-weight: 600;" class="text-slate-600"><?= number_format($taken) ?></p>
                        <p style="font-size: 0.75rem;" class="text-slate-400">Started</p>
                    </div>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width: 0%; background: <?= $colors[$idx % count($colors)] ?>;" data-width="<?= $pct ?>"></div>
                </div>
                <p style="font-size: 0.75rem;" class="mt-1.5 text-slate-400"><?= number_format($rate, 1) ?>% completion rate</p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bottom Grid: Top Careers + Education Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Most Recommended Careers -->
        <div class="anim-up d8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 style="font-size: 1.15rem; font-weight: 600;" class="text-slate-800">Most Recommended Careers</h2>
                    <p style="font-size: 0.85rem;" class="text-slate-400 mt-0.5">Top 5 careers by recommendation count</p>
                </div>
            </div>
            <?php if (empty($topCareers)): ?>
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center">
                <div class="mx-auto h-12 w-12 rounded-xl bg-slate-50 flex items-center justify-center"><i class="bi bi-briefcase text-xl text-slate-300"></i></div>
                <p style="font-size: 0.9rem;" class="mt-3 text-slate-500">No career recommendations yet.</p>
            </div>
            <?php else:
                $maxRec = max(array_map(fn($c) => (int)($c['recommendation_count'] ?? 0), $topCareers));
                $rankColors = ['#6366f1', '#818cf8', '#a5b4fc', '#c7d2fe', '#e0e7ff'];
            ?>
            <div class="space-y-2.5">
                <?php foreach ($topCareers as $i => $tc):
                    $name = htmlspecialchars((string)($tc['career_name'] ?? ''));
                    $count = (int)($tc['recommendation_count'] ?? 0);
                    $score = (float)($tc['avg_score'] ?? 0);
                    $barWidth = $maxRec > 0 ? round(($count / $maxRec) * 100) : 0;
                ?>
                <div class="career-row anim-up d<?= 9 + min($i, 2) ?>" style="animation-delay: <?= 0.05 * $i ?>s;">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <span style="font-size: 0.8rem; font-weight: 700; color: <?= $rankColors[$i % count($rankColors)] ?>;" class="w-5 shrink-0">#<?= $i + 1 ?></span>
                            <h4 style="font-size: 0.95rem; font-weight: 600;" class="text-slate-800 truncate"><?= $name ?></h4>
                        </div>
                        <span style="font-size: 0.85rem; font-weight: 600;" class="text-indigo-600 shrink-0"><?= $count ?> student<?= $count !== 1 ? 's' : '' ?></span>
                    </div>
                    <div class="progress-track ml-8">
                        <div class="progress-fill" style="width: 0%; background: <?= $rankColors[$i % count($rankColors)] ?>;" data-width="<?= $barWidth ?>"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Education Level Distribution -->
        <div class="anim-up d9">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 style="font-size: 1.15rem; font-weight: 600;" class="text-slate-800">Education Level Distribution</h2>
                    <p style="font-size: 0.85rem;" class="text-slate-400 mt-0.5">Students by education level</p>
                </div>
            </div>
            <?php if (empty($educationDistribution)): ?>
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center">
                <div class="mx-auto h-12 w-12 rounded-xl bg-slate-50 flex items-center justify-center"><i class="bi bi-mortarboard text-xl text-slate-300"></i></div>
                <p style="font-size: 0.9rem;" class="mt-3 text-slate-500">No student profile data available.</p>
            </div>
            <?php
                else:
                $maxEdu = max(array_map(fn($e) => (int)($e['count'] ?? 0), $educationDistribution));
            ?>
            <div class="space-y-3">
                <?php foreach ($educationDistribution as $ed):
                    $level = htmlspecialchars((string)($ed['education_level'] ?? ''));
                    $count = (int)($ed['count'] ?? 0);
                    $pct = $maxEdu > 0 ? round(($count / $maxEdu) * 100) : 0;
                ?>
                <div class="edu-chip anim-up d10">
                    <span style="font-size: 0.95rem; font-weight: 500;" class="text-slate-700"><?= $level ?></span>
                    <div class="flex items-center gap-3">
                        <div class="progress-track w-32 sm:w-40">
                            <div class="progress-fill" style="width: 0%; background: #6366f1;" data-width="<?= $pct ?>"></div>
                        </div>
                        <span style="font-size: 0.95rem; font-weight: 600;" class="text-slate-800 w-10 text-right"><?= number_format($count) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<script>
function exportExcel() {
    var rows = [
        ['Report', 'Metric', 'Value'],
        ['Summary', 'Total Students', '<?= $totalStudents ?>'],
        ['Summary', 'Assessment Completions', '<?= $assessmentCompletions ?>'],
        ['Summary', 'Total Recommendations', '<?= $totalRecommendations ?>'],
        ['Summary', 'Reports Generated', '<?= $reportsGenerated ?>'],
        [],
        ['Assessment', 'Completed', 'Started', 'Completion Rate'],
        <?php foreach ($assessmentStats as $as): ?>
        ['<?= str_replace("'", "\\'", $as['title'] ?? '') ?>', '<?= (int)($as['completed'] ?? 0) ?>', '<?= (int)($as['total_taken'] ?? 0) ?>', '<?= (float)($as['completion_rate'] ?? 0) ?>%'],
        <?php endforeach; ?>
        [],
        ['Career', 'Recommended Students', 'Avg Score'],
        <?php foreach ($topCareers as $tc): ?>
        ['<?= str_replace("'", "\\'", $tc['career_name'] ?? '') ?>', '<?= (int)($tc['recommendation_count'] ?? 0) ?>', '<?= number_format((float)($tc['avg_score'] ?? 0), 1) ?>%'],
        <?php endforeach; ?>
        [],
        ['Education Level', 'Students'],
        <?php foreach ($educationDistribution as $ed): ?>
        ['<?= str_replace("'", "\\'", $ed['education_level'] ?? '') ?>', '<?= (int)($ed['count'] ?? 0) ?>'],
        <?php endforeach; ?>
    ];
    var csv = rows.map(function(r) {
        return r.map(function(c) {
            var s = String(c);
            return s.includes(',') || s.includes('"') || s.includes('\n') ? '"' + s.replace(/"/g, '""') + '"' : s;
        }).join(',');
    }).join('\n');
    var blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'career_guidance_report.csv';
    a.click();
    URL.revokeObjectURL(a.href);
}

function generateReport() {
    var toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;bottom:2rem;right:2rem;background:#1e293b;color:#fff;padding:0.75rem 1.25rem;border-radius:0.75rem;font-size:0.9rem;font-weight:500;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,0.15);animation:fadeIn 0.3s ease-out;';
    toast.textContent = 'Report generated successfully.';
    document.body.appendChild(toast);
    setTimeout(function() { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(function() { toast.remove(); }, 300); }, 2500);
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.querySelectorAll('.progress-fill').forEach(function(bar) {
            var w = bar.getAttribute('data-width');
            if (w) bar.style.width = w + '%';
        });
    }, 300);
});

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

<style>
@media print {
    .btn-export { display: none !important; }
    .stat-card, .assess-card, .career-row, .edu-chip { break-inside: avoid; border: 1px solid #e2e8f0 !important; }
    body { font-size: 11px; }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

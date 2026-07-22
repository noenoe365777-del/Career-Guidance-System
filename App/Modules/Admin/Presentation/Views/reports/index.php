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
?>

<style>
    :root {
        --indigo: #6366f1;
        --indigo-light: #eef2ff;
        --emerald: #10b981;
        --emerald-light: #ecfdf5;
        --amber: #f59e0b;
        --amber-light: #fffbeb;
        --purple: #8b5cf6;
        --purple-light: #f3e8ff;
        --cyan: #06b6d4;
        --cyan-light: #ecfeff;
    }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes countUp {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes fillBar {
        from { width: 0; }
    }
    @keyframes donutFill {
        from { stroke-dashoffset: var(--circumference); }
    }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 4px 6px -1px rgba(99,102,241,0.1); }
        50% { box-shadow: 0 8px 24px -4px rgba(99,102,241,0.2); }
    }

    .anim-card { animation: fadeSlideUp 0.5s cubic-bezier(0.16,1,0.3,1) both; }
    .anim-scale { animation: scaleIn 0.4s cubic-bezier(0.16,1,0.3,1) both; }
    .anim-delay-1 { animation-delay: 0.05s; }
    .anim-delay-2 { animation-delay: 0.10s; }
    .anim-delay-3 { animation-delay: 0.15s; }
    .anim-delay-4 { animation-delay: 0.20s; }
    .anim-delay-5 { animation-delay: 0.25s; }
    .anim-delay-6 { animation-delay: 0.30s; }
    .anim-delay-7 { animation-delay: 0.35s; }
    .anim-delay-8 { animation-delay: 0.40s; }

    .kpi-card {
        border-radius: 18px;
        background: #fff;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(15,23,42,0.04), 0 2px 4px -2px rgba(15,23,42,0.03);
        transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
        will-change: transform, box-shadow;
    }
    .kpi-card:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 20px 40px -12px rgba(99,102,241,0.18);
        border-color: rgba(99,102,241,0.2);
    }
    .kpi-card:active { transform: scale(0.98); }

    .donut-ring-bg { fill: none; stroke: #f1f5f9; stroke-width: 7; }
    .donut-ring-fg {
        fill: none;
        stroke-width: 7;
        stroke-linecap: round;
        transform: rotate(-90deg);
        transform-origin: center;
        transition: stroke-dashoffset 1.2s cubic-bezier(0.16,1,0.3,1);
    }
    .donut-ring-fg.animated { animation: donutFill 1.2s cubic-bezier(0.16,1,0.3,1) both; }

    .assess-card {
        border-radius: 18px;
        background: #fff;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(15,23,42,0.04);
        transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .assess-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px -8px rgba(99,102,241,0.12);
        border-color: rgba(99,102,241,0.15);
    }

    .filter-chip {
        padding: 0.4rem 1rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .filter-chip:hover { background: #f8fafc; border-color: #cbd5e1; }
    .filter-chip.active {
        background: var(--indigo);
        color: #fff;
        border-color: var(--indigo);
        box-shadow: 0 2px 8px -2px rgba(99,102,241,0.4);
    }

    .filter-group-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
    }

    .bar-chart-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 0.75rem;
        border-radius: 10px;
        transition: background 0.2s ease;
    }
    .bar-chart-row:hover { background: #f8fafc; }

    .bar-track {
        flex: 1;
        height: 28px;
        background: #f1f5f9;
        border-radius: 9999px;
        overflow: hidden;
        position: relative;
    }
    .bar-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 1s cubic-bezier(0.16,1,0.3,1);
        position: relative;
    }
    .bar-fill.animated { animation: fillBar 1s cubic-bezier(0.16,1,0.3,1) both; }

    .reports-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .reports-table th {
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        text-align: left;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .reports-table th:first-child { border-radius: 12px 0 0 0; }
    .reports-table th:last-child { border-radius: 0 12px 0 0; text-align: right; }
    .reports-table td {
        padding: 0.85rem 1rem;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .reports-table tr:last-child td { border-bottom: none; }
    .reports-table tr:hover td { background: #f8fafc; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.65rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-badge.completed { background: #ecfdf5; color: #059669; }
    .status-badge.pending { background: #fffbeb; color: #d97706; }
    .status-badge.in-progress { background: #eef2ff; color: #4f46e5; }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.15s ease;
        cursor: pointer;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        text-decoration: none;
    }
    .btn-action:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .btn-action.primary { background: var(--indigo); color: #fff; border-color: var(--indigo); }
    .btn-action.primary:hover { background: #4f46e5; }

    .btn-export-dropdown {
        position: relative;
        display: inline-block;
    }
    .export-menu {
        position: absolute;
        top: calc(100% + 4px);
        right: 0;
        min-width: 160px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 12px 32px -8px rgba(15,23,42,0.12);
        z-index: 50;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-4px);
        transition: all 0.2s ease;
    }
    .export-menu.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .export-menu-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.65rem 1rem;
        font-size: 0.85rem;
        color: #334155;
        cursor: pointer;
        transition: background 0.15s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }
    .export-menu-item:hover { background: #f8fafc; }
    .export-menu-item:not(:last-child) { border-bottom: 1px solid #f1f5f9; }

    .section-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(15,23,42,0.04);
        overflow: hidden;
    }

    @media (max-width: 640px) {
        .assess-card { padding: 1.25rem !important; }
        .filter-row { flex-direction: column; align-items: stretch !important; }
    }
</style>

<div class="max-w-[1440px] mx-auto">

    <!-- ============================================================ -->
    <!-- Hero Section                                                  -->
    <!-- ============================================================ -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 anim-card anim-delay-1">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight" style="letter-spacing: -0.02em;">
                Reports Dashboard
            </h1>
            <p class="text-sm sm:text-base text-slate-500 mt-1.5 max-w-lg">
                Monitor student assessments and career recommendations.
            </p>
        </div>
        <div class="flex items-center gap-2.5 shrink-0">
            <button onclick="generateReport()" class="btn-action primary text-sm px-4 py-2.5">
                <i class="bi bi-file-earmark-plus"></i>
                Generate Report
            </button>
            <div class="btn-export-dropdown">
                <button id="exportDropdownBtn" class="btn-action text-sm px-4 py-2.5" onclick="toggleExportMenu()">
                    <i class="bi bi-download"></i>
                    Export
                    <i class="bi bi-chevron-down text-[10px] ml-0.5"></i>
                </button>
                <div id="exportMenu" class="export-menu">
                    <button class="export-menu-item" onclick="handleExport('pdf')">
                        <i class="bi bi-filetype-pdf text-red-500"></i>
                        PDF
                    </button>
                    <button class="export-menu-item" onclick="handleExport('excel')">
                        <i class="bi bi-file-earmark-excel text-emerald-600"></i>
                        Excel
                    </button>
                    <button class="export-menu-item" onclick="handleExport('csv')">
                        <i class="bi bi-filetype-csv text-blue-500"></i>
                        CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- Summary Cards (shared component - shorter height)              -->
    <!-- ============================================================ -->
    <?php
    if (file_exists(__DIR__ . '/../partials/summary_stat_card.php')) {
        include __DIR__ . '/../partials/summary_stat_card.php';
    }
    ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php renderAdminSummaryCard(['title' => 'Total Students', 'value' => '0', 'valueNumber' => (int)$totalStudents, 'counterId' => 'kpiCountStudents', 'icon' => 'bi-people-fill', 'iconBg' => '#eef2ff', 'iconColor' => '#5B5FEF', 'hint' => 'Registered Students', 'delayClass' => 'd1']); ?>
        <?php renderAdminSummaryCard(['title' => 'Completed Assessments', 'value' => '0', 'valueNumber' => (int)$assessmentCompletions, 'counterId' => 'kpiCountCompleted', 'icon' => 'bi-check-circle-fill', 'iconBg' => '#ecfdf5', 'iconColor' => '#059669', 'hint' => 'Finished Assessments', 'delayClass' => 'd2']); ?>
        <?php renderAdminSummaryCard(['title' => 'Total Recommendations', 'value' => '0', 'valueNumber' => (int)$totalRecommendations, 'counterId' => 'kpiCountRecommendations', 'icon' => 'bi-star-fill', 'iconBg' => '#fffbeb', 'iconColor' => '#d97706', 'hint' => 'Generated Recommendations', 'delayClass' => 'd3']); ?>
        <?php renderAdminSummaryCard(['title' => 'Reports Generated', 'value' => '0', 'valueNumber' => (int)$reportsGenerated, 'counterId' => 'kpiCountReports', 'icon' => 'bi-file-text-fill', 'iconBg' => '#f3e8ff', 'iconColor' => '#9333ea', 'hint' => 'Exported Reports', 'delayClass' => 'd4']); ?>
    </div>

    <!-- ============================================================ -->
    <!-- Assessment Completion - Donut Charts                          -->
    <!-- ============================================================ -->
    <div class="section-card p-5 sm:p-6 mb-8 anim-card anim-delay-5">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-slate-800">Assessment Completion</h2>
                <p class="text-sm text-slate-400 mt-0.5">Completed students per assessment type</p>
            </div>
        </div>
        <?php if (empty($assessmentStats)): ?>
        <div class="flex flex-col items-center justify-center py-12">
            <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center mb-4">
                <i class="bi bi-journal-check text-2xl text-slate-300"></i>
            </div>
            <p class="text-sm text-slate-400">No completion data available.</p>
        </div>
        <?php else:
            $donutColors = ['#10b981', '#6366f1', '#06b6d4', '#f59e0b'];
            $donutBgColors = ['#ecfdf5', '#eef2ff', '#ecfeff', '#fffbeb'];
            $donutIcons = ['bi-emoji-smile', 'bi-person-badge', 'bi-cpu', 'bi-heart'];
        ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php foreach ($assessmentStats as $idx => $as):
                $title = htmlspecialchars((string)($as['title'] ?? ''));
                $completed = (int)($as['completed'] ?? 0);
                $taken = (int)($as['total_taken'] ?? 0);
                $rate = (float)($as['completion_rate'] ?? 0);
                $pct = $taken > 0 ? round(($completed / $taken) * 100) : 0;
                $color = $donutColors[$idx % count($donutColors)];
                $bgColor = $donutBgColors[$idx % count($donutBgColors)];
                $icon = $donutIcons[$idx % count($donutIcons)];
                $r = 44;
                $circ = 2 * 3.14159 * $r;
                $offset = $circ * (1 - $pct / 100);
            ?>
            <div class="assess-card anim-card anim-delay-<?= 6 + $idx ?>">
                <div class="relative w-[108px] h-[108px] mb-3">
                    <svg class="w-[108px] h-[108px]" viewBox="0 0 108 108">
                        <circle class="donut-ring-bg" cx="54" cy="54" r="<?= $r ?>"/>
                        <circle class="donut-ring-fg" id="donutFg<?= $idx ?>"
                                cx="54" cy="54" r="<?= $r ?>"
                                stroke="<?= $color ?>"
                                stroke-dasharray="<?= $circ ?>"
                                stroke-dashoffset="<?= $circ ?>"
                                data-offset="<?= $offset ?>"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-lg font-bold text-slate-800 donut-pct" id="donutPct<?= $idx ?>" data-pct="<?= $pct ?>">0%</span>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-slate-700"><?= $title ?></h3>
                <p class="text-xs text-slate-400 mt-0.5"><?= number_format($completed) ?> completed</p>
                <div class="mt-2 px-3 py-1 rounded-full text-xs font-medium" style="background: <?= $bgColor ?>; color: <?= $color ?>;">
                    <?= number_format($rate, 1) ?>% rate
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- ============================================================ -->
    <!-- Filters                                                       -->
    <!-- ============================================================ -->
    <div class="section-card p-5 sm:p-6 mb-8 anim-card anim-delay-6">
        <div class="flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between filter-row">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                <span class="filter-group-label">Date Range</span>
                <div class="flex flex-wrap gap-1.5">
                    <?php
                    $dateFilters = ['Today', 'This Week', 'This Month', 'This Year', 'Custom'];
                    foreach ($dateFilters as $df):
                    ?>
                    <button class="filter-chip <?= $df === 'This Year' ? 'active' : '' ?>" data-filter="date" data-value="<?= strtolower(str_replace(' ', '-', $df)) ?>">
                        <?= $df ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                <span class="filter-group-label">Assessment Type</span>
                <div class="flex flex-wrap gap-1.5">
                    <?php
                    $typeFilters = ['All', 'Personality', 'Interest', 'Aptitude', 'Career Values'];
                    foreach ($typeFilters as $tf):
                    ?>
                    <button class="filter-chip <?= $tf === 'All' ? 'active' : '' ?>" data-filter="type" data-value="<?= strtolower($tf) ?>">
                        <?= $tf ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    
   
<script>
(function() {
    'use strict';

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
            animateCounter(el, target);
        });
    }, 300);

    // ---- Export Dropdown ----
    window.toggleExportMenu = function() {
        var menu = document.getElementById('exportMenu');
        menu.classList.toggle('open');
    };

    window.handleExport = function(format) {
        document.getElementById('exportMenu').classList.remove('open');
        if (format === 'pdf') {
            window.print();
        } else if (format === 'excel' || format === 'csv') {
            exportExcel();
        }
    };

    document.addEventListener('click', function(e) {
        var btn = document.getElementById('exportDropdownBtn');
        var menu = document.getElementById('exportMenu');
        if (btn && menu && !btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('open');
        }
    });

    // ---- Filter Chips ----
    document.querySelectorAll('.filter-chip').forEach(function(chip) {
        chip.addEventListener('click', function() {
            var group = this.getAttribute('data-filter');
            document.querySelectorAll('.filter-chip[data-filter="' + group + '"]').forEach(function(c) {
                c.classList.remove('active');
            });
            this.classList.add('active');
            // Future: connect to backend filtering
        });
    });

    // ---- Counter Animation ----
    function animateCounter(el, target) {
        if (!el) return;
        var current = 0;
        var steps = 40;
        var inc = Math.max(1, Math.ceil(target / steps));
        var timer = setInterval(function() {
            current += inc;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            el.textContent = current.toLocaleString();
        }, 20);
    }

    // ---- Bar Animations ----
    function animateBars() {
        document.querySelectorAll('.bar-anim').forEach(function(bar) {
            var w = bar.getAttribute('data-width');
            if (w) bar.style.width = w + '%';
        });
    }

    // ---- Donut Animations ----
    function animateDonuts() {
        document.querySelectorAll('.donut-ring-fg').forEach(function(circle) {
            var offset = circle.getAttribute('data-offset');
            if (offset != null) {
                setTimeout(function() {
                    circle.style.strokeDashoffset = offset;
                }, 300);
            }
            var pctEl = circle.closest('.relative').querySelector('.donut-pct');
            if (pctEl) {
                var target = parseInt(pctEl.getAttribute('data-pct') || '0', 10);
                animatePct(pctEl, target);
            }
        });
    }

    function animatePct(el, target) {
        var current = 0;
        var timer = setInterval(function() {
            current++;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            el.textContent = current + '%';
        }, 20);
    }

    // ---- KPI Counter Start ----
    function startCounters() {
        document.querySelectorAll('[data-target]').forEach(function(el) {
            var target = parseInt(el.getAttribute('data-target') || '0', 10);
            animateCounter(el, target);
        });
    }

    // ---- Init on DOM ready ----
    function init() {
        startCounters();
        setTimeout(animateBars, 400);
        setTimeout(animateDonuts, 500);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

// ---- Existing Functions (preserved) ----
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
</script>

<style>
@media print {
    .btn-action, .btn-export-dropdown, .filter-chip { display: none !important; }
    .section-card { break-inside: avoid; border: 1px solid #e2e8f0 !important; }
    body { font-size: 11px; }
    .kpi-card { break-inside: avoid; }
    .assess-card { break-inside: avoid; }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

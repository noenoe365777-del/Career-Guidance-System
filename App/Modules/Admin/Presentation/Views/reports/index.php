<?php
$pageTitle = $pageTitle ?? 'Reports & Analytics';
$activeMenu = $activeMenu ?? 'reports';
$period = $period ?? 'all';
$summaryStats = $summaryStats ?? [];
$assessmentStats = $assessmentStats ?? [];
$topCareers = $topCareers ?? [];
$registrationTrend = $registrationTrend ?? [];
$educationDistribution = $educationDistribution ?? [];
$resultTypes = $resultTypes ?? [];
ob_start();
?>

<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 m-0">Reports & Analytics</h1>
            <p class="text-sm text-slate-500 mt-1 m-0">Overview of assessment activity and student data</p>
        </div>
        <div class="flex items-center gap-3">
            <select id="periodSelect" onchange="applyPeriod(this.value)"
                    class="px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none shadow-sm">
                <option value="all" <?= $period === 'all' ? 'selected' : '' ?>>All Time</option>
                <option value="this_year" <?= $period === 'this_year' ? 'selected' : '' ?>>This Year</option>
                <option value="this_month" <?= $period === 'this_month' ? 'selected' : '' ?>>This Month</option>
            </select>
            <button onclick="window.print()" class="px-3 py-2 text-sm font-medium bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                <i class="bi bi-printer"></i> Print
            </button>
            <button onclick="exportCSV()" class="px-3 py-2 text-sm font-medium bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Students</span>
                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="bi bi-people text-base"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-800"><?= htmlspecialchars((string)($summaryStats['total_students'] ?? 0)) ?></div>
            <p class="text-xs text-slate-400 mt-1 m-0">Registered students</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Completed</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="bi bi-check-circle text-base"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-800"><?= htmlspecialchars((string)($summaryStats['completed_assessments'] ?? 0)) ?></div>
            <p class="text-xs text-slate-400 mt-1 m-0">Completed assessments</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Recommendations</span>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="bi bi-star text-base"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-800"><?= htmlspecialchars((string)($summaryStats['total_recommendations'] ?? 0)) ?></div>
            <p class="text-xs text-slate-400 mt-1 m-0">Career recommendations</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Completion Rate</span>
                <div class="w-9 h-9 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center">
                    <i class="bi bi-graph-up text-base"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-800"><?= htmlspecialchars((string)($summaryStats['avg_completion_rate'] ?? 0)) ?>%</div>
            <p class="text-xs text-slate-400 mt-1 m-0">Avg per student</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <h3 class="text-sm font-bold text-slate-700 mb-4">Student Registration Trend</h3>
            <canvas id="registrationChart" height="200"></canvas>
            <?php if (empty($registrationTrend)): ?>
                <p class="text-center text-slate-400 text-sm py-8">No registration data available</p>
            <?php endif; ?>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <h3 class="text-sm font-bold text-slate-700 mb-4">Education Level Distribution</h3>
            <canvas id="educationChart" height="200"></canvas>
            <?php if (empty($educationDistribution)): ?>
                <p class="text-center text-slate-400 text-sm py-8">No education data available</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-100 shadow-sm mb-6">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-700 m-0">Assessment Completion Statistics</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-left">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Assessment</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Type</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 text-center">Total Taken</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 text-center">Completed</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 text-center">Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($assessmentStats)): ?>
                        <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">No data available</td></tr>
                    <?php else: ?>
                        <?php foreach ($assessmentStats as $row): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 font-medium text-slate-700"><?= htmlspecialchars($row['title']) ?></td>
                                <td class="px-5 py-3 text-slate-500"><?= htmlspecialchars($row['assessment_type']) ?></td>
                                <td class="px-5 py-3 text-center text-slate-700"><?= (int)$row['total_taken'] ?></td>
                                <td class="px-5 py-3 text-center text-slate-700"><?= (int)$row['completed'] ?></td>
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?= (float)$row['completion_rate'] >= 70 ? 'bg-emerald-50 text-emerald-700' : ((float)$row['completion_rate'] >= 40 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') ?>">
                                        <?= htmlspecialchars((string)$row['completion_rate']) ?>%
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="p-5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-700 m-0">Top Recommended Careers</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-left">
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Career</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 text-center">Count</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 text-center">Avg Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (empty($topCareers)): ?>
                            <tr><td colspan="3" class="px-5 py-8 text-center text-slate-400">No recommendations yet</td></tr>
                        <?php else: ?>
                            <?php foreach ($topCareers as $row): ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-5 py-3 font-medium text-slate-700"><?= htmlspecialchars($row['career_name']) ?></td>
                                    <td class="px-5 py-3 text-center text-slate-700"><?= (int)$row['recommendation_count'] ?></td>
                                    <td class="px-5 py-3 text-center text-slate-700"><?= htmlspecialchars((string)$row['avg_score']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="p-5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-700 m-0">Most Common Result Types</h3>
            </div>
            <canvas id="resultTypesChart" class="p-4" height="200"></canvas>
            <?php if (empty($resultTypes)): ?>
                <p class="text-center text-slate-400 text-sm py-8">No result data available</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
function applyPeriod(value) {
    const params = new URLSearchParams(window.location.search);
    if (value === 'all') {
        params.delete('period');
    } else {
        params.set('period', value);
    }
    params.set('page', 'admin-reports');
    window.location.search = params.toString();
}

function exportCSV() {
    const rows = [['Report', 'Metric', 'Value']];

    <?php foreach ($summaryStats as $key => $value): ?>
    rows.push(['Summary', '<?= $key ?>', '<?= $value ?>']);
    <?php endforeach; ?>

    rows.push([]);
    rows.push(['Assessment', 'Type', 'Total Taken', 'Completed', 'Rate']);

    <?php foreach ($assessmentStats as $row): ?>
    rows.push(['<?= str_replace("'", "\\'", $row['title']) ?>', '<?= $row['assessment_type'] ?>', '<?= (int)$row['total_taken'] ?>', '<?= (int)$row['completed'] ?>', '<?= $row['completion_rate'] ?>%']);
    <?php endforeach; ?>

    rows.push([]);
    rows.push(['Career', 'Count', 'Avg Score']);

    <?php foreach ($topCareers as $row): ?>
    rows.push(['<?= str_replace("'", "\\'", $row['career_name']) ?>', '<?= (int)$row['recommendation_count'] ?>', '<?= $row['avg_score'] ?>']);
    <?php endforeach; ?>

    const csv = rows.map(r => r.map(c => {
        const s = String(c);
        return s.includes(',') || s.includes('"') || s.includes('\n') ? '"' + s.replace(/"/g, '""') + '"' : s;
    }).join(',')).join('\n');

    const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'career_guidance_report.csv';
    a.click();
    URL.revokeObjectURL(a.href);
}

document.addEventListener('DOMContentLoaded', function () {
    const baseOpts = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: { boxWidth: 12, padding: 12, font: { size: 11 } }
            }
        }
    };

    <?php if (!empty($registrationTrend)): ?>
    new Chart(document.getElementById('registrationChart'), {
        type: 'line',
        data: {
            labels: [<?php foreach ($registrationTrend as $r): ?>'<?= $r['ym'] ?>',<?php endforeach; ?>],
            datasets: [{
                label: 'Registrations',
                data: [<?php foreach ($registrationTrend as $r): ?><?= (int)$r['count'] ?>,<?php endforeach; ?>],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: '#6366f1',
            }]
        },
        options: { ...baseOpts, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    <?php endif; ?>

    <?php if (!empty($educationDistribution)): ?>
    const eduLabels = [<?php foreach ($educationDistribution as $r): ?>'<?= str_replace("'", "\\'", $r['education_level']) ?>',<?php endforeach; ?>];
    const eduData = [<?php foreach ($educationDistribution as $r): ?><?= (int)$r['count'] ?>,<?php endforeach; ?>];
    const eduColors = ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#f97316','#ec4899'];
    new Chart(document.getElementById('educationChart'), {
        type: 'doughnut',
        data: {
            labels: eduLabels,
            datasets: [{
                data: eduData,
                backgroundColor: eduColors.slice(0, eduLabels.length),
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: { ...baseOpts, plugins: { ...baseOpts.plugins, legend: { ...baseOpts.plugins.legend, position: 'bottom' } } }
    });
    <?php endif; ?>

    <?php if (!empty($resultTypes)): ?>
    const rtLabels = [<?php foreach ($resultTypes as $r): ?>'<?= str_replace("'", "\\'", $r['assessment_name']) ?>: <?= str_replace("'", "\\'", $r['type_label']) ?>',<?php endforeach; ?>];
    const rtData = [<?php foreach ($resultTypes as $r): ?><?= (int)$r['count'] ?>,<?php endforeach; ?>];
    const rtColors = ['#6366f1','#818cf8','#a5b4fc','#10b981','#34d399','#6ee7b7','#f59e0b','#fbbf24','#fcd34d','#ef4444','#f87171','#fca5a5'];
    new Chart(document.getElementById('resultTypesChart'), {
        type: 'bar',
        data: {
            labels: rtLabels,
            datasets: [{
                label: 'Count',
                data: rtData,
                backgroundColor: rtColors.slice(0, rtLabels.length),
                borderRadius: 4,
            }]
        },
        options: { ...baseOpts, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { ...baseOpts.plugins, legend: { display: false } } }
    });
    <?php endif; ?>
});
</script>

<style>
@media print {
    nav, aside, .offcanvas, .flex > .hidden, select, button { display: none !important; }
    .max-w-7xl { max-width: 100% !important; }
    .bg-white { border: 1px solid #e2e8f0 !important; break-inside: avoid; }
    .grid { break-inside: avoid; }
    body { font-size: 12px; }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

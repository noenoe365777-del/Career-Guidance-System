<?php
$pageTitle = $pageTitle ?? 'Reports & Analytics';
$activeMenu = $activeMenu ?? 'reports';
$period = $period ?? 'all';
$summaryStats = $summaryStats ?? [];
$assessmentStats = $assessmentStats ?? [];
$topCareers = $topCareers ?? [];
$registrationTrend = $registrationTrend ?? [];
$educationDistribution = $educationDistribution ?? [];
$assessmentCompletionTrend = $assessmentCompletionTrend ?? [];
$studentPerformance = $studentPerformance ?? [];
$recentActivities = $recentActivities ?? [];
ob_start();
?>

<style>
:root { --primary: #6366F1; --bg-page: #F8FAFC; --radius: 16px; }
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(16px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.95); }
    to   { opacity: 1; transform: scale(1); }
}
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}
@keyframes countUp {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-in { animation: fadeInUp 0.5s cubic-bezier(0.22,1,0.36,1) both; }
.d1 { animation-delay: 0.04s; }
.d2 { animation-delay: 0.08s; }
.d3 { animation-delay: 0.12s; }
.d4 { animation-delay: 0.16s; }
.d5 { animation-delay: 0.20s; }
.d6 { animation-delay: 0.24s; }
.d7 { animation-delay: 0.28s; }
.d8 { animation-delay: 0.32s; }
.d9 { animation-delay: 0.36s; }
.d10 { animation-delay: 0.40s; }
.hover-lift { transition: transform 0.25s cubic-bezier(0.22,1,0.36,1), box-shadow 0.25s cubic-bezier(0.22,1,0.36,1); }
.hover-lift:hover { transform: translateY(-3px); box-shadow: 0 12px 30px -8px rgba(99,102,241,0.12); }
.slide-up { animation: slideUp 0.4s cubic-bezier(0.22,1,0.36,1) both; }
.table-row { transition: background 0.15s ease; }
.table-row:nth-child(even) { background: #F8FAFC; }
.table-row:hover { background: #EEF2FF !important; }
.stat-value { animation: countUp 0.6s cubic-bezier(0.22,1,0.36,1) both; }
.sticky-header { position: sticky; top: 0; z-index: 30; }
.progress-bar-fill { transition: width 1.2s cubic-bezier(0.22,1,0.36,1); }
.chart-container { position: relative; }
</style>

<div class="space-y-6" style="background:var(--bg-page);min-height:100vh">

<div class="sticky-header animate-in flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-[var(--bg-page)] py-4 -mx-4 px-4 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Reports & Analytics</h1>
        <p class="mt-1 text-sm text-slate-500">Overview of assessment activity and student performance.</p>
    </div>
    <div class="flex items-center gap-2.5 flex-wrap">
        <select id="periodSelect" onchange="applyPeriod(this.value)"
                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition-all duration-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 shadow-sm">
            <option value="today" <?= $period === 'today' ? 'selected' : '' ?>>Today</option>
            <option value="7d" <?= $period === '7d' ? 'selected' : '' ?>>7 Days</option>
            <option value="30d" <?= $period === '30d' ? 'selected' : '' ?>>30 Days</option>
            <option value="all" <?= $period === 'all' ? 'selected' : '' ?>>All Time</option>
        </select>
        <button onclick="window.print()"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-600 transition-all duration-200 hover:bg-slate-50 hover:shadow-sm active:scale-[0.97]">
            <i class="bi bi-filetype-pdf"></i> Export PDF
        </button>
        <button onclick="exportCSV()"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-600 transition-all duration-200 hover:bg-slate-50 hover:shadow-sm active:scale-[0.97]">
            <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
        </button>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
<?php
$statKeys = ['total_students', 'completed_assessments', 'total_recommendations', 'avg_completion_rate', 'active_users', 'avg_score'];
$cardColors = [
    'total_students' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'icon' => 'bi-people'],
    'completed_assessments' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'icon' => 'bi-check-circle'],
    'total_recommendations' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'icon' => 'bi-star'],
    'avg_completion_rate' => ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-600', 'icon' => 'bi-graph-up'],
    'active_users' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600', 'icon' => 'bi-person-activity'],
    'avg_score' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'icon' => 'bi-trophy'],
];
$dl = 1;
foreach ($statKeys as $sk):
    $s = $summaryStats[$sk] ?? ['value' => 0, 'change' => 0, 'label' => $sk, 'format' => 'number'];
    $val = $s['value'];
    $change = $s['change'];
    $label = $s['label'];
    $fmt = $s['format'];
    $cc = $cardColors[$sk];
    $displayVal = match($fmt) {
        'percent' => number_format((float)$val, 1) . '%',
        'decimal' => number_format((float)$val, 2),
        default => number_format((int)$val),
    };
    $changeIsGood = $change >= 0;
    $changeClass = $changeIsGood ? 'text-emerald-600 bg-emerald-50' : 'text-red-600 bg-red-50';
    $changeIcon = $changeIsGood ? 'bi-arrow-up' : 'bi-arrow-down';
    $dly = 'd' . $dl;
?>
    <div class="animate-in <?= $dly ?> hover-lift rounded-[var(--radius)] bg-white p-4 shadow-[0_1px_3px_rgba(0,0,0,0.06)] flex flex-col justify-between stat-hover">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400"><?= htmlspecialchars($label) ?></span>
            <div class="flex h-8 w-8 items-center justify-center rounded-lg <?= $cc['bg'] ?> <?= $cc['text'] ?> transition-all duration-300">
                <i class="bi <?= $cc['icon'] ?> text-sm"></i>
            </div>
        </div>
        <div class="stat-value text-xl font-bold text-slate-900"><?= $displayVal ?></div>
        <div class="mt-1.5 flex items-center gap-1.5">
            <?php if ($period !== 'all'): ?>
            <span class="inline-flex items-center gap-0.5 rounded-full px-1.5 py-0.5 text-[10px] font-semibold <?= $changeClass ?>">
                <i class="bi <?= $changeIcon ?> text-[9px]"></i>
                <?= $changeIsGood ? '+' : '' ?><?= number_format(abs($change), 1) ?>%
            </span>
            <span class="text-[10px] text-slate-400">vs prev</span>
            <?php else: ?>
            <span class="text-[10px] text-slate-400">All time total</span>
            <?php endif; ?>
        </div>
    </div>
<?php $dl++; endforeach; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="animate-in d7 rounded-[var(--radius)] bg-white p-5 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
        <h3 class="text-sm font-bold text-slate-700 mb-4">Student Registration Trend</h3>
        <div class="chart-container">
            <canvas id="registrationChart" height="220"></canvas>
        </div>
        <?php if (empty($registrationTrend)): ?>
        <div class="flex items-center justify-center h-[220px] text-slate-400 text-sm">No registration data available</div>
        <?php endif; ?>
    </div>
    <div class="animate-in d8 rounded-[var(--radius)] bg-white p-5 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
        <h3 class="text-sm font-bold text-slate-700 mb-4">Assessment Completion Trend</h3>
        <div class="chart-container">
            <canvas id="completionTrendChart" height="220"></canvas>
        </div>
        <?php if (empty($assessmentCompletionTrend)): ?>
        <div class="flex items-center justify-center h-[220px] text-slate-400 text-sm">No completion data available</div>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="animate-in d9 rounded-[var(--radius)] bg-white p-5 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
        <h3 class="text-sm font-bold text-slate-700 mb-4">Most Popular Assessments</h3>
        <?php if (empty($assessmentStats)): ?>
        <div class="flex items-center justify-center h-[200px] text-slate-400 text-sm">No assessment data</div>
        <?php else: ?>
        <div class="space-y-4">
            <?php
            $maxRate = 0;
            foreach ($assessmentStats as $a) { $r = (float)$a['completion_rate']; if ($r > $maxRate) $maxRate = $r; }
            $maxRate = $maxRate > 0 ? $maxRate : 100;
            foreach ($assessmentStats as $a):
                $title = htmlspecialchars((string)($a['title'] ?? ''));
                $rate = (float)$a['completion_rate'];
                $pct = $maxRate > 0 ? round(($rate / $maxRate) * 100) : 0;
                $t = strtolower($title);
                if (str_contains($t, 'personality')) { $bar = 'bg-indigo-500'; $dot = 'bg-indigo-500'; }
                elseif (str_contains($t, 'interest')) { $bar = 'bg-pink-500'; $dot = 'bg-pink-500'; }
                elseif (str_contains($t, 'aptitude')) { $bar = 'bg-emerald-500'; $dot = 'bg-emerald-500'; }
                elseif (str_contains($t, 'value')) { $bar = 'bg-amber-500'; $dot = 'bg-amber-500'; }
                else { $bar = 'bg-slate-500'; $dot = 'bg-slate-500'; }
            ?>
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="inline-block h-2 w-2 rounded-full <?= $dot ?>"></span>
                        <span class="text-xs font-medium text-slate-700"><?= $title ?></span>
                    </div>
                    <span class="text-xs font-bold text-slate-800"><?= number_format($rate, 1) ?>%</span>
                </div>
                <div class="h-2.5 w-full rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full <?= $bar ?> progress-bar-fill" style="width:0%" data-width="<?= $pct ?>"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="animate-in d10 rounded-[var(--radius)] bg-white p-5 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
        <h3 class="text-sm font-bold text-slate-700 mb-4">Education Level Distribution</h3>
        <div class="chart-container">
            <canvas id="educationChart" height="220"></canvas>
        </div>
        <?php if (empty($educationDistribution)): ?>
        <div class="flex items-center justify-center h-[220px] text-slate-400 text-sm">No education data</div>
        <?php endif; ?>
    </div>
</div>

<div class="animate-in d3 rounded-[var(--radius)] bg-white shadow-[0_1px_3px_rgba(0,0,0,0.06)] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div>
            <h3 class="text-sm font-bold text-slate-700">Student Performance</h3>
            <p class="text-xs text-slate-400 mt-0.5">Latest completed assessments</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Student</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Assessment</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 hidden sm:table-cell">Date</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">Score</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">Career Match</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($studentPerformance)): ?>
                <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">No performance data yet</td></tr>
                <?php else: ?>
                <?php foreach ($studentPerformance as $sp): ?>
                <tr class="table-row border-b border-slate-50 slide-up" style="animation-delay:<?= (float)(0.02 * min(10, array_search($sp, $studentPerformance) + 1)) ?>s">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-xs font-semibold text-indigo-600 shrink-0">
                                <?= strtoupper(substr(htmlspecialchars((string)($sp['student_name'] ?? '')), 0, 1)) ?>
                            </div>
                            <span class="text-sm font-medium text-slate-700"><?= htmlspecialchars((string)($sp['student_name'] ?? '')) ?></span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-slate-600"><?= htmlspecialchars((string)($sp['assessment_title'] ?? '')) ?></td>
                    <td class="px-5 py-3.5 text-sm text-slate-500 hidden sm:table-cell">
                        <?php
                            $ca = $sp['completed_at'] ?? null;
                            echo $ca ? date('M j, Y', strtotime((string)$ca)) : '—';
                        ?>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-center font-medium text-slate-700">
                        <?= number_format((float)($sp['total_score'] ?? 0), 2) ?>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-center">
                        <?php
                            $cms = (float)($sp['career_match_score'] ?? 0);
                            $cmPct = $cms > 0 ? round($cms, 0) : 0;
                        ?>
                        <?php if ($cmPct > 0): ?>
                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold <?= $cmPct >= 70 ? 'bg-emerald-50 text-emerald-700' : ($cmPct >= 40 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') ?>">
                            <?= $cmPct ?>%
                        </span>
                        <?php else: ?>
                        <span class="text-xs text-slate-400">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="animate-in d4 rounded-[var(--radius)] bg-white shadow-[0_1px_3px_rgba(0,0,0,0.06)] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div>
            <h3 class="text-sm font-bold text-slate-700">Recent Activities</h3>
            <p class="text-xs text-slate-400 mt-0.5">Latest platform activities</p>
        </div>
    </div>
    <?php if (empty($recentActivities)): ?>
    <div class="flex flex-col items-center justify-center py-12">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50">
            <i class="bi bi-clock-history text-xl text-slate-300"></i>
        </div>
        <p class="mt-3 text-sm text-slate-500">No recent activities</p>
    </div>
    <?php else: ?>
    <div class="divide-y divide-slate-50">
        <?php foreach ($recentActivities as $i => $ra): ?>
        <?php
            $raType = $ra['type'] ?? '';
            $raSubject = htmlspecialchars((string)($ra['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
            $raDetail = htmlspecialchars((string)($ra['detail'] ?? ''), ENT_QUOTES, 'UTF-8');
            $raTime = $ra['occurred_at'] ?? '';
            $ts = strtotime((string)$raTime);
            $relative = $ts ? time() - $ts : 0;
            if ($relative < 60) $relStr = 'Just now';
            elseif ($relative < 3600) $relStr = floor($relative / 60) . 'm ago';
            elseif ($relative < 86400) $relStr = floor($relative / 3600) . 'h ago';
            elseif ($relative < 604800) $relStr = floor($relative / 86400) . 'd ago';
            else $relStr = $ts ? date('M j', $ts) : '';

            $typeIconMap = [
                'user_registered' => ['icon' => 'bi-person-plus', 'bg' => 'bg-emerald-50', 'color' => 'text-emerald-600', 'label' => 'Student registered'],
                'assessment_completed' => ['icon' => 'bi-clipboard-check', 'bg' => 'bg-indigo-50', 'color' => 'text-indigo-600', 'label' => 'Assessment completed'],
                'recommendation_generated' => ['icon' => 'bi-stars', 'bg' => 'bg-amber-50', 'color' => 'text-amber-600', 'label' => 'Recommendation generated'],
                'assessment_created' => ['icon' => 'bi-file-earmark-plus', 'bg' => 'bg-cyan-50', 'color' => 'text-cyan-600', 'label' => 'Assessment created'],
            ];
            $ti = $typeIconMap[$raType] ?? ['icon' => 'bi-info-circle', 'bg' => 'bg-slate-50', 'color' => 'text-slate-500', 'label' => 'Activity'];
        ?>
        <div class="flex items-start gap-3.5 px-5 py-3.5 slide-up" style="animation-delay:<?= min(0.24, 0.02 * ($i + 1)) ?>s">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl <?= $ti['bg'] ?> <?= $ti['color'] ?>">
                <i class="bi <?= $ti['icon'] ?> text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-slate-700">
                    <span class="font-semibold"><?= $raSubject ?></span>
                    <span class="text-slate-500"> <?= $ti['label'] ?></span>
                    <?php if ($raDetail): ?>
                    <span class="text-slate-400">— <?= $raDetail ?></span>
                    <?php endif; ?>
                </p>
            </div>
            <span class="shrink-0 text-xs text-slate-400"><?= $relStr ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<div class="animate-in d5">
    <h3 class="text-sm font-bold text-slate-700 mb-4">Top Recommended Careers</h3>
    <?php if (empty($topCareers)): ?>
    <div class="rounded-[var(--radius)] bg-white p-8 text-center shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50 mx-auto">
            <i class="bi bi-briefcase text-xl text-slate-300"></i>
        </div>
        <p class="mt-3 text-sm text-slate-500">No career recommendations yet</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach ($topCareers as $i => $tc): ?>
        <?php
            $cName = htmlspecialchars((string)($tc['career_name'] ?? ''));
            $cCount = (int)($tc['recommendation_count'] ?? 0);
            $cScore = (float)($tc['avg_score'] ?? 0);
            $icons = ['bi-laptop', 'bi-database', 'bi-palette', 'bi-bar-chart', 'bi-graph-up-arrow', 'bi-building', 'bi-heart-pulse', 'bi-calculator'];
            $cIcon = $icons[$i % count($icons)];
        ?>
        <div class="hover-lift rounded-[var(--radius)] bg-white p-5 shadow-[0_1px_3px_rgba(0,0,0,0.06)] slide-up" style="animation-delay:<?= 0.05 * ($i + 1) ?>s">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 mb-3">
                <i class="bi <?= $cIcon ?> text-lg"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800"><?= $cName ?></h4>
            <p class="mt-1 text-xs text-slate-500">Recommended to <span class="font-semibold text-indigo-600"><?= $cCount ?></span> student<?= $cCount === 1 ? '' : 's' ?></p>
            <div class="mt-2 flex items-center gap-1.5">
                <div class="flex-1 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full bg-indigo-500 progress-bar-fill" style="width:0%" data-width="<?= min(100, round($cScore)) ?>"></div>
                </div>
                <span class="text-[11px] font-semibold text-slate-600"><?= number_format($cScore, 0) ?>%</span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
function applyPeriod(value) {
    var params = new URLSearchParams(window.location.search);
    if (value === 'all') { params.delete('period'); }
    else { params.set('period', value); }
    params.set('page', 'admin-reports');
    window.location.search = params.toString();
}

function exportCSV() {
    var rows = [['Report', 'Metric', 'Value']];
    <?php foreach ($summaryStats as $key => $s): ?>
    rows.push(['Summary', '<?= $key ?>', '<?= $s['value'] ?>']);
    <?php endforeach; ?>
    rows.push([]);
    rows.push(['Student', 'Assessment', 'Date', 'Score', 'Career Match']);
    <?php foreach ($studentPerformance as $sp): ?>
    rows.push(['<?= str_replace("'", "\\'", $sp['student_name'] ?? '') ?>', '<?= str_replace("'", "\\'", $sp['assessment_title'] ?? '') ?>', '<?= $sp['completed_at'] ?? '' ?>', '<?= $sp['total_score'] ?? '' ?>', '<?= $sp['career_match_score'] ?? '' ?>%']);
    <?php endforeach; ?>
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

document.addEventListener('DOMContentLoaded', function() {
    var baseOpts = { responsive: true, maintainAspectRatio: true, animation: { duration: 800, easing: 'easeOutQuart' }, plugins: { legend: { labels: { boxWidth: 12, padding: 12, font: { size: 11 } } } } };

    <?php if (!empty($registrationTrend)): ?>
    new Chart(document.getElementById('registrationChart'), {
        type: 'line',
        data: {
            labels: [<?php foreach ($registrationTrend as $r): ?>'<?= $r['ym'] ?>',<?php endforeach; ?>],
            datasets: [{
                label: 'Registrations',
                data: [<?php foreach ($registrationTrend as $r): ?><?= (int)$r['count'] ?>,<?php endforeach; ?>],
                borderColor: '#6366f1',
                backgroundColor: function(ctx) {
                    var g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 220);
                    g.addColorStop(0, 'rgba(99,102,241,0.25)');
                    g.addColorStop(1, 'rgba(99,102,241,0.01)');
                    return g;
                },
                fill: true,
                tension: 0.35,
                pointRadius: 3,
                pointBackgroundColor: '#6366f1',
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#4F46E5',
            }]
        },
        options: { ...baseOpts, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { ...baseOpts.plugins, legend: { display: false } } }
    });
    <?php endif; ?>

    <?php if (!empty($assessmentCompletionTrend)): ?>
    var compCtx = document.getElementById('completionTrendChart');
    if (compCtx) {
        var compLabels = [<?php foreach ($assessmentCompletionTrend as $r): ?>'<?= $r['ym'] ?>',<?php endforeach; ?>];
        var compData = [<?php foreach ($assessmentCompletionTrend as $r): ?><?= (int)$r['count'] ?>,<?php endforeach; ?>];
        new Chart(compCtx, {
            type: 'bar',
            data: {
                labels: compLabels,
                datasets: [{
                    label: 'Completions',
                    data: compData,
                    backgroundColor: compLabels.map(function(_, i) {
                        var colors = ['#6366f1','#818cf8','#a5b4fc','#6366f1','#818cf8','#a5b4fc'];
                        return colors[i % colors.length];
                    }),
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: { ...baseOpts, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                plugins: { ...baseOpts.plugins, legend: { display: false } } }
        });
    }
    <?php endif; ?>

    <?php if (!empty($educationDistribution)): ?>
    var eduLabels = [<?php foreach ($educationDistribution as $r): ?>'<?= str_replace("'", "\\'", $r['education_level']) ?>',<?php endforeach; ?>];
    var eduData = [<?php foreach ($educationDistribution as $r): ?><?= (int)$r['count'] ?>,<?php endforeach; ?>];
    var eduColors = ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#f97316','#ec4899'];
    new Chart(document.getElementById('educationChart'), {
        type: 'doughnut',
        data: {
            labels: eduLabels,
            datasets: [{
                data: eduData,
                backgroundColor: eduColors.slice(0, eduLabels.length),
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8,
            }]
        },
        options: { ...baseOpts, cutout: '60%',
            plugins: { ...baseOpts.plugins, legend: { ...baseOpts.plugins.legend, position: 'bottom' } } }
    });
    <?php endif; ?>

    var bars = document.querySelectorAll('.progress-bar-fill');
    setTimeout(function() {
        bars.forEach(function(bar) {
            bar.style.width = bar.dataset.width + '%';
        });
    }, 300);
});
</script>

<style>
@media print {
    nav, aside, .offcanvas, .sticky-header select, .sticky-header button { display: none !important; }
    .sticky-header { position: static !important; padding: 0 !important; margin: 0 !important; background: transparent !important; }
    .bg-white { border: 1px solid #e2e8f0 !important; break-inside: avoid; }
    .grid { break-inside: avoid; }
    body { font-size: 11px; }
    .space-y-6 { gap: 1rem !important; }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

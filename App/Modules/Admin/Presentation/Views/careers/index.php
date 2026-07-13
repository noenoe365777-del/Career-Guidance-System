<?php
$careers = $careers ?? [];
$search = $search ?? '';
$educationFilter = $educationFilter ?? '';
$growthFilter = $growthFilter ?? '';
$categoryFilter = $categoryFilter ?? '';
$statusFilter = $statusFilter ?? '';
$sort = $sort ?? 'az';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$totalCareers = $totalCareers ?? 0;
$educationLevels = $educationLevels ?? [];
$growthRates = $growthRates ?? [];
$personalityTypes = $personalityTypes ?? [];
$statuses = $statuses ?? [];
$summaryStats = $summaryStats ?? [];
$allRecommendationStudents = $allRecommendationStudents ?? [];
$message = $message ?? null;

$pageTitle = 'Career Management';
$headerTitle = 'Career Management';
$activeMenu = 'careers';

ob_start();
?>

<?php if ($message !== null): ?>
    <div class="transform transition-all duration-300">
        <?php if ($message === 'created'): ?>
            <div class="flex items-center gap-3 p-4 border border-emerald-100 bg-emerald-50/50 rounded-2xl text-emerald-800 text-sm font-medium">
                <i class="bi bi-check-circle-fill text-base text-emerald-500"></i>
                <div>Career created successfully.</div>
            </div>
        <?php elseif ($message === 'updated'): ?>
            <div class="flex items-center gap-3 p-4 border border-blue-100 bg-blue-50/50 rounded-2xl text-blue-800 text-sm font-medium">
                <i class="bi bi-info-circle-fill text-base text-blue-500"></i>
                <div>Career updated successfully.</div>
            </div>
        <?php elseif ($message === 'deleted'): ?>
            <div class="flex items-center gap-3 p-4 border border-amber-100 bg-amber-50/50 rounded-2xl text-amber-800 text-sm font-medium">
                <i class="bi bi-exclamation-triangle-fill text-base text-amber-500"></i>
                <div>Career deleted successfully.</div>
            </div>
        <?php elseif ($message === 'not_found'): ?>
            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium">
                <i class="bi bi-x-circle-fill text-base text-rose-500"></i>
                <div>The selected career was not found.</div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>

        <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Career Management</h1>
        <p class="text-sm text-slate-500 mt-1">Manage careers and monitor recommendation results.</p>
    </div>
    <a href="<?= BASE_URL ?>/index.php?page=admin-careers-create"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 no-underline shrink-0 shadow-lg shadow-indigo-200">
        <i class="bi bi-plus-lg text-sm"></i>
        Add Career
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-slate-100 animate-[fadeInUp_0.6s_ease_both]">
        <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
            <i class="bi bi-briefcase text-lg text-indigo-600"></i>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Careers</p>
            <p class="stat-counter text-2xl font-extrabold text-slate-900 mt-0.5" data-target="<?= (int)($summaryStats['total_careers'] ?? 0) ?>">0</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-slate-100 animate-[fadeInUp_0.7s_ease_both]">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
            <i class="bi bi-people text-lg text-emerald-600"></i>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Students w/ Recommendations</p>
            <p class="stat-counter text-2xl font-extrabold text-slate-900 mt-0.5" data-target="<?= (int)($summaryStats['students_with_recommendations'] ?? 0) ?>">0</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-slate-100 animate-[fadeInUp_0.8s_ease_both]">
        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
            <i class="bi bi-trophy text-lg text-amber-600"></i>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Most Recommended</p>
            <p class="text-lg font-extrabold text-slate-900 mt-0.5 truncate" title="<?= htmlspecialchars((string)($summaryStats['most_recommended_name'] ?? 'N/A')) ?>"><?= htmlspecialchars((string)($summaryStats['most_recommended_name'] ?? 'N/A')) ?></p>
            <p class="text-xs text-slate-400"><?= (int)($summaryStats['most_recommended_count'] ?? 0) ?> student<?= (int)($summaryStats['most_recommended_count'] ?? 0) !== 1 ? 's' : '' ?></p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-slate-100 animate-[fadeInUp_0.9s_ease_both]">
        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
            <i class="bi bi-star text-lg text-blue-600"></i>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Recommendations</p>
            <p class="stat-counter text-2xl font-extrabold text-slate-900 mt-0.5" data-target="<?= (int)($summaryStats['total_recommendations'] ?? 0) ?>">0</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5 mt-6 border border-slate-100">
    <form method="get" action="<?= BASE_URL ?>/index.php" class="flex flex-col gap-4">
        <input type="hidden" name="page" value="admin-careers">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-1">
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Search</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="bi bi-search text-xs"></i>
                    </span>
                    <input type="text" name="search"
                           class="block w-full pl-9 pr-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                           value="<?= htmlspecialchars($search) ?>"
                           placeholder="Career name...">
                </div>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Category</label>
                <select name="category"
                        class="block w-full px-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150">
                    <option value="">All Categories</option>
                    <?php foreach ($personalityTypes as $type): ?>
                        <option value="<?= htmlspecialchars($type) ?>" <?= $categoryFilter === $type ? 'selected' : '' ?>><?= htmlspecialchars($type) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
          
          
          
        </div>
        <div class="flex items-center justify-end gap-2">
           
            <button type="submit"
                    class="inline-flex items-center justify-center px-5 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 border-0 outline-none cursor-pointer">
                <i class="bi bi-funnel mr-2"></i>
                Apply
            </button>
        </div>
    </form>
</div>

<?php if ($careers === []): ?>
    <div class="mt-6 rounded-2xl border border-dashed border-slate-200 bg-white p-12 text-center shadow-sm">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
            <i class="bi bi-briefcase text-2xl"></i>
        </div>
        <h3 class="mt-4 text-lg font-semibold text-slate-800">No careers found</h3>
        <p class="mt-2 text-sm text-slate-500">Try adjusting the search or add a new career to the system.</p>
    </div>
<?php else: ?>
    <div class="mt-6 grid gap-5 xl:grid-cols-2">
        <?php foreach ($careers as $index => $career): ?>
            <?php
            $careerId = (int)($career['career_id'] ?? 0);
            $name = (string)($career['career_name'] ?? 'Unnamed career');
            $description = (string)($career['description'] ?? '');
            $status = (string)($career['status'] ?? 'active');
            $isActive = strtolower($status) === 'active';
            $recommendationCount = (int)($career['recommendation_count'] ?? 0);
            $salaryValue = (string)($career['average_salary'] ?? '');
            $salaryNumeric = preg_replace('/[^0-9.]/', '', $salaryValue);
            $salaryDisplay = $salaryNumeric !== '' && $salaryNumeric !== '0' ? 'PHP ' . number_format((float)$salaryNumeric) : '—';
            $education = (string)($career['education_required'] ?? '');
            $category = (string)($career['personality_type'] ?? 'General');
            $growth = (string)($career['growth_rate'] ?? '');
            $highDemand = stripos($growth, 'high') !== false || stripos($growth, 'rapid') !== false || stripos($growth, 'very') !== false;
            $iconClass = (string)($career['career_icon'] ?? 'bi bi-briefcase');
            if (strpos($iconClass, 'bi ') !== 0 && strpos($iconClass, 'fa ') !== 0) {
                $iconClass = 'bi bi-briefcase';
            }
            $skills = [];
            foreach (preg_split('/[;,|\n]+/', (string)($career['required_skills'] ?? '')) ?: [] as $skill) {
                $skill = trim((string)$skill);
                if ($skill !== '') {
                    $skills[] = $skill;
                }
            }
            $skills = array_slice($skills, 0, 5);
            $maxRecommendations = max(1, max(array_map(static fn($item) => (int)($item['recommendation_count'] ?? 0), $careers)));
            $progressPercent = (int)round(($recommendationCount / $maxRecommendations) * 100);
            ?>
            <div class="career-card group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl" style="animation: fadeInUp 0.45s ease both; animation-delay: <?= min(0.16, 0.04 * $index) ?>s;">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white shadow-lg shadow-indigo-100">
                            <i class="<?= htmlspecialchars($iconClass) ?>"></i>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400"><?= htmlspecialchars($category ?: 'General') ?></div>
                            <h3 class="mt-1 text-lg font-semibold text-slate-900"><?= htmlspecialchars($name) ?></h3>
                        </div>
                    </div>
                    <?php if ($highDemand): ?>
                        <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-700 animate-pulse">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            High Demand
                        </span>
                    <?php endif; ?>
                </div>

                <p class="mt-4 text-sm leading-6 text-slate-500 line-clamp-3"><?= htmlspecialchars($description) ?></p>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Salary Range</div>
                        <div class="mt-1 text-sm font-semibold text-slate-800"><?= htmlspecialchars($salaryDisplay) ?></div>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Required Education</div>
                        <div class="mt-1 text-sm font-semibold text-slate-800"><?= htmlspecialchars($education ?: '—') ?></div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Required Skills</span>
                        <span class="text-xs text-slate-400">Top matches</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($skills as $skill): ?>
                            <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700"><?= htmlspecialchars($skill) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50/70 p-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Students Recommended</span>
                        <span class="font-semibold text-slate-900"><?= (int)$recommendationCount ?></span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200">
                        <div class="h-2 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-700" style="width: <?= $progressPercent ?>%"></div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-semibold tracking-[0.2em] <?= $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' ?>">
                        <span class="h-1.5 w-1.5 rounded-full <?= $isActive ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                        <?= $isActive ? 'Active' : 'Inactive' ?>
                    </span>
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <a href="<?= BASE_URL ?>/index.php?page=admin-careers-view&id=<?= $careerId ?>" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-indigo-200 hover:text-indigo-600">
                            <i class="bi bi-eye"></i>
                            View
                        </a>
                        <a href="<?= BASE_URL ?>/index.php?page=admin-careers-edit&id=<?= $careerId ?>" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:text-blue-600">
                            <i class="bi bi-pencil"></i>
                            Edit
                        </a>
                        <button type="button" onclick="openAnalyticsModal(<?= $careerId ?>)" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-violet-200 hover:text-violet-600">
                            <i class="bi bi-bar-chart"></i>
                            Analytics
                        </button>
                        <button type="button" onclick="openDeleteModal(<?= $careerId ?>, '<?= htmlspecialchars(addslashes($name)) ?>')" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-rose-200 hover:text-rose-600">
                            <i class="bi bi-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($totalPages > 1): ?>
    <nav class="mt-8 flex justify-center">
        <ul class="inline-flex items-center gap-1.5 p-1 bg-white border border-slate-100 rounded-xl shadow-sm">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php $isCurrent = ($i === $currentPage);
                $queryParams = [
                    'page' => 'admin-careers',
                    'search' => $search,
                    'education' => $educationFilter,
                    'growth' => $growthFilter,
                    'category' => $categoryFilter,
                    'status' => $statusFilter,
                    'sort' => $sort,
                    'page_number' => $i,
                ];
                $queryString = http_build_query(array_filter($queryParams, fn($v) => $v !== ''));
                ?>
                <li>
                    <a href="<?= BASE_URL ?>/index.php?<?= $queryString ?>"
                       class="inline-flex items-center justify-center text-xs font-bold rounded-lg transition-all duration-150 no-underline border-0 min-w-[32px] h-8 px-2.5
                              <?= $isCurrent ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-800' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<div id="analyticsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/35 backdrop-blur-sm transition-all duration-200">
    <div class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl animate-fade-in-up mx-4">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Recommendation Analytics</p>
                <h3 id="analyticsTitle" class="text-xl font-semibold text-slate-900"></h3>
            </div>
            <button type="button" onclick="closeAnalyticsModal()" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:text-slate-800">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div id="analyticsBody" class="mt-5 space-y-4"></div>
        <div class="mt-6 flex justify-end">
            <button type="button" onclick="closeAnalyticsModal()" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Close</button>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/35 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4 animate-fade-in-up">
        <div class="flex flex-col items-center text-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-50 text-red-500">
                <i class="bi bi-exclamation-triangle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Delete Career</h3>
                <p class="text-sm text-slate-500 mt-1">Are you sure you want to delete <strong id="deleteCareerName">this career</strong>? This action cannot be undone.</p>
            </div>
        </div>
        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-careers-delete" class="mt-6">
            <input type="hidden" name="id" id="deleteCareerId" value="">
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 border-0 outline-none cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-500 hover:bg-red-600 transition-all duration-150 border-0 outline-none cursor-pointer">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$careersJson = [];
foreach ($careers as $c) {
    $cid = (int)($c['career_id'] ?? 0);
    $careersJson[] = [
        'career_id' => $cid,
        'career_name' => (string)($c['career_name'] ?? ''),
        'description' => (string)($c['description'] ?? ''),
        'required_skills' => (string)($c['required_skills'] ?? ''),
        'average_salary' => (string)($c['average_salary'] ?? ''),
        'education_required' => (string)($c['education_required'] ?? ''),
        'personality_type' => (string)($c['personality_type'] ?? ''),
        'interest_type' => (string)($c['interest_type'] ?? ''),
        'growth_rate' => (string)($c['growth_rate'] ?? ''),
        'status' => (string)($c['status'] ?? 'active'),
        'recommendation_count' => (int)($c['recommendation_count'] ?? 0),
        'analytics' => $c['analytics'] ?? [],
    ];
}
$careersJsonEncoded = json_encode($careersJson);
$baseUrl = BASE_URL;
?>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.22s ease-out;
    }
    .career-card {
        animation-fill-mode: both;
    }
</style>

<script>
    var careerData = <?= $careersJsonEncoded ?>;

    function openAnalyticsModal(id) {
        var career = careerData.find(function(item) { return item.career_id === id; });
        if (!career) return;

        var analytics = career.analytics || {};
        var educationDistribution = analytics.education_distribution || {};
        var history = analytics.history || [];
        var total = Number(analytics.recommended_count || 0);
        var avgScore = Number(analytics.average_score || 0);
        var lastDate = analytics.last_recommendation_date ? new Date(analytics.last_recommendation_date.replace(' ', 'T')).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'No data yet';

        document.getElementById('analyticsTitle').textContent = career.career_name;
        var html = '';
        html += '<div class="grid gap-3 sm:grid-cols-2">';
        html += '<div class="rounded-2xl border border-indigo-100 bg-indigo-50/70 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-600">Recommended Count</div><div class="mt-2 text-2xl font-extrabold text-slate-900">' + total + '</div></div>';
        html += '<div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-600">Average Match Score</div><div class="mt-2 text-2xl font-extrabold text-slate-900">' + avgScore.toFixed(1) + '%</div></div>';
        html += '</div>';
        html += '<div class="rounded-2xl border border-slate-200 p-4"><div class="text-sm font-semibold text-slate-800">Education Distribution</div><div class="mt-3 space-y-3">';
        var current = 0;
        Object.keys(educationDistribution).forEach(function(label) {
            current += 1;
            var count = educationDistribution[label];
            var width = total > 0 ? Math.max(10, Math.round((count / total) * 100)) : 0;
            html += '<div><div class="flex justify-between text-sm text-slate-600"><span>' + escapeHtml(label) + '</span><span class="font-semibold text-slate-800">' + count + '</span></div><div class="mt-1 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-2 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width:' + width + '%"></div></div></div>';
        });
        if (current === 0) {
            html += '<p class="text-sm text-slate-500">No education distribution available yet.</p>';
        }
        html += '</div></div>';
        html += '<div class="rounded-2xl border border-slate-200 p-4"><div class="flex items-center justify-between"><div class="text-sm font-semibold text-slate-800">Last Recommendation</div><div class="text-sm text-slate-500">' + escapeHtml(lastDate) + '</div></div><div class="mt-3 space-y-3">';
        if (history.length > 0) {
            history.forEach(function(item) {
                html += '<div class="rounded-2xl border border-slate-100 bg-slate-50 p-3"><div class="flex items-center justify-between"><span class="font-semibold text-slate-800">' + escapeHtml(item.student || 'Student') + '</span><span class="text-sm font-semibold text-indigo-600">' + Number(item.score || 0).toFixed(1) + '%</span></div><p class="mt-2 text-sm text-slate-500">' + escapeHtml(item.reason || 'No notes') + '</p></div>';
            });
        } else {
            html += '<p class="text-sm text-slate-500">No recommendation history yet for this career.</p>';
        }
        html += '</div></div>';

        document.getElementById('analyticsBody').innerHTML = html;
        document.getElementById('analyticsModal').classList.remove('hidden');
        document.getElementById('analyticsModal').classList.add('flex');
    }

    function closeAnalyticsModal() {
        document.getElementById('analyticsModal').classList.add('hidden');
        document.getElementById('analyticsModal').classList.remove('flex');
    }

    function openDeleteModal(id, name) {
        document.getElementById('deleteCareerId').value = id;
        document.getElementById('deleteCareerName').textContent = name;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    document.querySelectorAll('.stat-counter[data-target]').forEach(function(counter) {
        var target = Number(counter.getAttribute('data-target') || 0);
        var start = 0;
        var duration = 800;
        var startTime = null;
        function animate(currentTime) {
            if (!startTime) startTime = currentTime;
            var progress = Math.min((currentTime - startTime) / duration, 1);
            var value = Math.floor(progress * target);
            counter.textContent = value.toLocaleString();
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }
        requestAnimationFrame(animate);
    });

    document.addEventListener('click', function(event) {
        if (event.target === document.getElementById('analyticsModal')) {
            closeAnalyticsModal();
        }
        if (event.target === document.getElementById('deleteModal')) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAnalyticsModal();
            closeDeleteModal();
        }
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
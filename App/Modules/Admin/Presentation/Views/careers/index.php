<?php
$careers = $careers ?? [];
$search = $search ?? '';
$educationFilter = $educationFilter ?? '';
$categoryFilter = $categoryFilter ?? '';
$statusFilter = $statusFilter ?? '';
$summaryStats = $summaryStats ?? [];
$allRecommendationStudents = $allRecommendationStudents ?? [];
$message = $message ?? null;
$educationLevels = $educationLevels ?? [];
$personalityTypes = $personalityTypes ?? [];
$statuses = $statuses ?? [];

$iconCategoryMap = [
    'fa-code' => 'Technology', 'fa-bolt' => 'Technology', 'fa-database' => 'Technology',
    'fa-stethoscope' => 'Healthcare', 'fa-heartbeat' => 'Healthcare', 'fa-hands' => 'Healthcare',
    'fa-chart-bar' => 'Business', 'fa-chart-line' => 'Business', 'fa-store' => 'Business',
    'fa-paint-brush' => 'Creative', 'fa-palette' => 'Creative',
    'fa-calculator' => 'Engineering', 'fa-cogs' => 'Engineering', 'fa-hard-hat' => 'Engineering', 'fa-wrench' => 'Engineering', 'fa-fan' => 'Engineering',
    'fa-gavel' => 'Legal', 'fa-shield-alt' => 'Legal',
    'fa-chalkboard-teacher' => 'Education',
];
function careerIconToLabel(string $icon, array $map): string {
    foreach ($map as $prefix => $label) {
        if ($icon === $prefix) return $label;
    }
    return 'General';
}
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;

$pageTitle = 'Career Management';
$activeMenu = 'careers';

$recommendedCareersCount = count(array_filter($careers, fn($c) => (int)($c['recommendation_count'] ?? 0) > 0));

ob_start();
if (file_exists(__DIR__ . '/../partials/summary_stat_card.php')) {
    include __DIR__ . '/../partials/summary_stat_card.php';
}
$careerCardDefs = [
    ['key' => 'total', 'label' => 'Total Careers', 'count' => (int)($summaryStats['total_careers'] ?? 0), 'icon' => 'bi-briefcase', 'bg' => '#eef2ff', 'color' => '#5B5FEF'],
    ['key' => 'recommended', 'label' => 'Recommended Careers', 'count' => $recommendedCareersCount, 'icon' => 'bi-people', 'bg' => '#ecfdf5', 'color' => '#059669'],
];
$mostRecommendedName = htmlspecialchars((string)($summaryStats['most_recommended_name'] ?? 'N/A'));
$mostRecommendedCount = (int)($summaryStats['most_recommended_count'] ?? 0);
$mostRecommendedHint = $mostRecommendedCount . ' student' . ($mostRecommendedCount !== 1 ? 's' : '') . ' recommended';
?>
<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUpCard { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes iconBounce { 0% { transform: scale(1); } 25% { transform: scale(1.25) rotate(-5deg); } 50% { transform: scale(0.9) rotate(3deg); } 75% { transform: scale(1.1) rotate(-2deg); } 100% { transform: scale(1) rotate(0deg); } }

    .card-in { animation: slideUpCard 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    .d1 { animation-delay: 0.05s; }
    .d2 { animation-delay: 0.10s; }
    .d3 { animation-delay: 0.15s; }
    .d4 { animation-delay: 0.20s; }
    .d5 { animation-delay: 0.25s; }
    .d6 { animation-delay: 0.30s; }

    .stat-card {
        border-radius: 16px; padding: 24px; background: #fff; border: 1px solid #e2e8f0; cursor: pointer;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out, background-color 0.3s ease-out;
        will-change: transform, box-shadow;
    }
    .stat-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 24px 48px -16px rgba(91,95,239,0.28);
        border-color: #5B5FEF; background: #fafaff;
    }
    .stat-card:hover .card-icon-bg { transform: scale(1.15) rotate(5deg); }
    .stat-card:hover .card-number { transform: scale(1.04); }
    .stat-card:active { transform: scale(0.97); }
    .card-icon-bg { transition: transform 0.3s ease-out, background-color 0.3s ease-out, color 0.3s ease-out; }
    .card-number { transition: transform 0.3s ease-out; }
    .card-icon-bg.bounce { animation: iconBounce 0.5s cubic-bezier(0.22,1,0.36,1); }

    .c-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .c-table th {
        padding: 0.65rem 1rem; font-size: 0.72rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8;
        text-align: left; background: #f8fafc; border-bottom: 1px solid #e2e8f0;
    }
    .c-table th:first-child { border-radius: 12px 0 0 0; }
    .c-table th:last-child { border-radius: 0 12px 0 0; text-align: right; }
    .c-table td {
        padding: 0.75rem 1rem; font-size: 0.84rem; color: #334155;
        border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    }
    .c-table tr:last-child td { border-bottom: none; }
    .c-table tr:hover td { background: #f8fafc; }

    .filter-select {
        border: 1px solid #e2e8f0; background: #fff; border-radius: 10px;
        padding: 0.5rem 0.85rem; font-size: 0.82rem; color: #334155;
        outline: none; transition: border-color 0.15s ease;
        cursor: pointer; min-width: 140px;
    }
    .filter-select:focus { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }

    .btn-action {
        display: inline-flex; align-items: center; justify-content: center;
        width: 2rem; height: 2rem; border-radius: 8px; color: #94a3b8;
        transition: all 0.15s ease; text-decoration: none; border: none; cursor: pointer; background: transparent;
    }
    .btn-action:hover { transform: scale(1.05); }
    .btn-action.view:hover { background: #eef2ff; color: #6366f1; }
    .btn-action.edit:hover { background: #eff6ff; color: #3b82f6; }
    .btn-action.danger:hover { background: #fef2f2; color: #ef4444; }

    .badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.15rem 0.6rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 500;
        border: 1px solid transparent;
    }
    .badge-active { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
    .badge-inactive { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
    .badge-dot { display: inline-block; width: 0.35rem; height: 0.35rem; border-radius: 50%; }
    .badge-dot.active { background: #10b981; }
    .badge-dot.inactive { background: #94a3b8; }
</style>

<div class="max-w-[1440px] mx-auto px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Career Management</h1>
            <p class="mt-1 text-sm text-slate-500">Manage careers and monitor career recommendations.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=admin-careers-create"
           class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all no-underline shadow-lg shadow-indigo-200 active:scale-[0.97]">
            <i class="bi bi-plus-lg text-sm"></i>
            Add Career
        </a>
    </div>

    <!-- Messages -->
    <?php if ($message !== null): ?>
    <div class="mb-8">
        <?php if ($message === 'created'): ?>
            <div class="flex items-center gap-3 p-4 border border-emerald-100 bg-emerald-50/50 rounded-2xl text-emerald-800 text-sm font-medium"><i class="bi bi-check-circle-fill text-base text-emerald-500"></i><div>Career created successfully.</div></div>
        <?php elseif ($message === 'updated'): ?>
            <div class="flex items-center gap-3 p-4 border border-blue-100 bg-blue-50/50 rounded-2xl text-blue-800 text-sm font-medium"><i class="bi bi-info-circle-fill text-base text-blue-500"></i><div>Career updated successfully.</div></div>
        <?php elseif ($message === 'deleted'): ?>
            <div class="flex items-center gap-3 p-4 border border-amber-100 bg-amber-50/50 rounded-2xl text-amber-800 text-sm font-medium"><i class="bi bi-exclamation-triangle-fill text-base text-amber-500"></i><div>Career deleted successfully.</div></div>
        <?php elseif ($message === 'not_found'): ?>
            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium"><i class="bi bi-x-circle-fill text-base text-rose-500"></i><div>The selected career was not found.</div></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <?php foreach ($careerCardDefs as $i => $cd):
            $counterId = 'careerCount' . ucfirst($cd['key']);
            renderAdminSummaryCard([
                'title' => $cd['label'],
                'value' => '0',
                'valueNumber' => (int)($cd['count'] ?? 0),
                'counterId' => $counterId,
                'icon' => $cd['icon'],
                'iconBg' => $cd['bg'],
                'iconColor' => $cd['color'],
                'hint' => $cd['hint'] ?? '',
                'delayClass' => 'd' . ($i + 1),
                'filter' => $cd['key'],
                'active' => false,
                'extraClass' => '',
            ]);
        endforeach; ?>
        <div class="stat-card card-in d3" style="cursor:default;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
                <div style="flex:1;min-width:0;">
                    <p style="font-size:14px;font-weight:600;color:#64748b;margin:0;">Most Recommended</p>
                    <p style="font-size:22px;font-weight:700;color:#0f172a;margin:2px 0 0 0;" title="<?= $mostRecommendedName ?>"><?= $mostRecommendedName ?></p>
                    <p style="font-size:12px;color:#94a3b8;margin:2px 0 0 0;"><?= $mostRecommendedHint ?></p>
                </div>
                <div class="card-icon-bg" style="width:44px;height:44px;display:flex;align-items:center;justify-content:center;border-radius:12px;background:#fffbeb;color:#d97706;flex-shrink:0;">
                    <i class="bi bi-trophy" style="font-size:22px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card-in d4 mb-6">
        <form method="get" action="<?= BASE_URL ?>/index.php" class="flex flex-wrap items-end gap-3">
            <input type="hidden" name="page" value="admin-careers">

            <div class="relative min-w-[200px] flex-1" style="max-width:280px;">
                <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-300"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search career..."
                       style="border:1px solid #e2e8f0;background:#fff;border-radius:10px;padding:0.55rem 1rem 0.55rem 2.4rem;font-size:0.85rem;width:100%;outline:none;transition:border-color 0.15s ease;"
                       onfocus="this.style.borderColor='#818cf8';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                       onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
            </div>

            <?php
            $uniqueCategories = [];
            foreach ($personalityTypes as $icon) {
                $label = careerIconToLabel($icon, $iconCategoryMap);
                $uniqueCategories[$label] = $icon;
            }
            ?>
            <select name="category" class="filter-select">
                <option value="">All Categories</option>
                <?php foreach ($uniqueCategories as $label => $icon): ?>
                <option value="<?= htmlspecialchars($icon) ?>" <?= $categoryFilter === $icon ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="education" class="filter-select">
                <option value="">All Education Levels</option>
                <?php foreach ($educationLevels as $el): ?>
                <option value="<?= htmlspecialchars($el) ?>" <?= $educationFilter === $el ? 'selected' : '' ?>><?= htmlspecialchars($el) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="status" class="filter-select">
                <option value="">All Statuses</option>
                <?php foreach ($statuses as $st): ?>
                <option value="<?= htmlspecialchars($st) ?>" <?= $statusFilter === $st ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($st)) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-all border-0 cursor-pointer"><i class="bi bi-funnel"></i> Filter</button>

            <?php if ($search !== '' || $categoryFilter !== '' || $educationFilter !== '' || $statusFilter !== ''): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-careers" class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:bg-slate-100 transition-all no-underline"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Careers Table -->
    <?php if ($careers === []): ?>
    <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-16 text-center shadow-sm card-in d5">
        <div class="mx-auto h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center">
            <i class="bi bi-briefcase text-2xl text-slate-300"></i>
        </div>
        <h3 class="mt-4 text-lg font-semibold text-slate-800">No careers found</h3>
        <p class="mt-1.5 text-sm text-slate-500">Try adjusting the search or add a new career to the system.</p>
    </div>
    <?php else: ?>
    <div class="section-card card-in d5" style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;">
        <div class="overflow-x-auto">
            <table class="c-table">
                <thead>
                    <tr>
                        <th>Career Name</th>
                        <th>Category</th>
                        <th>Education Level</th>
                        <th style="text-align:center;">Recommendations</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($careers as $career):
                        $careerId = (int)($career['career_id'] ?? 0);
                        $name = htmlspecialchars((string)($career['career_name'] ?? 'Unnamed'));
                        $catIcon = (string)($career['career_icon'] ?? '');
                        $category = htmlspecialchars(careerIconToLabel($catIcon, $iconCategoryMap));
                        $education = htmlspecialchars((string)($career['education_required'] ?? '—'));
                        $status = strtolower((string)($career['status'] ?? 'active'));
                        $isActive = $status === 'active';
                        $recCount = (int)($career['recommendation_count'] ?? 0);
                    ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                                    <i class="bi bi-briefcase text-sm"></i>
                                </div>
                                <span class="font-medium text-slate-800"><?= $name ?></span>
                            </div>
                        </td>
                        <td><span class="text-slate-600"><?= $category ?></span></td>
                        <td><span class="text-slate-600"><?= $education ?></span></td>
                        <td style="text-align:center;">
                            <span class="inline-flex items-center gap-1 font-semibold <?= $recCount > 0 ? 'text-indigo-600' : 'text-slate-400' ?>">
                                <i class="bi bi-people text-xs"></i>
                                <?= $recCount ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
                                <span class="badge-dot <?= $isActive ? 'active' : 'inactive' ?>"></span>
                                <?= $isActive ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-0.5">
                                <button type="button" onclick="openCareerDrawer(<?= $careerId ?>)" title="View" class="btn-action view"><i class="bi bi-eye"></i></button>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-careers-edit&id=<?= $careerId ?>" title="Edit" class="btn-action edit"><i class="bi bi-pencil"></i></a>
                                <button type="button" onclick="openDeleteModal(<?= $careerId ?>, '<?= htmlspecialchars(addslashes($name)) ?>')" title="Delete" class="btn-action danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="flex items-center justify-between px-6 py-3 border-t border-slate-100">
            <span class="text-xs text-slate-400">Page <?= $currentPage ?> of <?= $totalPages ?></span>
            <div class="flex items-center gap-1.5">
                <?php if ($currentPage > 1): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-careers&page_number=<?= $currentPage - 1 ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?><?= $categoryFilter !== '' ? '&category=' . urlencode($categoryFilter) : '' ?><?= $educationFilter !== '' ? '&education=' . urlencode($educationFilter) : '' ?><?= $statusFilter !== '' ? '&status=' . urlencode($statusFilter) : '' ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-600 hover:bg-slate-50 no-underline"><i class="bi bi-chevron-left"></i> Prev</a>
                <?php endif; ?>
                <?php for ($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-careers&page_number=<?= $p ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?><?= $categoryFilter !== '' ? '&category=' . urlencode($categoryFilter) : '' ?><?= $educationFilter !== '' ? '&education=' . urlencode($educationFilter) : '' ?><?= $statusFilter !== '' ? '&status=' . urlencode($statusFilter) : '' ?>" class="inline-flex items-center justify-center min-w-[32px] h-[32px] rounded-lg text-xs font-semibold no-underline <?= $p === $currentPage ? 'bg-indigo-600 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>"><?= $p ?></a>
                <?php endfor; ?>
                <?php if ($currentPage < $totalPages): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-careers&page_number=<?= $currentPage + 1 ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?><?= $categoryFilter !== '' ? '&category=' . urlencode($categoryFilter) : '' ?><?= $educationFilter !== '' ? '&education=' . urlencode($educationFilter) : '' ?><?= $statusFilter !== '' ? '&status=' . urlencode($statusFilter) : '' ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-600 hover:bg-slate-50 no-underline">Next <i class="bi bi-chevron-right"></i></a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

<!-- View Drawer -->
<div id="careerDrawer" class="fixed inset-0 z-50 hidden overflow-hidden">
    <div class="absolute inset-0" style="background:rgba(15,23,42,0.3);backdrop-filter:blur(2px);" onclick="closeCareerDrawer()"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-4xl overflow-y-auto bg-white shadow-2xl ring-1 ring-slate-200 drawer-content" style="animation:slideRight 0.35s cubic-bezier(0.16,1,0.3,1) both;">
        <div class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur-xl px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Career Details</p>
                <h2 class="font-semibold text-slate-900 mt-0.5">Quick View</h2>
            </div>
            <button type="button" onclick="closeCareerDrawer()" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-900 transition cursor-pointer"><i class="bi bi-x-lg text-sm"></i></button>
        </div>
        <div id="careerDrawerContent" class="p-0"></div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0" style="background:rgba(15,23,42,0.3);backdrop-filter:blur(2px);" onclick="closeDeleteModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4 z-10" style="animation:scaleIn 0.25s ease-out both;">
        <div class="flex flex-col items-center text-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-50 text-red-500"><i class="bi bi-exclamation-triangle text-2xl"></i></div>
            <div>
                <h3 class="font-bold text-slate-800">Delete Career</h3>
                <p class="text-sm text-slate-500 mt-1.5">Are you sure you want to delete <strong id="deleteCareerName" class="text-slate-700">this career</strong>? This action cannot be undone.</p>
            </div>
        </div>
        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-careers-delete" class="mt-6">
            <input type="hidden" name="id" id="deleteCareerId" value="">
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all border-0 cursor-pointer">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl font-semibold text-white bg-red-500 hover:bg-red-600 transition-all border-0 cursor-pointer">Delete</button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes slideRight { from { opacity: 0; transform: translateX(360px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .drawer-content::-webkit-scrollbar { width: 5px; }
    .drawer-content::-webkit-scrollbar-track { background: transparent; }
    .drawer-content::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }
</style>

<script>
var allRecStudents = <?= json_encode($allRecommendationStudents) ?>;

function openCareerDrawer(id) {
    var drawer = document.getElementById('careerDrawer');
    var content = document.getElementById('careerDrawerContent');
    if (!drawer || !content) return;
    drawer.classList.remove('hidden');
    content.innerHTML = '<div class="flex items-center justify-center py-20"><div class="flex flex-col items-center gap-3 text-slate-400"><i class="bi bi-arrow-repeat text-2xl animate-spin"></i><span style="font-size:0.85rem;">Loading career details...</span></div></div>';
    fetch('<?= BASE_URL ?>/index.php?page=admin-careers-view&id=' + encodeURIComponent(id) + '&format=modal')
        .then(function(r) { if (!r.ok) throw new Error(); return r.text(); })
        .then(function(html) {
            content.innerHTML = html;
            var students = allRecStudents[id] || [];
            if (students.length > 0) {
                var recSection = document.createElement('div');
                recSection.className = 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 lg:pb-12';
                recSection.innerHTML = buildRecommendedStudentsHTML(students);
                var container = content.querySelector('.max-w-7xl') || content.querySelector('section:last-of-type');
                if (container) container.parentNode.insertBefore(recSection, container.nextSibling);
                else content.appendChild(recSection);
            }
        })
        .catch(function() { content.innerHTML = '<div class="p-8 text-center text-rose-600">Unable to load career details.</div>'; });
}

function buildRecommendedStudentsHTML(students) {
    var html = '';
    html += '<div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-6 sm:p-8 mt-6">';
    html += '<div class="flex items-center gap-3 mb-6">';
    html += '<div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-md">';
    html += '<i class="bi bi-people text-white text-sm"></i></div>';
    html += '<h2 style="font-size:1.2rem;font-weight:700;" class="text-slate-900">Recommended Students</h2></div>';
    html += '<div class="overflow-x-auto"><table class="min-w-full border-collapse">';
    html += '<thead><tr class="border-b border-slate-100">';
    html += '<th style="font-size:0.75rem;" class="px-4 py-3 text-left font-semibold uppercase tracking-wider text-slate-400">Student</th>';
    html += '<th style="font-size:0.75rem;" class="px-4 py-3 text-left font-semibold uppercase tracking-wider text-slate-400 hidden sm:table-cell">Email</th>';
    html += '<th style="font-size:0.75rem;" class="px-4 py-3 text-center font-semibold uppercase tracking-wider text-slate-400">Score</th>';
    html += '<th style="font-size:0.75rem;" class="px-4 py-3 text-right font-semibold uppercase tracking-wider text-slate-400">Date</th>';
    html += '</tr></thead><tbody>';
    students.forEach(function(s) {
        var name = escapeHtml(s.username || 'Unknown');
        var email = escapeHtml(s.email || '');
        var score = Number(s.match_score || 0).toFixed(1);
        var date = s.created_at ? new Date(s.created_at.replace(' ', 'T')).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '—';
        var initial = (s.username || 'U').charAt(0).toUpperCase();
        html += '<tr class="border-b border-slate-100/70 hover:bg-slate-50/50 transition">';
        html += '<td class="px-4 py-3"><div class="flex items-center gap-2.5"><div class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center text-xs font-semibold text-indigo-500 shrink-0">' + initial + '</div><span style="font-size:0.9rem;" class="font-medium text-slate-700">' + name + '</span></div></td>';
        html += '<td style="font-size:0.85rem;" class="px-4 py-3 text-slate-500 hidden sm:table-cell">' + email + '</td>';
        html += '<td class="px-4 py-3 text-center"><span style="font-size:0.85rem;" class="inline-flex items-center rounded-full px-2.5 py-0.5 font-semibold bg-indigo-50 text-indigo-700">' + score + '%</span></td>';
        html += '<td style="font-size:0.85rem;" class="px-4 py-3 text-right text-slate-500">' + date + '</td>';
        html += '</tr>';
    });
    html += '</tbody></table></div></div>';
    return html;
}

function closeCareerDrawer() { document.getElementById('careerDrawer').classList.add('hidden'); }
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
function escapeHtml(str) { var d = document.createElement('div'); d.textContent = str; return d.innerHTML; }

document.addEventListener('click', function(e) { if (e.target === document.getElementById('deleteModal')) closeDeleteModal(); });
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeCareerDrawer(); closeDeleteModal(); } });

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

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

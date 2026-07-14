<?php
$careers = $careers ?? [];
$search = $search ?? '';
$summaryStats = $summaryStats ?? [];
$allRecommendationStudents = $allRecommendationStudents ?? [];
$message = $message ?? null;

$pageTitle = 'Career Management';
$activeMenu = 'careers';

$recommendedCareersCount = count(array_filter($careers, fn($c) => (int)($c['recommendation_count'] ?? 0) > 0));

ob_start();
if (file_exists(__DIR__ . '/../partials/summary_stat_card.php')) {
    include __DIR__ . '/../partials/summary_stat_card.php';
}
$careerCardDefs = [
    ['key' => 'total', 'label' => 'Total Careers', 'count' => (int)($summaryStats['total_careers'] ?? 0), 'icon' => 'bi-briefcase', 'bg' => '#eef2ff', 'color' => '#5B5FEF', 'hint' => 'In career catalog'],
    ['key' => 'recommended', 'label' => 'Recommended Careers', 'count' => $recommendedCareersCount, 'icon' => 'bi-people', 'bg' => '#ecfdf5', 'color' => '#059669', 'hint' => 'Have student recommendations'],
];
$mostRecommendedName = htmlspecialchars((string)($summaryStats['most_recommended_name'] ?? 'N/A'));
$mostRecommendedCount = (int)($summaryStats['most_recommended_count'] ?? 0);
$mostRecommendedHint = $mostRecommendedCount . ' student' . ($mostRecommendedCount !== 1 ? 's' : '') . ' recommended';
?>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes slideUpCard { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes iconBounce { 0% { transform: scale(1); } 25% { transform: scale(1.25) rotate(-5deg); } 50% { transform: scale(0.9) rotate(3deg); } 75% { transform: scale(1.1) rotate(-2deg); } 100% { transform: scale(1) rotate(0deg); } }
    @keyframes slideRight { from { opacity: 0; transform: translateX(360px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

    .page-in { animation: fadeIn 0.5s ease-out both; }
    .card-in { animation: slideUpCard 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    .anim-in { animation: fadeIn 0.4s ease-out both; }
    .anim-right { animation: slideRight 0.35s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .anim-scale { animation: scaleIn 0.25s ease-out both; }

    .d1 { animation-delay: 0.05s; }
    .d2 { animation-delay: 0.10s; }
    .d3 { animation-delay: 0.15s; }
    .d4 { animation-delay: 0.20s; }
    .d5 { animation-delay: 0.25s; }
    .d6 { animation-delay: 0.30s; }
    .d7 { animation-delay: 0.35s; }

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

    .career-card {
        transition: all 0.25s ease;
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid #f1f5f9;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .career-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -10px rgba(0,0,0,0.08);
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        color: #94a3b8;
        transition: all 0.15s ease;
        text-decoration: none;
        background: transparent;
        border: none;
        cursor: pointer;
    }
    .action-btn:hover { transform: scale(1.05); }
    .action-btn.view:hover { background: #eef2ff; color: #6366f1; }
    .action-btn.edit:hover { background: #eff6ff; color: #3b82f6; }
    .action-btn.danger:hover { background: #fef2f2; color: #ef4444; }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.2rem 0.7rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        border: 1px solid transparent;
    }
    .badge-active { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
    .badge-inactive { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
    .badge-dot { display: inline-block; width: 0.4rem; height: 0.4rem; border-radius: 50%; }
    .badge-dot.active { background: #10b981; }
    .badge-dot.inactive { background: #94a3b8; }

    .input-field {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        border-radius: 0.75rem;
        padding: 0.65rem 1rem 0.65rem 2.5rem;
        font-size: 0.9rem;
        transition: all 0.15s ease;
        width: 100%;
        outline: none;
    }
    .input-field:focus { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
    .input-field::placeholder { color: #94a3b8; }

    .drawer-overlay { background: rgba(15, 23, 42, 0.3); backdrop-filter: blur(2px); }
    .drawer-content::-webkit-scrollbar { width: 5px; }
    .drawer-content::-webkit-scrollbar-track { background: transparent; }
    .drawer-content::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }

    .rec-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 0.75rem;
        border-radius: 0.6rem;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
    }

    .card-stat {
        padding: 0.6rem 0.75rem;
        border-radius: 0.6rem;
        background: #f8fafc;
        text-align: center;
    }
</style>

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8 space-y-8">

    <!-- Header -->
    <div class="anim-up d1">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; letter-spacing: -0.02em;" class="text-slate-900">Career Management</h1>
                <p style="font-size: 0.95rem;" class="mt-1.5 text-slate-500">Manage careers and monitor career recommendations.</p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=admin-careers-create"
               class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all no-underline shadow-lg shadow-indigo-200 active:scale-[0.97]">
                <i class="bi bi-plus-lg text-sm"></i>
                Add Career
            </a>
        </div>
    </div>

    <!-- Messages -->
    <?php if ($message !== null): ?>
    <div class="anim-up d2">
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
    <div class="grid grid-cols-1 sm:grid-cols-3" style="gap: 24px;">
        <?php foreach ($careerCardDefs as $i => $cd):
            $delayClass = 'd' . ($i + 1);
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
                'delayClass' => $delayClass,
                'filter' => $cd['key'],
                'active' => false,
                'extraClass' => '',
            ]);
        endforeach; ?>

        <!-- Most Recommended (text card) -->
        <div class="stat-card card-in d3" style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:24px;box-shadow:0 6px 18px rgba(15,23,42,0.04);cursor:default;text-align:left;transition:transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out, background-color 0.3s ease-out;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
                <div style="flex:1;min-width:0;">
                    <p style="font-size:16px;font-weight:600;color:#64748b;margin:0;">Most Recommended</p>
                    <p style="font-size:20px;font-weight:700;color:#0f172a;margin:8px 0 0 0;" title="<?= $mostRecommendedName ?>"><?= $mostRecommendedName ?></p>
                    <p style="font-size:13px;color:#94a3b8;margin:6px 0 0 0;"><?= $mostRecommendedHint ?></p>
                </div>
                <div class="card-icon-bg" style="width:52px;height:52px;display:flex;align-items:center;justify-content:center;border-radius:14px;background:#fffbeb;color:#d97706;flex-shrink:0;transition:transform 0.3s ease-out, background-color 0.3s ease-out, color 0.3s ease-out;">
                    <i class="bi bi-trophy" style="font-size:24px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="anim-up d5">
        <form id="careerSearchForm" method="get" action="<?= BASE_URL ?>/index.php">
            <input type="hidden" name="page" value="admin-careers">
            <div class="relative max-w-md">
                <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-300 z-10"></i>
                <input id="careerSearch" type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search career..."
                       class="input-field">
                <?php if ($search !== ''): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-careers"
                   class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Career Cards -->
    <?php if ($careers === []): ?>
    <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-16 text-center shadow-sm anim-up d6">
        <div class="mx-auto h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center">
            <i class="bi bi-briefcase text-2xl text-slate-300"></i>
        </div>
        <h3 style="font-size: 1.1rem;" class="mt-4 font-semibold text-slate-800">No careers found</h3>
        <p style="font-size: 0.9rem;" class="mt-1.5 text-slate-500">Try adjusting the search or add a new career to the system.</p>
    </div>
    <?php else: ?>
    <div class="grid gap-5 sm:grid-cols-2">
        <?php foreach ($careers as $index => $career):
            $careerId = (int)($career['career_id'] ?? 0);
            $name = (string)($career['career_name'] ?? 'Unnamed');
            $description = (string)($career['description'] ?? '');
            $status = (string)($career['status'] ?? 'active');
            $isActive = strtolower($status) === 'active';
            $recommendationCount = (int)($career['recommendation_count'] ?? 0);
            $category = (string)($career['personality_type'] ?? 'General');
            $education = (string)($career['education_required'] ?? '');
            $iconLetter = strtoupper(substr($name, 0, 1));
            $bgColors = ['from-indigo-500 to-violet-500', 'from-emerald-500 to-teal-500', 'from-sky-500 to-blue-600', 'from-amber-500 to-orange-500', 'from-rose-500 to-pink-500', 'from-cyan-500 to-blue-500'];
            $bgGrad = $bgColors[$careerId % count($bgColors)];
        ?>
        <div class="career-card anim-up d<?= 6 + min($index % 3, 2) ?>" style="animation-delay: <?= min(0.3, 0.04 * $index) ?>s;">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br <?= $bgGrad ?> text-white font-bold text-xl shadow-md shrink-0">
                        <?= $iconLetter ?>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem;" class="font-semibold uppercase tracking-wider text-slate-400"><?= htmlspecialchars($category) ?></span>
                        <h3 style="font-size: 1.2rem; font-weight: 600;" class="text-slate-900 leading-tight mt-0.5"><?= htmlspecialchars($name) ?></h3>
                    </div>
                </div>
            </div>

            <p style="font-size: 0.9rem; line-height: 1.5;" class="mt-3 text-slate-500 line-clamp-2"><?= htmlspecialchars($description ?: 'No description available.') ?></p>

            <div class="mt-4 flex items-center gap-1.5">
                <?php if ($education): ?>
                <span style="font-size: 0.8rem;" class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 font-medium text-slate-600">
                    <i class="bi bi-mortarboard text-xs text-slate-400"></i>
                    <?= htmlspecialchars($education) ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span style="font-size: 0.85rem;" class="inline-flex items-center gap-1.5 font-medium text-slate-500">
                        <i class="bi bi-people text-sm"></i>
                        <?= $recommendationCount ?> student<?= $recommendationCount !== 1 ? 's' : '' ?>
                    </span>
                    <span class="badge <?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
                        <span class="badge-dot <?= $isActive ? 'active' : 'inactive' ?>"></span>
                        <?= $isActive ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
                <div class="flex items-center gap-0.5">
                    <button type="button" onclick="openCareerDrawer(<?= $careerId ?>)" title="View" class="action-btn view"><i class="bi bi-eye"></i></button>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-careers-edit&id=<?= $careerId ?>" title="Edit" class="action-btn edit"><i class="bi bi-pencil"></i></a>
                    <button type="button" onclick="openDeleteModal(<?= $careerId ?>, '<?= htmlspecialchars(addslashes($name)) ?>')" title="Delete" class="action-btn danger"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<!-- View Drawer -->
<div id="careerDrawer" class="fixed inset-0 z-50 hidden overflow-hidden">
    <div class="absolute inset-0 drawer-overlay" onclick="closeCareerDrawer()"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-4xl overflow-y-auto bg-white shadow-2xl ring-1 ring-slate-200 drawer-content anim-right">
        <div class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur-xl px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p style="font-size: 0.75rem;" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Career Details</p>
                <h2 style="font-size: 1.1rem;" class="font-semibold text-slate-900 mt-0.5">Quick View</h2>
            </div>
            <button type="button" onclick="closeCareerDrawer()" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-900 transition cursor-pointer">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>
        <div id="careerDrawerContent" class="p-0"></div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 drawer-overlay" onclick="closeDeleteModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4 z-10 anim-scale">
        <div class="flex flex-col items-center text-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-50 text-red-500">
                <i class="bi bi-exclamation-triangle text-2xl"></i>
            </div>
            <div>
                <h3 style="font-size: 1.05rem;" class="font-bold text-slate-800">Delete Career</h3>
                <p style="font-size: 0.85rem;" class="text-slate-500 mt-1.5">Are you sure you want to delete <strong id="deleteCareerName" class="text-slate-700">this career</strong>? This action cannot be undone.</p>
            </div>
        </div>
        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-careers-delete" class="mt-6">
            <input type="hidden" name="id" id="deleteCareerId" value="">
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeDeleteModal()" style="font-size: 0.85rem;" class="flex-1 px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all border-0 outline-none cursor-pointer">Cancel</button>
                <button type="submit" style="font-size: 0.85rem;" class="flex-1 px-4 py-2.5 rounded-xl font-semibold text-white bg-red-500 hover:bg-red-600 transition-all border-0 outline-none cursor-pointer">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
var allRecStudents = <?= json_encode($allRecommendationStudents) ?>;
var careerSearchTimer = null;

document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('careerSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (careerSearchTimer) clearTimeout(careerSearchTimer);
            careerSearchTimer = setTimeout(function() {
                searchInput.closest('form').submit();
            }, 400);
        });
    }
});

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
                if (container) {
                    container.parentNode.insertBefore(recSection, container.nextSibling);
                } else {
                    content.appendChild(recSection);
                }
            }
        })
        .catch(function() {
            content.innerHTML = '<div class="p-8 text-center text-rose-600">Unable to load career details.</div>';
        });
}

function buildRecommendedStudentsHTML(students) {
    var html = '';
    html += '<div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-6 sm:p-8 mt-6">';
    html += '<div class="flex items-center gap-3 mb-6">';
    html += '<div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-md">';
    html += '<i class="bi bi-people text-white text-sm"></i></div>';
    html += '<h2 style="font-size:1.2rem;font-weight:700;" class="text-slate-900">Recommended Students</h2>';
    html += '</div>';

    html += '<div class="overflow-x-auto">';
    html += '<table class="min-w-full border-collapse">';
    html += '<thead><tr class="border-b border-slate-100">';
    html += '<th style="font-size:0.75rem;" class="px-4 py-3 text-left font-semibold uppercase tracking-wider text-slate-400">Student</th>';
    html += '<th style="font-size:0.75rem;" class="px-4 py-3 text-left font-semibold uppercase tracking-wider text-slate-400 hidden sm:table-cell">Email</th>';
    html += '<th style="font-size:0.75rem;" class="px-4 py-3 text-center font-semibold uppercase tracking-wider text-slate-400">Score</th>';
    html += '<th style="font-size:0.75rem;" class="px-4 py-3 text-right font-semibold uppercase tracking-wider text-slate-400">Date</th>';
    html += '</tr></thead>';
    html += '<tbody>';

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

function closeCareerDrawer() {
    document.getElementById('careerDrawer').classList.add('hidden');
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

document.addEventListener('click', function(e) {
    if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeCareerDrawer(); closeDeleteModal(); }
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

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

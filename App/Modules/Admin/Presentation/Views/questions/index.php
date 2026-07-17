<?php
$questions = $questions ?? [];
$assessments = $assessments ?? [];
$distribution = $distribution ?? [];
$slugMap = $slugMap ?? [];
$search = $search ?? '';
$categorySlug = $categorySlug ?? '';
$questionTypeFilter = $questionTypeFilter ?? '';
$difficultyFilter = $difficultyFilter ?? '';
$statusFilter = $statusFilter ?? '';
$sort = $sort ?? '';
$totalQuestions = $totalQuestions ?? 0;
$personalityCount = $personalityCount ?? 0;
$interestCount = $interestCount ?? 0;
$aptitudeCount = $aptitudeCount ?? 0;
$valuesCount = $valuesCount ?? 0;
$questionTypes = $questionTypes ?? [];
$message = $message ?? null;

$pageTitle = 'Question Management';
$activeMenu = 'questions';

$assessmentNames = [];
foreach ($assessments as $a) {
    $id = (int)($a['assessment_id'] ?? 0);
    $assessmentNames[$id] = (string)($a['title'] ?? '');
}

$logoMap = [
    'personality'  => 'bi bi-person-badge',
    'interest'     => 'bi bi-activity',
    'aptitude'     => 'bi bi-cpu',
    'career_values' => 'bi bi-heart',
];
$iconMap = [];
foreach ($slugMap as $slug => $id) {
    $iconMap[$id] = $logoMap[$slug] ?? 'bi bi-question';
}

$questionCardDefs = [
    ['key' => 'total',         'label' => 'Total Questions',          'count' => $totalQuestions,   'icon' => 'bi-question-circle', 'bg' => '#eef2ff', 'color' => '#5B5FEF', 'filterId' => 'all'],
    ['key' => 'personality',   'label' => 'Personality Questions',    'count' => $personalityCount, 'icon' => 'bi-person-badge',    'bg' => '#fffbeb', 'color' => '#d97706', 'filterId' => 'personality'],
    ['key' => 'interest',      'label' => 'Interest Questions',       'count' => $interestCount,    'icon' => 'bi-activity',        'bg' => '#ecfdf5', 'color' => '#059669', 'filterId' => 'interest'],
    ['key' => 'aptitude',      'label' => 'Aptitude Questions',       'count' => $aptitudeCount,    'icon' => 'bi-cpu',            'bg' => '#ecfeff', 'color' => '#0891b2', 'filterId' => 'aptitude'],
    ['key' => 'career_values', 'label' => 'Career Values Questions',  'count' => $valuesCount,      'icon' => 'bi-heart',           'bg' => '#fef2f2', 'color' => '#dc2626', 'filterId' => 'career_values'],
];
foreach ($questionCardDefs as &$cd) {
    $cd['active'] = $categorySlug !== '' && $cd['filterId'] === $categorySlug;
}
unset($cd);

ob_start();
if (file_exists(__DIR__ . '/../partials/summary_stat_card.php')) {
    include __DIR__ . '/../partials/summary_stat_card.php';
}

if (!empty($isPartial)) {
    include __DIR__ . '/_list.php';
    return;
}
?>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes slideUpCard { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes iconBounce { 0% { transform: scale(1); } 25% { transform: scale(1.25) rotate(-5deg); } 50% { transform: scale(0.9) rotate(3deg); } 75% { transform: scale(1.1) rotate(-2deg); } 100% { transform: scale(1) rotate(0deg); } }
    @keyframes slideRight { from { opacity: 0; transform: translateX(320px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    @keyframes shimmer { 0% { background-position: -200px 0; } 100% { background-position: 200px 0; } }

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
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
    }
    .card-icon-bg { transition: transform 0.3s ease-out, background-color 0.3s ease-out, color 0.3s ease-out; }
    .card-number { transition: transform 0.3s ease-out; }
    .card-icon-bg.bounce { animation: iconBounce 0.5s cubic-bezier(0.22,1,0.36,1); }

    .tab-btn {
        padding: 0.55rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.15s ease;
        cursor: pointer;
        border: none;
        background: transparent;
        color: #64748b;
        white-space: nowrap;
    }
    .tab-btn:hover { background: #f1f5f9; color: #334155; }
    .tab-btn.active { background: #6366f1; color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,0.25); }
    .tab-btn.active:hover { background: #4f46e5; }

    .question-card {
        transition: all 0.2s ease;
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid #f1f5f9;
        padding: 1.25rem;
    }
    .question-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -10px rgba(0,0,0,0.08);
        border-color: #e2e8f0;
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
        font-size: 0.85rem;
    }
    .action-btn:hover { transform: scale(1.05); }
    .action-btn.view:hover { background: #eef2ff; color: #6366f1; }
    .action-btn.edit:hover { background: #eff6ff; color: #3b82f6; }
    .action-btn.dup:hover { background: #f5f3ff; color: #8b5cf6; }
    .action-btn.danger:hover { background: #fef2f2; color: #ef4444; }

    .assessment-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.7rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1px solid transparent;
    }

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

    .drawer-overlay {
        background: rgba(15, 23, 42, 0.3);
        backdrop-filter: blur(2px);
    }

    .drawer-content::-webkit-scrollbar { width: 5px; }
    .drawer-content::-webkit-scrollbar-track { background: transparent; }
    .drawer-content::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }

    .option-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 0.75rem;
        border-radius: 0.6rem;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
    }
</style>

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8 space-y-8">

    <!-- Header -->
    <div class="anim-up d1">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; letter-spacing: -0.02em;" class="text-slate-900">Question Management</h1>
                <p style="font-size: 0.95rem;" class="mt-1.5 text-slate-500">Manage assessment questions used for career recommendations.</p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=admin-questions-create"
               class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all no-underline shadow-lg shadow-indigo-200 active:scale-[0.97]">
                <i class="bi bi-plus-lg text-sm"></i>
                Add Question
            </a>
        </div>
    </div>

    <!-- Messages -->
    <?php if ($message !== null): ?>
    <div class="anim-up d2">
        <?php if ($message === 'created'): ?>
            <div class="flex items-center gap-3 p-4 border border-emerald-100 bg-emerald-50/50 rounded-2xl text-emerald-800 text-sm font-medium"><i class="bi bi-check-circle-fill text-base text-emerald-500"></i><div>Question created successfully.</div></div>
        <?php elseif ($message === 'updated'): ?>
            <div class="flex items-center gap-3 p-4 border border-blue-100 bg-blue-50/50 rounded-2xl text-blue-800 text-sm font-medium"><i class="bi bi-info-circle-fill text-base text-blue-500"></i><div>Question updated successfully.</div></div>
        <?php elseif ($message === 'deleted'): ?>
            <div class="flex items-center gap-3 p-4 border border-amber-100 bg-amber-50/50 rounded-2xl text-amber-800 text-sm font-medium"><i class="bi bi-exclamation-triangle-fill text-base text-amber-500"></i><div>Question deleted successfully.</div></div>
        <?php elseif ($message === 'duplicated'): ?>
            <div class="flex items-center gap-3 p-4 border border-violet-100 bg-violet-50/50 rounded-2xl text-violet-800 text-sm font-medium"><i class="bi bi-files text-base text-violet-500"></i><div>Question duplicated successfully.</div></div>
        <?php elseif ($message === 'not_found'): ?>
            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium"><i class="bi bi-x-circle-fill text-base text-rose-500"></i><div>Question not found.</div></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5" style="gap: 20px;" id="questionCards">
        <?php foreach ($questionCardDefs as $i => $cd):
            $delayClass = 'd' . ($i + 1);
            $counterId = 'qCount' . ucfirst($cd['key']);
            renderAdminSummaryCard([
                'title' => $cd['label'],
                'value' => (string)(int)($cd['count'] ?? 0),
                'valueNumber' => (int)($cd['count'] ?? 0),
                'counterId' => $counterId,
                'icon' => $cd['icon'],
                'iconBg' => $cd['bg'],
                'iconColor' => $cd['color'],
                'delayClass' => $delayClass,
            ]);
        endforeach; ?>
    </div>

    <!-- Filter Buttons -->
    <div class="anim-up d5 flex items-center gap-2 flex-wrap" id="filterButtons">
        <?php
        $filterLabels = [
            'personality'   => 'Personality',
            'interest'      => 'Interest',
            'aptitude'      => 'Aptitude',
            'career_values' => 'Career Values',
        ];
        foreach ($filterLabels as $slug => $label):
        ?>
        <button type="button" class="tab-btn <?= $categorySlug === $slug ? 'active' : '' ?>" data-filter="<?= htmlspecialchars($slug) ?>"><?= htmlspecialchars($label) ?></button>
        <?php endforeach; ?>
    </div>

    <!-- Search -->
    <div class="anim-up d6">
        <form id="questionSearchForm" method="get" action="<?= BASE_URL ?>/index.php">
            <input type="hidden" name="page" value="admin-questions">
            <input type="hidden" name="category" value="<?= htmlspecialchars($categorySlug) ?>">
            <div class="relative max-w-md">
                <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-300 z-10"></i>
                <input id="questionSearch" type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search questions..."
                       class="input-field">
                <?php if ($search !== ''): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-questions<?= $categorySlug !== '' && $categorySlug !== 'all' ? '&category=' . htmlspecialchars($categorySlug) : '' ?>"
                   class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                     <i class="bi bi-x-lg text-xs"></i>
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Question list — replaced via AJAX when a card or filter button is clicked -->
    <div id="questionListContainer">
        <?php if ($categorySlug === ''): ?>
        <div class="rounded-xl border border-dashed border-slate-200 bg-white p-6 text-center shadow-sm">
            <div class="mx-auto h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center">
                <i class="bi bi-funnel text-lg text-slate-300"></i>
            </div>
            <p style="font-size: 0.85rem;" class="mt-2 text-slate-500">Select a category above to view its questions.</p>
        </div>
        <?php else: ?>
        <?php include __DIR__ . '/_list.php'; ?>
        <?php endif; ?>
    </div>

</div>

<!-- View Drawer -->
<div id="viewDrawer" class="fixed inset-0 z-50 hidden overflow-hidden">
    <div class="absolute inset-0 drawer-overlay" onclick="closeViewDrawer()"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-xl overflow-y-auto bg-white shadow-2xl ring-1 ring-slate-200 drawer-content anim-right">
        <div class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur-xl px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p style="font-size: 0.75rem;" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Question Details</p>
                <h2 style="font-size: 1.1rem;" class="font-semibold text-slate-900 mt-0.5">View Question</h2>
            </div>
            <button type="button" onclick="closeViewDrawer()" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-900 transition cursor-pointer">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>
        <div id="viewDrawerContent" class="p-6 space-y-6">
            <div class="flex items-center justify-center py-16">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                    <i class="bi bi-arrow-repeat text-2xl animate-spin"></i>
                    <span style="font-size: 0.85rem;">Loading question details...</span>
                </div>
            </div>
        </div>
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
                <h3 style="font-size: 1.05rem;" class="font-bold text-slate-800">Delete Question</h3>
                <p style="font-size: 0.85rem;" class="text-slate-500 mt-1.5">Are you sure you want to delete <strong id="deleteQuestionText" class="text-slate-700">this question</strong>? This action cannot be undone.</p>
            </div>
        </div>
        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-questions-delete" class="mt-6">
            <input type="hidden" name="id" id="deleteQuestionId" value="">
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeDeleteModal()" style="font-size: 0.85rem;" class="flex-1 px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all border-0 outline-none cursor-pointer">Cancel</button>
                <button type="submit" style="font-size: 0.85rem;" class="flex-1 px-4 py-2.5 rounded-xl font-semibold text-white bg-red-500 hover:bg-red-600 transition-all border-0 outline-none cursor-pointer">Delete</button>
            </div>
        </form>
    </div>
</div>

<?php
function truncateText($text, $max = 60) {
    if (strlen($text) <= $max) return $text;
    return substr($text, 0, $max) . '...';
}
?>

<script>
(function() {
    var baseUrl = '<?= BASE_URL ?>/index.php';
    var listContainer = document.getElementById('questionListContainer');
    var cardsWrap = document.getElementById('questionCards');
    var filterButtonsWrap = document.getElementById('filterButtons');
    var searchInput = document.getElementById('questionSearch');
    var searchForm = document.getElementById('questionSearchForm');
    var currentFilterId = '<?= htmlspecialchars((string)($categorySlug ?? '')) ?>';
    var loading = false;

    function buildUrl(filterId, search) {
        var url = baseUrl + '?page=admin-questions&partial=1';
        if (filterId && filterId !== 'all' && filterId !== '') url += '&category=' + encodeURIComponent(filterId);
        if (search) url += '&search=' + encodeURIComponent(search);
        return url;
    }

    function highlightActive(filterId) {
        if (filterButtonsWrap) {
            filterButtonsWrap.querySelectorAll('.tab-btn').forEach(function(btn) {
                var match = btn.getAttribute('data-filter') === filterId;
                btn.classList.toggle('active', match);
            });
        }
    }

    function refreshList(filterId, search) {
        if (loading || !listContainer) return;
        loading = true;
        listContainer.style.opacity = '0.5';
        fetch(buildUrl(filterId, search), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { if (!r.ok) throw new Error(); return r.text(); })
            .then(function(html) {
                listContainer.innerHTML = html;
                listContainer.style.opacity = '1';
            })
            .catch(function() {
                listContainer.innerHTML = '<div class="rounded-2xl border border-dashed border-slate-200 bg-white p-16 text-center shadow-sm"><div class="mx-auto h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center"><i class="bi bi-exclamation-triangle text-2xl text-slate-300"></i></div><h3 class="mt-4 font-semibold text-slate-800">Failed to load questions</h3><p class="mt-1.5 text-slate-500">Please try again.</p></div>';
                listContainer.style.opacity = '1';
            })
            .finally(function() { loading = false; });
    }

    function applyFilter(filterId) {
        currentFilterId = filterId;
        highlightActive(filterId);
        var catInput = searchForm.querySelector('input[name="category"]');
        if (catInput) catInput.value = filterId && filterId !== 'all' ? filterId : '';
        var searchVal = searchInput ? searchInput.value.trim() : '';
        refreshList(filterId, searchVal);
        var newUrl = baseUrl + '?page=admin-questions';
        if (filterId && filterId !== 'all' && filterId !== '') newUrl += '&category=' + encodeURIComponent(filterId);
        if (searchVal) newUrl += '&search=' + encodeURIComponent(searchVal);
        history.replaceState(null, '', newUrl);
    }

    if (filterButtonsWrap) {
        filterButtonsWrap.addEventListener('click', function(e) {
            var btn = e.target.closest('.tab-btn[data-filter]');
            if (!btn) return;
            e.preventDefault();
            applyFilter(btn.getAttribute('data-filter'));
        });
    }

    if (searchInput && searchForm) {
        var searchTimer = null;
        searchInput.addEventListener('input', function() {
            if (searchTimer) clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                refreshList(currentFilterId, searchInput.value.trim());
                var newUrl = baseUrl + '?page=admin-questions';
                if (currentFilterId && currentFilterId !== 'all' && currentFilterId !== '') newUrl += '&category=' + encodeURIComponent(currentFilterId);
                if (searchInput.value.trim()) newUrl += '&search=' + encodeURIComponent(searchInput.value.trim());
                history.replaceState(null, '', newUrl);
            }, 400);
        });
    }
})();

function openViewDrawer(id) {
    var drawer = document.getElementById('viewDrawer');
    var content = document.getElementById('viewDrawerContent');
    if (!drawer || !content) return;

    drawer.classList.remove('hidden');
    content.innerHTML = '<div class="flex items-center justify-center py-16"><div class="flex flex-col items-center gap-3 text-slate-400"><i class="bi bi-arrow-repeat text-2xl animate-spin"></i><span style="font-size:0.85rem;">Loading question details...</span></div></div>';

    fetch('<?= BASE_URL ?>/index.php?page=admin-questions-view&id=' + encodeURIComponent(id) + '&format=json')
        .then(function(r) { if (!r.ok) throw new Error(); return r.json(); })
        .then(function(data) {
            var q = data.question || {};
            var opts = data.options || [];
            var assessmentName = q.assessment_title || 'Unknown';
            var created = q.created_at ? new Date(q.created_at.replace(' ', 'T')).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '—';
            var updated = q.updated_at ? new Date(q.updated_at.replace(' ', 'T')).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '—';
            var typeLabels = { 'single_choice': 'Single Choice', 'multiple_choice': 'Multiple Choice', 'likert': 'Likert Scale', 'true_false': 'True/False' };
            var typeLabel = typeLabels[q.question_type] || q.question_type || '—';
            var baseUrl = '<?= BASE_URL ?>';

            var html = '';

            html += '<div class="space-y-5">';

            html += '<div><label style="font-size:0.8rem;" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Question</label><p style="font-size:1.05rem;line-height:1.6;" class="mt-1.5 font-medium text-slate-800">' + escapeHtml(q.question_text || '') + '</p></div>';

            html += '<div class="grid grid-cols-2 gap-4">';
            html += '<div class="rounded-xl bg-slate-50 p-3.5"><label style="font-size:0.75rem;" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Assessment</label><p style="font-size:0.9rem;" class="mt-1 font-medium text-slate-700">' + escapeHtml(assessmentName) + '</p></div>';
            html += '<div class="rounded-xl bg-slate-50 p-3.5"><label style="font-size:0.75rem;" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Question Type</label><p style="font-size:0.9rem;" class="mt-1 font-medium text-slate-700">' + escapeHtml(typeLabel) + '</p></div>';
            html += '</div>';

            if (opts.length > 0) {
                html += '<div><label style="font-size:0.8rem;" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Answer Options</label><div class="mt-2 space-y-2">';
                opts.forEach(function(opt, idx) {
                    var val = opt.option_value !== undefined && opt.option_value !== null ? ' (Score: ' + opt.option_value + ')' : '';
                    html += '<div class="option-row"><span class="flex items-center justify-center w-6 h-6 rounded-md bg-indigo-100 text-indigo-700 text-xs font-bold shrink-0">' + (idx + 1) + '</span><span style="font-size:0.85rem;" class="text-slate-700 flex-1">' + escapeHtml(opt.option_text || '') + '</span><span style="font-size:0.8rem;" class="text-slate-400 font-medium">' + val + '</span></div>';
                });
                html += '</div></div>';
            }

            html += '<div class="grid grid-cols-2 gap-4">';
            html += '<div class="rounded-xl bg-slate-50 p-3.5"><label style="font-size:0.75rem;" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Created</label><p style="font-size:0.9rem;" class="mt-1 font-medium text-slate-700">' + escapeHtml(created) + '</p></div>';
            html += '<div class="rounded-xl bg-slate-50 p-3.5"><label style="font-size:0.75rem;" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Last Updated</label><p style="font-size:0.9rem;" class="mt-1 font-medium text-slate-700">' + escapeHtml(updated) + '</p></div>';
            html += '</div>';

            html += '</div>';

            html += '<div class="flex items-center gap-3 pt-4 border-t border-slate-100">';
            html += '<a href="' + baseUrl + '/index.php?page=admin-questions-edit&id=' + encodeURIComponent(q.question_id || id) + '" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all no-underline"><i class="bi bi-pencil"></i> Edit Question</a>';
            html += '<button type="button" onclick="closeViewDrawer()" style="font-size:0.85rem;" class="px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all border-0 outline-none cursor-pointer">Close</button>';
            html += '</div>';

            content.innerHTML = html;
        })
        .catch(function() {
            content.innerHTML = '<div class="p-8 text-center text-rose-600">Unable to load question details.</div>';
        });
}

function closeViewDrawer() {
    document.getElementById('viewDrawer').classList.add('hidden');
}

function openDeleteModal(id, text) {
    document.getElementById('deleteQuestionId').value = id;
    document.getElementById('deleteQuestionText').textContent = text;
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

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeViewDrawer(); closeDeleteModal(); }
});
document.addEventListener('click', function(e) {
    if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
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

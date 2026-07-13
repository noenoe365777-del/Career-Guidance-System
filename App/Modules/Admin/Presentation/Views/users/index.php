<?php
$users = $users ?? [];
$search = $search ?? '';
$assessmentStatus = $assessmentStatus ?? '';
$educationLevel = $educationLevel ?? null;
$educationLevels = $educationLevels ?? [];
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$totalUsers = $totalUsers ?? 0;
$message = $message ?? null;

ob_start();

$initials = function (string $name): string {
    $name = trim($name);
    if ($name === '') return '?';
    $parts = preg_split('/\s+/', $name);
    if (count($parts) >= 2) return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1));
    return mb_strtoupper(mb_substr($name, 0, 2));
};

$hasFilters = $search !== '' || $assessmentStatus !== '' || $educationLevel !== null;
?>
<style>
    @keyframes fadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes badgePop { from { opacity: 0; transform: scale(0.85); } to { opacity: 1; transform: scale(1); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scaleModal { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    @keyframes rowIn { from { opacity: 0; transform: translateX(-8px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes highlightFade { 0% { background-color: rgba(91,95,239,0.08); } 100% { background-color: transparent; } }

    .page-fade { animation: fadeUp 0.4s ease-out both; }
    .filter-drop { animation: slideDown 0.25s ease-out both; }
    .modal-in { animation: scaleModal 0.3s cubic-bezier(0.21,0.98,0.35,1) both; }
    .modal-overlay { animation: fadeIn 0.2s ease-out both; }

    .row-item { animation: rowIn 0.35s ease-out both; }
    .r1 { animation-delay: 0.02s; } .r2 { animation-delay: 0.06s; } .r3 { animation-delay: 0.10s; }
    .r4 { animation-delay: 0.14s; } .r5 { animation-delay: 0.18s; } .r6 { animation-delay: 0.22s; }
    .r7 { animation-delay: 0.26s; } .r8 { animation-delay: 0.30s; } .r9 { animation-delay: 0.34s; }
    .r10 { animation-delay: 0.38s; }

    .row-hover { transition: all 0.2s ease; }
    .row-hover:hover { background: rgba(91,95,239,0.04) !important; }

    .zebra-row:nth-child(even) { background-color: #faf9ff; }
    .zebra-row:nth-child(odd) { background-color: #ffffff; }

    .table-header-gradient {
        background: linear-gradient(135deg, #f8f6ff 0%, #f1f5f9 50%, #faf9ff 100%);
    }

    .status-badge {
        animation: badgePop 0.3s cubic-bezier(0.21,0.98,0.35,1) both;
    }

    .btn-view { transition: all 0.2s cubic-bezier(0.21,0.98,0.35,1); position: relative; overflow: hidden; }
    .btn-view:hover { transform: scale(1.04); }
    .btn-view:active { transform: scale(0.96); }
    .btn-view::after { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at center, rgba(91,95,239,0.15) 0%, transparent 70%); opacity: 0; transition: opacity 0.3s; }
    .btn-view:active::after { opacity: 1; }

    .skeleton { background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 8px; }

    .search-highlight { animation: highlightFade 2s ease-out; }

    .filter-select { transition: all 0.2s ease; }
    .filter-select:focus { transform: scale(1.02); }

    .tab-pill { transition: all 0.2s ease; }
    .tab-pill:hover { transform: translateY(-1px); }

    .progress-ring { transition: stroke-dashoffset 1s ease-out; }
</style>

<div class="max-w-[1400px] mx-auto space-y-6 page-fade">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
  
            <h1 class="text-2xl font-extrabold text-slate-900 mt-1">User Management</h1>
            <p class="text-sm text-slate-500 mt-1">Monitor registered students and their career guidance progress.</p>
        </div>
        <div class="flex items-center gap-2 bg-white rounded-xl border border-slate-200 px-4 py-2.5 shadow-sm">
            <i class="bi bi-people text-[#5B5FEF] text-base"></i>
            <span class="text-sm font-bold text-slate-800"><?= number_format($totalUsers) ?></span>
            <span class="text-xs text-slate-400">registered</span>
        </div>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white rounded-[18px] shadow-sm border border-slate-100 p-5 filter-drop">
        <form method="get" class="flex flex-col lg:flex-row items-end gap-4 w-full m-0">
            <input type="hidden" name="page" value="admin-users">

            <div class="w-full lg:flex-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Search</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400"><i class="bi bi-search text-sm"></i></span>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name or email..."
                        class="w-full pl-11 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 outline-none transition-all duration-200 focus:border-[#5B5FEF] focus:ring-2 focus:ring-[#5B5FEF]/10">
                </div>
            </div>

            <div class="w-full sm:w-48">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Education Level</label>
                <select name="education_level" class="filter-select w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-700 outline-none transition-all duration-200 focus:border-[#5B5FEF] focus:ring-2 focus:ring-[#5B5FEF]/10">
                    <option value="">All Levels</option>
                    <?php foreach ($educationLevels as $el): ?>
                    <option value="<?= (int)$el['id'] ?>" <?= $educationLevel === (int)$el['id'] ? 'selected' : '' ?>><?= htmlspecialchars($el['label']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full sm:w-48">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Assessment Status</label>
                <select name="assessment_status" class="filter-select w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-700 outline-none transition-all duration-200 focus:border-[#5B5FEF] focus:ring-2 focus:ring-[#5B5FEF]/10">
                    <option value="">All</option>
                    <option value="completed" <?= $assessmentStatus === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="in_progress" <?= $assessmentStatus === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="not_started" <?= $assessmentStatus === 'not_started' ? 'selected' : '' ?>>Not Started</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#5B5FEF] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#4a4ed6] hover:shadow-md border-0 outline-none cursor-pointer">
                    <i class="bi bi-funnel text-sm"></i>
                    Filter
                </button>
                <?php if ($hasFilters): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-users" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 no-underline transition-all duration-200 hover:bg-slate-50">
                    <i class="bi bi-x-circle text-sm"></i>
                    Reset
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Student List -->
    <div class="bg-white rounded-[20px] shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 pt-5 pb-1">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Registered Students</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Showing <?= count($users) ?> of <?= number_format($totalUsers) ?> students</p>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <i class="bi bi-arrow-repeat text-sm"></i>
                    <span>Click a row for details</span>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto px-2 pb-2">
            <table class="w-full text-left border-collapse align-middle">
                <thead>
                    <tr class="table-header-gradient">
                        <th class="whitespace-nowrap px-6 py-3.5 text-[11px] font-bold text-indigo-700 uppercase tracking-wider rounded-l-xl">Student</th>
                        <th class="whitespace-nowrap px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="whitespace-nowrap px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Education</th>
                        <th class="whitespace-nowrap px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Progress</th>
                        <th class="whitespace-nowrap px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Registered</th>
                        <th class="whitespace-nowrap px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right rounded-r-xl">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if ($users === []): ?>
                    <tr>
                        <td colspan="6" class="text-center py-20 px-5">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                    <i class="bi bi-person-x text-2xl"></i>
                                </div>
                                <span class="text-sm font-semibold text-slate-600">No students found</span>
                                <span class="text-xs text-slate-400">Try changing your search or filters.</span>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php $ri = 0; ?>
                    <?php foreach ($users as $user):
                        $ri++;
                        $userId = (int)($user['user_id'] ?? 0);
                        $fullName = htmlspecialchars((string)($user['username'] ?? ''));
                        $email = htmlspecialchars((string)($user['email'] ?? ''));
                        $edu = htmlspecialchars((string)($user['education_level'] ?? 'N/A'));
                        $createdAt = htmlspecialchars(date('M d, Y', strtotime((string)($user['created_at'] ?? date('Y-m-d')))));
                        $profileImage = (string)($user['profile_image'] ?? '');
                        $completed = (int)($user['completed_count'] ?? 0);
                        $total = (int)($user['total_count'] ?? 0);
                        $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
                        $statusText = $completed === 0 ? 'Not started' : ($completed >= $total ? 'Completed' : 'In progress');
                    ?>
                    <tr class="zebra-row row-item r<?= min($ri, 10) ?> row-hover cursor-pointer border-b border-slate-50/80 last:border-b-0" onclick="openModal(<?= $userId ?>)">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <?php if ($profileImage !== '' && file_exists(BASE_PATH . '/public/uploads/profile/' . $profileImage)): ?>
                                    <img src="<?= BASE_URL ?>/uploads/profile/<?= rawurlencode($profileImage) ?>" alt="" class="w-9 h-9 rounded-full object-cover shrink-0 ring-2 ring-white shadow-sm">
                                <?php else: ?>
                                    <span class="flex items-center justify-center w-9 h-9 rounded-full text-xs font-bold bg-gradient-to-br from-indigo-50 to-purple-50 text-[#5B5FEF] shrink-0 shadow-sm ring-2 ring-white"><?= $initials($fullName) ?></span>
                                <?php endif; ?>
                                <div>
                                    <span class="font-semibold text-slate-800"><?= $fullName ?: 'Student' ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500"><?= $email ?></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-50/50 text-indigo-600 status-badge">
                                <i class="bi bi-mortarboard text-[10px]"></i>
                                <?= $edu ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="flex-1 max-w-[100px]">
                                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-700 <?= $progress >= 100 ? 'bg-emerald-500' : ($progress > 0 ? 'bg-amber-400' : 'bg-slate-200') ?>" style="width:<?= max($progress, $total > 0 ? 4 : 0) ?>%"></div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold status-badge
                                    <?= $completed === 0 ? 'bg-slate-100 text-slate-500' : ($completed >= $total ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600') ?>">
                                    <?= $completed ?>/<?= $total ?: '0' ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-400 whitespace-nowrap text-xs"><?= $createdAt ?></td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button type="button" onclick="event.stopPropagation(); openModal(<?= $userId ?>)" class="btn-view inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-[#5B5FEF] transition-all duration-200 hover:border-[#5B5FEF] hover:bg-indigo-50 hover:shadow-sm hover:shadow-indigo-500/10 outline-none cursor-pointer">
                                <i class="bi bi-eye text-sm"></i>
                                View Details
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="flex justify-center">
        <ul class="inline-flex items-center gap-1.5 p-1 bg-white border border-slate-100 rounded-xl shadow-sm">
            <?php if ($currentPage > 1): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?page=admin-users&search=<?= urlencode($search) ?>&assessment_status=<?= urlencode($assessmentStatus) ?>&education_level=<?= $educationLevel ?>&page_number=<?= $currentPage - 1 ?>" class="inline-flex items-center justify-center text-xs font-bold rounded-lg transition-all duration-200 no-underline border-0 min-w-[36px] h-9 px-2.5 text-slate-500 hover:bg-slate-50 hover:text-slate-800">
                    <i class="bi bi-chevron-left text-sm"></i>
                </a>
            </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?page=admin-users&search=<?= urlencode($search) ?>&assessment_status=<?= urlencode($assessmentStatus) ?>&education_level=<?= $educationLevel ?>&page_number=<?= $i ?>"
                   class="inline-flex items-center justify-center text-xs font-bold rounded-lg transition-all duration-200 no-underline border-0 min-w-[36px] h-9 px-2.5
                          <?= $i === $currentPage ? 'bg-[#5B5FEF] text-white shadow-sm' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-800' ?>">
                    <?= $i ?>
                </a>
            </li>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?page=admin-users&search=<?= urlencode($search) ?>&assessment_status=<?= urlencode($assessmentStatus) ?>&education_level=<?= $educationLevel ?>&page_number=<?= $currentPage + 1 ?>" class="inline-flex items-center justify-center text-xs font-bold rounded-lg transition-all duration-200 no-underline border-0 min-w-[36px] h-9 px-2.5 text-slate-500 hover:bg-slate-50 hover:text-slate-800">
                    <i class="bi bi-chevron-right text-sm"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>

</div>

<!-- Student Detail Modal -->
<div id="studentModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div id="modalOverlay" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm modal-overlay" onclick="closeModal()"></div>
    <div id="modalContent" class="relative w-full max-w-lg bg-white rounded-[20px] shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto modal-in">
        <div class="p-6 sm:p-8">
            <!-- Close -->
            <button type="button" onclick="closeModal()" class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-xl border-0 bg-transparent text-slate-400 transition-all duration-200 hover:bg-slate-50 hover:text-slate-600 outline-none cursor-pointer">
                <i class="bi bi-x text-lg"></i>
            </button>

            <!-- Skeleton Loader -->
            <div id="modalSkeleton" class="space-y-5">
                <div class="flex items-center gap-4">
                    <div class="skeleton w-16 h-16 rounded-full shrink-0"></div>
                    <div class="flex-1 space-y-2">
                        <div class="skeleton h-5 w-40"></div>
                        <div class="skeleton h-3.5 w-56"></div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="skeleton h-4 w-full"></div>
                    <div class="skeleton h-4 w-3/4"></div>
                    <div class="skeleton h-4 w-5/6"></div>
                    <div class="skeleton h-4 w-2/3"></div>
                </div>
            </div>

            <!-- Modal Body -->
            <div id="modalBody" class="hidden">
                <div class="flex items-center gap-4 mb-6 pb-5 border-b border-slate-100">
                    <div id="modalAvatar" class="w-16 h-16 rounded-full shrink-0"></div>
                    <div class="min-w-0">
                        <h3 id="modalName" class="text-lg font-bold text-slate-900 truncate"></h3>
                        <p id="modalEmail" class="text-sm text-slate-500 mt-0.5"></p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Education</p>
                            <p id="modalEducation" class="text-sm font-semibold text-slate-800 mt-1"></p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Registered</p>
                            <p id="modalRegistered" class="text-sm font-semibold text-slate-800 mt-1"></p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Assessment Progress</p>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="flex-1">
                                <div class="w-full h-2.5 bg-white rounded-full overflow-hidden">
                                    <div id="modalProgressBar" class="h-full rounded-full bg-emerald-500 transition-all duration-700" style="width:0%"></div>
                                </div>
                            </div>
                            <span id="modalProgressText" class="text-sm font-bold text-slate-800 shrink-0"></span>
                        </div>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Latest Assessment</p>
                        <p id="modalLatestAssessment" class="text-sm font-semibold text-slate-800 mt-1">None</p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Top Career Match</p>
                        <p id="modalTopCareer" class="text-sm font-semibold text-slate-800 mt-1">None</p>
                        <p id="modalMatchScore" class="text-xs text-slate-500 mt-0.5 hidden"></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal()" class="w-full rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition-all duration-200 hover:bg-slate-50 outline-none cursor-pointer">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const studentsData = <?= json_encode(array_map(function($u) {
    return [
        'user_id' => (int)($u['user_id'] ?? 0),
        'username' => $u['username'] ?? '',
        'email' => $u['email'] ?? '',
        'education_level' => $u['education_level'] ?? 'N/A',
        'created_at' => $u['created_at'] ?? '',
        'profile_image' => $u['profile_image'] ?? '',
        'completed_count' => (int)($u['completed_count'] ?? 0),
        'total_count' => (int)($u['total_count'] ?? 0),
    ];
}, $users)) ?>;

function getInitials(name) {
    if (!name || name.trim() === '') return '?';
    const parts = name.trim().split(/\s+/);
    if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    return name.substring(0, 2).toUpperCase();
}

function openModal(userId) {
    const modal = document.getElementById('studentModal');
    const overlay = document.getElementById('modalOverlay');
    const content = document.getElementById('modalContent');
    const skeleton = document.getElementById('modalSkeleton');
    const body = document.getElementById('modalBody');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    skeleton.classList.remove('hidden');
    body.classList.add('hidden');

    // Fetch detail data
    fetch('<?= BASE_URL ?>/index.php?page=admin-users-view&id=' + userId + '&format=json')
        .then(r => r.json())
        .then(data => {
            skeleton.classList.add('hidden');
            body.classList.remove('hidden');

            const img = data.profile_image && data.profile_image.trim() !== ''
                ? '<img src="<?= BASE_URL ?>/uploads/profile/' + encodeURIComponent(data.profile_image) + '" alt="" class="w-16 h-16 rounded-full object-cover">'
                : '<span class="flex items-center justify-center w-16 h-16 rounded-full text-lg font-bold bg-[#5B5FEF]/10 text-[#5B5FEF]">' + getInitials(data.username) + '</span>';
            document.getElementById('modalAvatar').innerHTML = img;
            document.getElementById('modalName').textContent = data.username || 'Student';
            document.getElementById('modalEmail').textContent = data.email || '';

            document.getElementById('modalEducation').textContent = data.education_level || 'N/A';
            const d = data.created_at ? new Date(data.created_at) : null;
            document.getElementById('modalRegistered').textContent = d ? d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';

            const comp = parseInt(data.completed_count || 0);
            const total = parseInt(data.total_count || 0);
            const pct = total > 0 ? Math.round((comp / total) * 100) : 0;
            document.getElementById('modalProgressText').textContent = comp + '/' + total;
            document.getElementById('modalProgressBar').style.width = pct + '%';
            document.getElementById('modalProgressBar').className = 'h-full rounded-full transition-all duration-700 ' + (pct >= 100 ? 'bg-emerald-500' : pct > 0 ? 'bg-amber-400' : 'bg-slate-200');

            document.getElementById('modalLatestAssessment').textContent = data.latest_assessment || 'None';

            const careerEl = document.getElementById('modalTopCareer');
            const scoreEl = document.getElementById('modalMatchScore');
            if (data.top_career) {
                careerEl.textContent = data.top_career;
                scoreEl.textContent = 'Match score: ' + (parseFloat(data.match_score) || 0).toFixed(1) + '%';
                scoreEl.classList.remove('hidden');
            } else {
                careerEl.textContent = 'None';
                scoreEl.classList.add('hidden');
            }
        })
        .catch(() => {
            skeleton.classList.add('hidden');
            body.classList.remove('hidden');
            document.getElementById('modalName').textContent = 'Error loading data';
        });
}

function closeModal() {
    const modal = document.getElementById('studentModal');
    const content = document.getElementById('modalContent');
    content.style.transform = 'scale(0.95)';
    content.style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        content.style.transform = '';
        content.style.opacity = '';
    }, 200);
}

document.addEventListener('DOMContentLoaded', function() {
    // Highlight matching text
    const searchVal = '<?= htmlspecialchars($search) ?>';
    if (searchVal) {
        const regex = new RegExp('(' + searchVal.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
        document.querySelectorAll('td').forEach(td => {
            if (!td.closest('.btn-view')) {
                td.innerHTML = td.innerHTML.replace(regex, '<span class="search-highlight font-semibold text-[#5B5FEF]">$1</span>');
            }
        });
    }
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
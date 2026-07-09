<?php
$careers = $careers ?? [];
$search = $search ?? '';
$educationFilter = $educationFilter ?? '';
$growthFilter = $growthFilter ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$totalCareers = $totalCareers ?? 0;
$educationLevels = $educationLevels ?? [];
$growthRates = $growthRates ?? [];
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

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Overview</p>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Career Management</h1>
        <p class="text-sm text-slate-500 mt-1"><?= number_format($totalCareers) ?> career path<?= $totalCareers !== 1 ? 's' : '' ?></p>
    </div>
    <a href="<?= BASE_URL ?>/index.php?page=admin-careers-create"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 no-underline">
        <i class="bi bi-plus-lg text-sm"></i>
        Add Career
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5">
    <form method="get" class="flex flex-col sm:flex-row items-end gap-4 w-full m-0">
        <input type="hidden" name="page" value="admin-careers">
        <div class="w-full flex-1">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Search by name</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                    <i class="bi bi-search text-sm"></i>
                </span>
                <input type="text" name="search"
                       class="block w-full pl-11 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                       value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search careers...">
            </div>
        </div>
        <div class="w-full sm:w-44">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Education</label>
            <select name="education"
                    class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150">
                <option value="">All Levels</option>
                <?php foreach ($educationLevels as $level): ?>
                    <option value="<?= htmlspecialchars($level) ?>" <?= $educationFilter === $level ? 'selected' : '' ?>><?= htmlspecialchars($level) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-full sm:w-44">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Growth Rate</label>
            <select name="growth"
                    class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150">
                <option value="">All Rates</option>
                <?php foreach ($growthRates as $rate): ?>
                    <option value="<?= htmlspecialchars($rate) ?>" <?= $growthFilter === $rate ? 'selected' : '' ?>><?= htmlspecialchars($rate) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 border-0 outline-none whitespace-nowrap">
            <i class="bi bi-funnel mr-2"></i>
            Filter
        </button>
        <?php if ($search !== '' || $educationFilter !== '' || $growthFilter !== ''): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-careers" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 no-underline">
                Clear
            </a>
        <?php endif; ?>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse align-middle">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/50">
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Career</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Education Required</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Avg Salary</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Growth Rate</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <?php if ($careers === []): ?>
                    <tr>
                        <td colspan="5" class="text-center py-16 text-slate-400 bg-white">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="bi bi-briefcase text-4xl text-slate-200"></i>
                                <span class="text-sm">No careers found.</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($careers as $career): ?>
                        <?php
                        $careerId = (int)($career['career_id'] ?? 0);
                        $name = htmlspecialchars((string)($career['career_name'] ?? ''));
                        $description = htmlspecialchars((string)($career['description'] ?? ''));
                        $skills = htmlspecialchars((string)($career['required_skills'] ?? ''));
                        $salary = htmlspecialchars((string)($career['average_salary'] ?? ''));
                        $growth = htmlspecialchars((string)($career['growth_rate'] ?? ''));
                        $education = htmlspecialchars((string)($career['education_required'] ?? ''));
                        $personality = htmlspecialchars((string)($career['personality_type'] ?? ''));
                        $interest = htmlspecialchars((string)($career['interest_type'] ?? ''));
                        $growthLower = strtolower($growth);
                        $growthColor = match(true) {
                            str_contains($growthLower, 'very high') => 'text-emerald-600 bg-emerald-50',
                            str_contains($growthLower, 'high') => 'text-blue-600 bg-blue-50',
                            str_contains($growthLower, 'medium') => 'text-amber-600 bg-amber-50',
                            str_contains($growthLower, 'low') => 'text-slate-600 bg-slate-50',
                            default => 'text-slate-600 bg-slate-50',
                        };
                        ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-sm shrink-0">
                                        <?= htmlspecialchars(substr($name, 0, 1)) ?>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-slate-800"><?= $name ?></span>
                                        <?php if ($description !== ''): ?>
                                            <div class="text-xs text-slate-400 mt-0.5 max-w-[200px] truncate"><?= $description ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-700"><?= $education ?: '—' ?></td>
                            <td class="px-5 py-4 text-slate-700 font-medium"><?= $salary ? 'PHP ' . number_format((float)preg_replace('/[^0-9]/', '', $salary)) : '—' ?></td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide <?= $growthColor ?>">
                                    <?= $growth ?: '—' ?>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1">
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-careers-view&id=<?= $careerId ?>"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-150 no-underline" title="View Details">
                                        <i class="bi bi-eye text-base"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-careers-edit&id=<?= $careerId ?>"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-150 no-underline" title="Edit Career">
                                        <i class="bi bi-pencil text-sm"></i>
                                    </a>
                                    <button type="button"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all duration-150 border-0 bg-transparent outline-none p-0 cursor-pointer"
                                            title="Delete Career"
                                            onclick="openDeleteModal(<?= $careerId ?>, '<?= htmlspecialchars(addslashes($name)) ?>')">
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
    <nav class="flex justify-center">
        <ul class="inline-flex items-center gap-1.5 p-1 bg-white border border-slate-100 rounded-xl shadow-sm">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php $isCurrent = ($i === $currentPage); ?>
                <li>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-careers&search=<?= urlencode($search) ?>&education=<?= urlencode($educationFilter) ?>&growth=<?= urlencode($growthFilter) ?>&page_number=<?= $i ?>"
                       class="inline-flex items-center justify-center text-xs font-bold rounded-lg transition-all duration-150 no-underline border-0 min-w-[32px] h-8 px-2.5
                              <?= $isCurrent ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-800' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm transition-all duration-200">
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
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 border-0 outline-none cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-500 hover:bg-red-600 transition-all duration-150 border-0 outline-none cursor-pointer">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.2s ease-out;
    }
</style>

<script>
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

    document.addEventListener('click', function(e) {
        var modal = document.getElementById('deleteModal');
        if (e.target === modal) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

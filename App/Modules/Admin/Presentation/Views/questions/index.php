<?php
$questions = $questions ?? [];
$search = $search ?? '';
$assessmentFilter = $assessmentFilter ?? 0;
$typeFilter = $typeFilter ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$totalQuestions = $totalQuestions ?? 0;
$totalOptions = $totalOptions ?? 0;
$totalAssessments = $totalAssessments ?? 0;
$assessments = $assessments ?? [];
$questionTypes = $questionTypes ?? [];
$message = $message ?? null;

$pageTitle = 'Question Management';
$headerTitle = 'Question Management';
$activeMenu = 'questions';

ob_start();
?>

<?php if ($message !== null): ?>
    <div class="transform transition-all duration-300">
        <?php if ($message === 'created'): ?>
            <div class="flex items-center gap-3 p-4 border border-emerald-100 bg-emerald-50/50 rounded-2xl text-emerald-800 text-sm font-medium">
                <i class="bi bi-check-circle-fill text-base text-emerald-500"></i>
                <div>Question created successfully.</div>
            </div>
        <?php elseif ($message === 'updated'): ?>
            <div class="flex items-center gap-3 p-4 border border-blue-100 bg-blue-50/50 rounded-2xl text-blue-800 text-sm font-medium">
                <i class="bi bi-info-circle-fill text-base text-blue-500"></i>
                <div>Question updated successfully.</div>
            </div>
        <?php elseif ($message === 'deleted'): ?>
            <div class="flex items-center gap-3 p-4 border border-amber-100 bg-amber-50/50 rounded-2xl text-amber-800 text-sm font-medium">
                <i class="bi bi-exclamation-triangle-fill text-base text-amber-500"></i>
                <div>Question deleted successfully.</div>
            </div>
        <?php elseif ($message === 'not_found'): ?>
            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium">
                <i class="bi bi-x-circle-fill text-base text-rose-500"></i>
                <div>The selected question was not found.</div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Questions</p>
        <div class="mt-2 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600">
                <i class="bi bi-question-circle text-lg"></i>
            </div>
            <span class="text-3xl font-bold text-indigo-600"><?= (int)$totalQuestions ?></span>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Assessments</p>
        <div class="mt-2 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600">
                <i class="bi bi-clipboard-check text-lg"></i>
            </div>
            <span class="text-3xl font-bold text-emerald-600"><?= (int)$totalAssessments ?></span>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Options</p>
        <div class="mt-2 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-50 text-amber-600">
                <i class="bi bi-list-ul text-lg"></i>
            </div>
            <span class="text-3xl font-bold text-amber-600"><?= (int)$totalOptions ?></span>
        </div>
    </div>
</div>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Overview</p>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Question Management</h1>
        <p class="text-sm text-slate-500 mt-1"><?= number_format($totalQuestions) ?> question<?= $totalQuestions !== 1 ? 's' : '' ?> across <?= number_format($totalAssessments) ?> assessment<?= $totalAssessments !== 1 ? 's' : '' ?></p>
    </div>
    <a href="<?= BASE_URL ?>/index.php?page=admin-questions-create"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 no-underline">
        <i class="bi bi-plus-lg text-sm"></i>
        Add Question
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5">
    <form method="get" class="flex flex-col sm:flex-row items-end gap-4 w-full m-0">
        <input type="hidden" name="page" value="admin-questions">
        <div class="w-full flex-1">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Search by text</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                    <i class="bi bi-search text-sm"></i>
                </span>
                <input type="text" name="search"
                       class="block w-full pl-11 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                       value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search questions...">
            </div>
        </div>
        <div class="w-full sm:w-44">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Assessment</label>
            <select name="assessment_id"
                    class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150">
                <option value="">All Assessments</option>
                <?php foreach ($assessments as $a): ?>
                    <option value="<?= (int)($a['assessment_id'] ?? 0) ?>" <?= $assessmentFilter === (int)($a['assessment_id'] ?? 0) ? 'selected' : '' ?>><?= htmlspecialchars((string)($a['title'] ?? '')) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-full sm:w-44">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Question Type</label>
            <select name="type"
                    class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150">
                <option value="">All Types</option>
                <?php foreach ($questionTypes as $key => $label): ?>
                    <option value="<?= htmlspecialchars($key) ?>" <?= $typeFilter === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 border-0 outline-none whitespace-nowrap">
            <i class="bi bi-funnel mr-2"></i>
            Filter
        </button>
        <?php if ($search !== '' || $assessmentFilter > 0 || $typeFilter !== ''): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-questions" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 no-underline">
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
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Question</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Assessment</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-center">Options</th>
                    <th class="whitespace-nowrap px-5 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <?php if ($questions === []): ?>
                    <tr>
                        <td colspan="5" class="text-center py-16 text-slate-400 bg-white">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="bi bi-question-circle text-4xl text-slate-200"></i>
                                <span class="text-sm">No questions found.</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($questions as $question): ?>
                        <?php
                        $qId = (int)($question['question_id'] ?? 0);
                        $text = htmlspecialchars((string)($question['question_text'] ?? ''));
                        $assessmentTitle = htmlspecialchars((string)($question['assessment_title'] ?? ''));
                        $type = (string)($question['question_type'] ?? 'single_choice');
                        $optionCount = (int)($question['option_count'] ?? 0);
                        $typeLabel = $questionTypes[$type] ?? ucfirst(str_replace('_', ' ', $type));
                        $typeColors = [
                            'single_choice' => 'bg-blue-50 text-blue-600',
                            'multiple_choice' => 'bg-purple-50 text-purple-600',
                            'scale' => 'bg-amber-50 text-amber-600',
                        ];
                        $typeColor = $typeColors[$type] ?? 'bg-slate-50 text-slate-600';
                        ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <td class="px-5 py-4 max-w-[320px]">
                                <span class="font-semibold text-slate-800"><?= $text ?></span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide bg-indigo-50 text-indigo-600">
                                    <?= $assessmentTitle ?>
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide <?= $typeColor ?>">
                                    <?= htmlspecialchars($typeLabel) ?>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center text-slate-700 font-semibold"><?= $optionCount ?></td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1">
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-questions-view&id=<?= $qId ?>"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-150 no-underline" title="View Details">
                                        <i class="bi bi-eye text-base"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-questions-edit&id=<?= $qId ?>"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-150 no-underline" title="Edit Question">
                                        <i class="bi bi-pencil text-sm"></i>
                                    </a>
                                    <button type="button"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all duration-150 border-0 bg-transparent outline-none p-0 cursor-pointer"
                                            title="Delete Question"
                                            onclick="openDeleteModal(<?= $qId ?>, '<?= htmlspecialchars(addslashes(mb_substr($text, 0, 60))) ?>')">
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
                    <a href="<?= BASE_URL ?>/index.php?page=admin-questions&search=<?= urlencode($search) ?>&assessment_id=<?= urlencode((string)$assessmentFilter) ?>&type=<?= urlencode($typeFilter) ?>&page_number=<?= $i ?>"
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
                <h3 class="text-lg font-bold text-slate-800">Delete Question</h3>
                <p class="text-sm text-slate-500 mt-1">Are you sure you want to delete this question? This will also remove all its answer options. This action cannot be undone.</p>
            </div>
        </div>
        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-questions-delete" class="mt-6">
            <input type="hidden" name="id" id="deleteQuestionId" value="">
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
    function openDeleteModal(id, text) {
        document.getElementById('deleteQuestionId').value = id;
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

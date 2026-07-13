<?php
$questions = $questions ?? [];
$search = $search ?? '';
$assessmentFilter = $assessmentFilter ?? 0;
$questionTypeFilter = $questionTypeFilter ?? '';
$difficultyFilter = $difficultyFilter ?? '';
$statusFilter = $statusFilter ?? '';
$sort = $sort ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$totalQuestions = $totalQuestions ?? 0;
$totalAssessments = $totalAssessments ?? 0;
$averageQuestions = $averageQuestions ?? 0;
$lastUpdatedDate = $lastUpdatedDate ?? null;
$assessments = $assessments ?? [];
$questionTypes = $questionTypes ?? [];
$difficultyOptions = $difficultyOptions ?? [];
$statusOptions = $statusOptions ?? [];
$distribution = $distribution ?? [];
$recentActivity = $recentActivity ?? [];
$message = $message ?? null;

$pageTitle = 'Question Management';
$activeMenu = 'questions';

function fmtDate($v): string {
    if (!$v) return '—';
    $t = strtotime((string)$v);
    return $t ? date('M j, Y', $t) : (string)$v;
}

ob_start();
?>

<style>
:root {
    --bg-page: #f8fafc;
    --radius: 18px;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(12px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-in { animation: fadeInUp 0.45s cubic-bezier(0.2,0.8,0.2,1) both; }
.d1 { animation-delay: 0.04s; }
.d2 { animation-delay: 0.08s; }
.d3 { animation-delay: 0.12s; }
.d4 { animation-delay: 0.16s; }
.d5 { animation-delay: 0.20s; }
.d6 { animation-delay: 0.24s; }

.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px -10px rgba(15, 23, 42, 0.16);
}

.toast { animation: slideDown 0.25s ease; }
</style>

<div class="space-y-6" style="min-height:100vh;background:var(--bg-page)">
    <?php if ($message !== null): ?>
    <div class="toast space-y-3">
        <?php if ($message === 'created'): ?>
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
            <i class="bi bi-check-circle-fill text-emerald-500"></i>
            <span>Question saved successfully.</span>
        </div>
        <?php elseif ($message === 'updated'): ?>
        <div class="flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-800 shadow-sm">
            <i class="bi bi-info-circle-fill text-blue-500"></i>
            <span>Question updated successfully.</span>
        </div>
        <?php elseif ($message === 'deleted'): ?>
        <div class="flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-900 shadow-sm">
            <i class="bi bi-check-circle-fill text-amber-500"></i>
            <span>Question deleted successfully.</span>
        </div>
        <?php elseif ($message === 'duplicated'): ?>
        <div class="flex items-center gap-3 rounded-2xl border border-violet-200 bg-violet-50 px-4 py-3 text-sm font-medium text-violet-800 shadow-sm">
            <i class="bi bi-copy text-violet-500"></i>
            <span>Question duplicated successfully.</span>
        </div>
        <?php elseif ($message === 'not_found'): ?>
        <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 shadow-sm">
            <i class="bi bi-x-circle-fill text-rose-500"></i>
            <span>Question not found.</span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="animate-in flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Question Management</h1>

        </div>
        <a href="<?= BASE_URL ?>/index.php?page=admin-questions-create" class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">
            <i class="bi bi-plus-lg"></i>
            Add Question
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="animate-in d1 hover-lift rounded-[var(--radius)] border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Total Questions</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600"><i class="bi bi-question-circle"></i></div>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-900"><?= number_format((int)$totalQuestions) ?></p>
        </div>
        <div class="animate-in d2 hover-lift rounded-[var(--radius)] border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Total Assessments</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-violet-50 text-violet-600"><i class="bi bi-journal-check"></i></div>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-900"><?= number_format((int)$totalAssessments) ?></p>
        </div>
        <div class="animate-in d3 hover-lift rounded-[var(--radius)] border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Avg per Assessment</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-600"><i class="bi bi-bar-chart"></i></div>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-900"><?= $averageQuestions ?></p>
        </div>
        <div class="animate-in d4 hover-lift rounded-[var(--radius)] border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Last Updated</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-600"><i class="bi bi-clock"></i></div>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-900"><?= $lastUpdatedDate ? htmlspecialchars(fmtDate($lastUpdatedDate), ENT_QUOTES, 'UTF-8') : '—' ?></p>
        </div>
    </div>

    <div class="animate-in d5 rounded-[var(--radius)] border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <form method="get" class="grid gap-4 xl:grid-cols-[1.3fr_0.8fr_0.8fr_0.7fr_0.7fr_0.7fr_auto] xl:items-end">
            <input type="hidden" name="page" value="admin-questions">
            <div>
                <label class="mb-1.5 block text-xs font-medium text-slate-500">Search</label>
                <div class="relative">
                    <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                    <input type="text" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" placeholder="Search questions..." class="w-full rounded-2xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-slate-500">Assessment</label>
                <select name="assessment_id" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                    <option value="">All Assessments</option>
                    <?php foreach ($assessments as $assessment): ?>
                        <?php $assessmentId = (int)($assessment['assessment_id'] ?? 0); ?>
                        <option value="<?= $assessmentId ?>" <?= (int)$assessmentFilter === $assessmentId ? 'selected' : '' ?>><?= htmlspecialchars((string)($assessment['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-slate-500">Question Type</label>
                <select name="question_type" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                    <option value="">All Types</option>
                    <?php foreach ($questionTypes as $key => $label): ?>
                        <option value="<?= htmlspecialchars((string)$key, ENT_QUOTES, 'UTF-8') ?>" <?= $questionTypeFilter === (string)$key ? 'selected' : '' ?>><?= htmlspecialchars((string)$label, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-slate-500">Difficulty</label>
                <select name="difficulty" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                    <option value="">All Levels</option>
                    <?php foreach ($difficultyOptions as $option): ?>
                        <option value="<?= htmlspecialchars((string)($option['value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" <?= $difficultyFilter === (string)($option['value'] ?? '') ? 'selected' : '' ?>><?= htmlspecialchars((string)($option['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-slate-500">Status</label>
                <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                    <option value="">All Statuses</option>
                    <?php foreach ($statusOptions as $option): ?>
                        <option value="<?= htmlspecialchars((string)($option['value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" <?= $statusFilter === (string)($option['value'] ?? '') ? 'selected' : '' ?>><?= htmlspecialchars((string)($option['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-slate-500">Sort</label>
                <select name="sort" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                    <option value="newest" <?= $sort === 'newest' || $sort === '' ? 'selected' : '' ?>>Newest</option>
                    <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest</option>
                    <option value="alpha" <?= $sort === 'alpha' ? 'selected' : '' ?>>A–Z</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">Apply</button>
                <?php if ($search !== '' || (int)$assessmentFilter > 0 || $questionTypeFilter !== '' || $difficultyFilter !== '' || $statusFilter !== ''): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-questions" class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.7fr,0.8fr]">
        <div class="space-y-6">
            <div class="rounded-[var(--radius)] border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Question Library</h2>
                        <p class="text-sm text-slate-500">A responsive view of the live question bank.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-600">
                            <input id="selectAll" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>Select all</span>
                        </label>
                        <button type="submit" form="bulkActionsForm" formaction="<?= BASE_URL ?>/index.php?page=admin-questions-bulk-delete" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-rose-200 hover:text-rose-600">Delete</button>
                        <button type="submit" form="bulkActionsForm" formaction="<?= BASE_URL ?>/index.php?page=admin-questions-duplicate" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-200 hover:text-indigo-600">Duplicate</button>
                        <a href="<?= BASE_URL ?>/index.php?page=admin-questions-export&search=<?= urlencode($search) ?>&assessment_id=<?= urlencode((string)$assessmentFilter) ?>&question_type=<?= urlencode((string)$questionTypeFilter) ?>&difficulty=<?= urlencode((string)$difficultyFilter) ?>&status=<?= urlencode((string)$statusFilter) ?>&sort=<?= urlencode($sort) ?>" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-emerald-200 hover:text-emerald-600">Export</a>
                        <button type="button" onclick="openImportModal()" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-violet-200 hover:text-violet-600">Import</button>
                    </div>
                </div>

                <?php if ($questions === []): ?>
                    <div class="mt-6 rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600"><i class="bi bi-question-circle text-2xl"></i></div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">No questions found</h3>
                        <p class="mt-2 text-sm text-slate-500">Try adjusting the search or add a new question from the header.</p>
                    </div>
                <?php else: ?>
                    <form id="bulkActionsForm" method="post" class="mt-6">
                        <div class="hidden overflow-hidden rounded-2xl border border-slate-200 md:block">
                            <div class="grid grid-cols-[40px_1.4fr_0.9fr_0.8fr_0.7fr_0.6fr_0.8fr_150px] gap-3 bg-slate-50 px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">
                                <div></div>
                                <div>Question</div>
                                <div>Assessment</div>
                                <div>Type</div>
                                <div>Difficulty</div>
                                <div>Options</div>
                                <div>Updated</div>
                                <div class="text-right">Actions</div>
                            </div>
                            <?php foreach ($questions as $question): ?>
                                <?php
                                    $questionId = (int)($question['question_id'] ?? 0);
                                    $questionText = (string)($question['question_text'] ?? '');
                                    $assessmentTitle = (string)($question['assessment_title'] ?? '');
                                    $questionType = (string)($question['question_type'] ?? 'single_choice');
                                    $difficulty = (string)($question['difficulty'] ?? 'easy');
                                    $optionCount = (int)($question['option_count'] ?? 0);
                                    $updated = $question['created_at'] ?? null;
                                    $typeLabel = is_array($questionTypes) ? ($questionTypes[$questionType] ?? ucfirst(str_replace('_', ' ', $questionType))) : ucfirst(str_replace('_', ' ', $questionType));
                                    $difficultyLabel = $difficulty === 'medium' ? 'Medium' : ($difficulty === 'hard' ? 'Hard' : 'Easy');
                                    $difficultyCss = $difficulty === 'hard' ? 'bg-amber-50 text-amber-700' : ($difficulty === 'medium' ? 'bg-cyan-50 text-cyan-700' : 'bg-emerald-50 text-emerald-700');
                                ?>
                                <div class="grid grid-cols-[40px_1.4fr_0.9fr_0.8fr_0.7fr_0.6fr_0.8fr_150px] gap-3 border-t border-slate-100 bg-white px-4 py-3 text-sm transition hover:bg-indigo-50/40">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="ids[]" value="<?= $questionId ?>" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900"><?= htmlspecialchars($questionText, ENT_QUOTES, 'UTF-8') ?></div>
                                    </div>
                                    <div class="truncate text-slate-600"><?= htmlspecialchars($assessmentTitle, ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="truncate text-slate-600"><?= htmlspecialchars((string)$typeLabel, ENT_QUOTES, 'UTF-8') ?></div>
                                    <div><span class="rounded-full px-2.5 py-1 text-xs font-semibold <?= $difficultyCss ?>"><?= htmlspecialchars($difficultyLabel, ENT_QUOTES, 'UTF-8') ?></span></div>
                                    <div class="text-slate-600"><?= $optionCount ?></div>
                                    <div class="text-slate-600"><?= htmlspecialchars(fmtDate($updated), ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="flex justify-end gap-1">
                                        <button type="button" onclick="openQuestionDrawer(<?= $questionId ?>)" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:border-indigo-200 hover:text-indigo-600" title="View"><i class="bi bi-eye"></i></button>
                                        <a href="<?= BASE_URL ?>/index.php?page=admin-questions-edit&id=<?= $questionId ?>" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:border-indigo-200 hover:text-indigo-600" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-questions-duplicate" class="m-0 inline-flex">
                                            <input type="hidden" name="id" value="<?= $questionId ?>">
                                            <button type="submit" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:border-indigo-200 hover:text-indigo-600" title="Duplicate"><i class="bi bi-copy"></i></button>
                                        </form>
                                        <button type="button" onclick="openDeleteModal(<?= $questionId ?>)" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:border-rose-200 hover:text-rose-600" title="Delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="space-y-3 md:hidden">
                            <?php foreach ($questions as $question): ?>
                                <?php
                                    $questionId = (int)($question['question_id'] ?? 0);
                                    $questionText = (string)($question['question_text'] ?? '');
                                    $assessmentTitle = (string)($question['assessment_title'] ?? '');
                                    $questionType = (string)($question['question_type'] ?? 'single_choice');
                                    $difficulty = (string)($question['difficulty'] ?? 'easy');
                                    $optionCount = (int)($question['option_count'] ?? 0);
                                    $updated = $question['created_at'] ?? null;
                                    $typeLabel = is_array($questionTypes) ? ($questionTypes[$questionType] ?? ucfirst(str_replace('_', ' ', $questionType))) : ucfirst(str_replace('_', ' ', $questionType));
                                    $difficultyLabel = $difficulty === 'medium' ? 'Medium' : ($difficulty === 'hard' ? 'Hard' : 'Easy');
                                    $difficultyCss = $difficulty === 'hard' ? 'bg-amber-50 text-amber-700' : ($difficulty === 'medium' ? 'bg-cyan-50 text-cyan-700' : 'bg-emerald-50 text-emerald-700');
                                ?>
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" name="ids[]" value="<?= $questionId ?>" class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <div class="min-w-0 flex-1">
                                            <div class="font-semibold text-slate-900"><?= htmlspecialchars($questionText, ENT_QUOTES, 'UTF-8') ?></div>
                                            <div class="mt-2 flex flex-wrap gap-2 text-xs text-slate-500">
                                                <span class="rounded-full bg-slate-100 px-2.5 py-1"><?= htmlspecialchars($assessmentTitle, ENT_QUOTES, 'UTF-8') ?></span>
                                                <span class="rounded-full bg-slate-100 px-2.5 py-1"><?= htmlspecialchars((string)$typeLabel, ENT_QUOTES, 'UTF-8') ?></span>
                                                <span class="rounded-full px-2.5 py-1 <?= $difficultyCss ?>"><?= htmlspecialchars($difficultyLabel, ENT_QUOTES, 'UTF-8') ?></span>
                                            </div>
                                            <div class="mt-3 flex items-center justify-between text-sm text-slate-500">
                                                <span><?= $optionCount ?> options</span>
                                                <span><?= htmlspecialchars(fmtDate($updated), ENT_QUOTES, 'UTF-8') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <button type="button" onclick="openQuestionDrawer(<?= $questionId ?>)" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-200 hover:text-indigo-600">View</button>
                                        <a href="<?= BASE_URL ?>/index.php?page=admin-questions-edit&id=<?= $questionId ?>" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-200 hover:text-indigo-600">Edit</a>
                                        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-questions-duplicate" class="m-0 inline-flex">
                                            <input type="hidden" name="id" value="<?= $questionId ?>">
                                            <button type="submit" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-200 hover:text-indigo-600">Duplicate</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </form>
                <?php endif; ?>

                <?php if ($totalPages > 1): ?>
                    <div class="mt-5 flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-slate-500">Page <?= $currentPage ?> of <?= $totalPages ?></p>
                        <nav class="flex items-center gap-1">
                            <?php if ($currentPage > 1): ?>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-questions&search=<?= urlencode($search) ?>&assessment_id=<?= urlencode((string)$assessmentFilter) ?>&question_type=<?= urlencode((string)$questionTypeFilter) ?>&difficulty=<?= urlencode((string)$difficultyFilter) ?>&status=<?= urlencode((string)$statusFilter) ?>&sort=<?= urlencode($sort) ?>&page_number=<?= $currentPage - 1 ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-sm text-slate-500 hover:bg-slate-100"><i class="bi bi-chevron-left"></i></a>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-questions&search=<?= urlencode($search) ?>&assessment_id=<?= urlencode((string)$assessmentFilter) ?>&question_type=<?= urlencode((string)$questionTypeFilter) ?>&difficulty=<?= urlencode((string)$difficultyFilter) ?>&status=<?= urlencode((string)$statusFilter) ?>&sort=<?= urlencode($sort) ?>&page_number=<?= $i ?>" class="inline-flex h-8 min-w-[32px] items-center justify-center rounded-lg px-2 text-sm font-medium <?= $i === $currentPage ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-100' ?>"><?= $i ?></a>
                            <?php endfor; ?>
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-questions&search=<?= urlencode($search) ?>&assessment_id=<?= urlencode((string)$assessmentFilter) ?>&question_type=<?= urlencode((string)$questionTypeFilter) ?>&difficulty=<?= urlencode((string)$difficultyFilter) ?>&status=<?= urlencode((string)$statusFilter) ?>&sort=<?= urlencode($sort) ?>&page_number=<?= $currentPage + 1 ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-sm text-slate-500 hover:bg-slate-100"><i class="bi bi-chevron-right"></i></a>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-[var(--radius)] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Distribution by Assessment</h3>
                        <p class="text-sm text-slate-500">Live question counts by assessment.</p>
                    </div>
                    <div class="rounded-full bg-indigo-50 px-3 py-1 text-sm font-semibold text-indigo-700"><?= count($distribution) ?> groups</div>
                </div>
                <div class="mt-5 space-y-4">
                    <?php foreach ($distribution as $item): ?>
                        <?php $title = (string)($item['title'] ?? 'Untitled'); $count = (int)($item['question_count'] ?? 0); $max = max(1, (int)max(array_column($distribution, 'question_count'))); ?>
                        <div>
                            <div class="mb-1.5 flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="text-slate-500"><?= $count ?></span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width: <?= round(($count / $max) * 100) ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="rounded-[var(--radius)] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Recent Activity</h3>
                        <p class="text-sm text-slate-500">Latest additions and question updates.</p>
                    </div>
                </div>
                <div class="mt-5 space-y-3">
                    <?php foreach ($recentActivity as $activity): ?>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-sm font-semibold text-slate-800"><?= htmlspecialchars((string)($activity['subject'] ?? 'Question'), ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-700"><?= htmlspecialchars((string)($activity['action_type'] ?? 'created'), ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <p class="mt-2 text-sm text-slate-500"><?= htmlspecialchars((string)($activity['assessment_name'] ?? 'Assessment'), ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="mt-2 text-xs text-slate-400"><?= htmlspecialchars(fmtDate((string)($activity['occurred_at'] ?? '')), ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="questionDrawer" class="fixed inset-0 z-50 hidden items-start justify-end bg-slate-950/40 backdrop-blur-sm">
    <div class="h-full w-full max-w-xl bg-white shadow-2xl">
        <div class="flex items-start justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Question Preview</p>
                <h3 id="drawerTitle" class="mt-1 text-lg font-semibold text-slate-900">Question details</h3>
            </div>
            <button type="button" onclick="closeQuestionDrawer()" class="rounded-full border border-slate-200 p-2 text-slate-500 transition hover:text-slate-800"><i class="bi bi-x-lg"></i></button>
        </div>
        <div id="drawerBody" class="space-y-4 overflow-y-auto px-6 py-6"></div>
        <div class="border-t border-slate-100 px-6 py-4">
            <a id="drawerEditLink" href="#" class="inline-flex items-center rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">Edit Question</a>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/35 backdrop-blur-sm">
    <div class="mx-4 w-full max-w-sm rounded-[var(--radius)] bg-white p-6 shadow-2xl">
        <div class="flex flex-col items-center text-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-xl text-red-500"><i class="bi bi-exclamation-triangle"></i></div>
            <h3 class="mt-4 text-lg font-semibold text-slate-900">Delete Question</h3>
            <p class="mt-2 text-sm text-slate-500">This action cannot be undone. The question and its options will be permanently removed.</p>
        </div>
        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-questions-delete" class="mt-6">
            <input type="hidden" name="id" id="deleteQuestionId" value="">
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</button>
                <button type="submit" class="flex-1 rounded-2xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">Delete</button>
            </div>
        </form>
    </div>
</div>

<div id="importModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/35 backdrop-blur-sm">
    <div class="mx-4 w-full max-w-lg rounded-[var(--radius)] bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Import Questions</p>
                <h3 class="text-lg font-semibold text-slate-900">Upload a CSV file</h3>
            </div>
            <button type="button" onclick="closeImportModal()" class="rounded-full border border-slate-200 p-2 text-slate-500 transition hover:text-slate-800"><i class="bi bi-x-lg"></i></button>
        </div>
        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-questions-import" enctype="multipart/form-data" class="mt-5 space-y-4">
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                <p class="font-medium text-slate-700">Expected columns</p>
                <p class="mt-1">question_text, assessment_id or assessment, question_type, options</p>
            </div>
            <input type="file" name="import_file" accept=".csv" class="block w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeImportModal()" class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">Import</button>
            </div>
        </form>
    </div>
</div>

<script>
function openQuestionDrawer(id) {
    fetch('<?= BASE_URL ?>/index.php?page=admin-questions-view&id=' + id + '&format=json')
        .then(function(response) { return response.json(); })
        .then(function(payload) {
            var question = payload.question || {};
            var options = payload.options || [];
            document.getElementById('drawerTitle').textContent = question.question_text || 'Question preview';
            document.getElementById('drawerEditLink').href = '<?= BASE_URL ?>/index.php?page=admin-questions-edit&id=' + (question.question_id || id);
            var optionsHtml = '';
            if (options.length > 0) {
                optionsHtml = '<div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><h4 class="text-sm font-semibold text-slate-700">Options</h4><ul class="mt-3 space-y-2">';
                options.forEach(function(option) {
                    optionsHtml += '<li class="rounded-xl border border-slate-100 bg-white px-3 py-2 text-sm text-slate-600">' + escapeHtml(option.option_text || '') + '</li>';
                });
                optionsHtml += '</ul></div>';
            } else {
                optionsHtml = '<div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">No options saved for this question yet.</div>';
            }
            document.getElementById('drawerBody').innerHTML = '<div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Question</p><p class="mt-2 text-base font-medium text-slate-800">' + escapeHtml(question.question_text || '') + '</p></div><div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Assessment</p><p class="mt-2 text-sm font-medium text-slate-700">' + escapeHtml(question.assessment_title || '') + '</p></div><div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Type</p><p class="mt-2 text-sm font-medium text-slate-700">' + escapeHtml(question.question_type || '') + '</p></div>' + optionsHtml;
            document.getElementById('questionDrawer').classList.remove('hidden');
            document.getElementById('questionDrawer').classList.add('flex');
        });
}
function closeQuestionDrawer() {
    document.getElementById('questionDrawer').classList.add('hidden');
    document.getElementById('questionDrawer').classList.remove('flex');
}
function openDeleteModal(id) {
    document.getElementById('deleteQuestionId').value = id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
    document.getElementById('importModal').classList.add('flex');
}
function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('importModal').classList.remove('flex');
}
function escapeHtml(value) {
    var div = document.createElement('div');
    div.textContent = value || '';
    return div.innerHTML;
}
document.getElementById('selectAll')?.addEventListener('change', function() {
    var boxes = document.querySelectorAll('input[name="ids[]"]');
    boxes.forEach(function(box) { box.checked = document.getElementById('selectAll').checked; });
});
document.addEventListener('click', function(event) {
    if (event.target === document.getElementById('deleteModal')) closeDeleteModal();
    if (event.target === document.getElementById('importModal')) closeImportModal();
    if (event.target === document.getElementById('questionDrawer')) closeQuestionDrawer();
});
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
        closeImportModal();
        closeQuestionDrawer();
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

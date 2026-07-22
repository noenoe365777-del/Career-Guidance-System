<?php
$questions = $questions ?? [];
$assessments = $assessments ?? [];
$search = $search ?? '';
$categorySlug = $categorySlug ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;

$badgeColorMap = [
    'personality'   => 'bg-violet-50 text-violet-700 border-violet-200',
    'interest'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    'aptitude'      => 'bg-sky-50 text-sky-700 border-sky-200',
    'career_values' => 'bg-amber-50 text-amber-700 border-amber-200',
];

$slugMap = $slugMap ?? [];
$assessmentBadgeColors = [];
foreach ($slugMap as $slug => $id) {
    $assessmentBadgeColors[$id] = $badgeColorMap[$slug] ?? 'bg-slate-50 text-slate-600 border-slate-200';
}
?>
<?php if ($questions === []): ?>
<div class="rounded-2xl border border-dashed border-slate-200 bg-white p-16 text-center shadow-sm card-in d5">
    <div class="mx-auto h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center">
        <i class="bi bi-question-circle text-2xl text-slate-300"></i>
    </div>
    <h3 class="mt-4 text-lg font-semibold text-slate-800">No questions found</h3>
    <p class="mt-1.5 text-sm text-slate-500">Try adjusting the search or add a new question to the system.</p>
</div>
<?php else: ?>
<div class="card-in d6" style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;">
    <div class="overflow-x-auto">
        <table class="c-table">
            <thead>
                <tr>
                    <th style="min-width:180px;">Question</th>
                    <th>Assessment Type</th>
                    <th>Status</th>
                    <th style="white-space:nowrap;">Created Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $q):
                    $qId = (int)($q['question_id'] ?? 0);
                    $qText = (string)($q['question_text'] ?? '');
                    $aId = (int)($q['assessment_id'] ?? 0);
                    $aTitle = (string)($q['assessment_title'] ?? 'Unknown');
                    $qCreated = $q['created_at'] ?? null;
                    $qStatus = (string)($q['status'] ?? 'active');
                    $isActive = $qStatus === 'active';
                    $badgeColor = $assessmentBadgeColors[$aId] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                                <i class="bi bi-question-lg text-sm"></i>
                            </div>
                            <span class="font-medium text-slate-800"><?= htmlspecialchars($qText) ?></span>
                        </div>
                    </td>
                    <td><span class="assessment-badge <?= $badgeColor ?>"><?= htmlspecialchars($aTitle) ?></span></td>
                    <td>
                        <span class="badge <?= $isActive ? 'badge-active' : 'badge-incomplete' ?>">
                            <span class="badge-dot <?= $isActive ? 'active' : 'incomplete' ?>"></span>
                            <?= $isActive ? 'Active' : 'Incomplete' ?>
                        </span>
                    </td>
                    <td class="text-slate-500" style="font-size:0.82rem;white-space:nowrap;">
                        <?= $qCreated ? date('M j, Y', strtotime($qCreated)) : '—' ?>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-0.5">
                            <button type="button" onclick="openViewDrawer(<?= $qId ?>)" title="View" class="btn-action view"><i class="bi bi-eye"></i></button>
                            <a href="<?= BASE_URL ?>/index.php?page=admin-questions-edit&id=<?= $qId ?>" title="Edit" class="btn-action edit"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-questions-duplicate" class="m-0 inline-flex">
                                <input type="hidden" name="id" value="<?= $qId ?>">
                                <button type="submit" title="Duplicate" class="btn-action dup"><i class="bi bi-files"></i></button>
                            </form>
                            <button type="button" onclick="openDeleteModal(<?= $qId ?>, '<?= htmlspecialchars(addslashes(mb_strlen($qText) > 60 ? mb_substr($qText, 0, 60) . '...' : $qText)) ?>')" title="Delete" class="btn-action danger"><i class="bi bi-trash"></i></button>
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
            <a href="<?= BASE_URL ?>/index.php?page=admin-questions&page_number=<?= $currentPage - 1 ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?><?= $categorySlug !== '' ? '&category=' . urlencode($categorySlug) : '' ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-600 hover:bg-slate-50 no-underline"><i class="bi bi-chevron-left"></i> Prev</a>
            <?php endif; ?>
            <?php for ($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-questions&page_number=<?= $p ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?><?= $categorySlug !== '' ? '&category=' . urlencode($categorySlug) : '' ?>" class="inline-flex items-center justify-center min-w-[32px] h-[32px] rounded-lg text-xs font-semibold no-underline <?= $p === $currentPage ? 'bg-indigo-600 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>"><?= $p ?></a>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-questions&page_number=<?= $currentPage + 1 ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?><?= $categorySlug !== '' ? '&category=' . urlencode($categorySlug) : '' ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-600 hover:bg-slate-50 no-underline">Next <i class="bi bi-chevron-right"></i></a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
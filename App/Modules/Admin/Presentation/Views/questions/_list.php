<?php
if (!function_exists('truncateText')) {
    function truncateText($text, $max = 60) {
        if (strlen($text) <= $max) return $text;
        return substr($text, 0, $max) . '...';
    }
}

$categorySlug = $categorySlug ?? '';
$search = $search ?? '';
$questions = $questions ?? [];
$assessments = $assessments ?? [];
$assessmentNames = $assessmentNames ?? [];
$iconMap = $iconMap ?? [];
$slugMap = $slugMap ?? [];

$filterLabels = [
    'personality'   => 'Personality',
    'interest'      => 'Interest',
    'aptitude'      => 'Aptitude',
    'career_values' => 'Career Values',
];
$badgeColorMap = [
    'personality'   => 'bg-violet-50 text-violet-700 border-violet-200',
    'interest'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    'aptitude'      => 'bg-sky-50 text-sky-700 border-sky-200',
    'career_values' => 'bg-amber-50 text-amber-700 border-amber-200',
];
$badgeColors = [];
foreach ($slugMap as $slug => $id) {
    $badgeColors[$id] = $badgeColorMap[$slug] ?? 'bg-slate-50 text-slate-600 border-slate-200';
}
$categoryLabel = $categorySlug === '' || $categorySlug === 'all'
    ? 'All Questions'
    : ($filterLabels[$categorySlug] ?? ucfirst((string)$categorySlug)) . ' Questions';
?>
    <!-- Question Cards Grid -->
    <?php if ($questions === []): ?>
    <div class="rounded-xl border border-dashed border-slate-200 bg-white p-6 text-center shadow-sm">
        <div class="mx-auto h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center">
            <i class="bi bi-file-text text-lg text-slate-300"></i>
        </div>
        <p style="font-size: 0.85rem;" class="mt-2 text-slate-500">No <?= htmlspecialchars($categoryLabel) ?> found.</p>
    </div>
    <?php else: ?>
    <div class="grid gap-5 sm:grid-cols-2" style="gap: 1.25rem;" id="questionGrid">
        <?php
        $displayIndex = 0;
        foreach ($questions as $q):
            $qId = (int)($q['question_id'] ?? 0);
            $qText = (string)($q['question_text'] ?? '');
            $qType = (string)($q['question_type'] ?? '');
            $aId = (int)($q['assessment_id'] ?? 0);
            $qOrder = (int)($q['question_order'] ?? 0);
            $qCreated = $q['created_at'] ?? null;
            $aTitle = $assessmentNames[$aId] ?? (string)($q['assessment_title'] ?? 'Unknown');

            $badgeColor = $badgeColors[$aId] ?? 'bg-slate-50 text-slate-600 border-slate-200';
            $icon = $iconMap[$aId] ?? 'bi bi-question';
        ?>
        <div class="question-card anim-up d<?= 7 + min($displayIndex % 4, 2) ?>" style="animation-delay: <?= min(0.3, 0.035 * $displayIndex) ?>s;">
            <div class="flex items-start justify-between gap-3 mb-3">
                <span class="assessment-badge <?= $badgeColor ?>">
                    <i class="<?= $icon ?> text-xs"></i>
                    <?= htmlspecialchars($aTitle) ?>
                </span>
            </div>

            <p style="font-size: 1.05rem; line-height: 1.5;" class="font-medium text-slate-800"><?= htmlspecialchars($qText) ?></p>

            <div class="mt-4 flex items-center gap-4 text-xs text-slate-400">
                <?php if ($qOrder > 0): ?>
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-hash"></i>
                    Question #<?= $qOrder ?>
                </span>
                <?php endif; ?>
                <?php if ($qCreated): ?>
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-calendar3"></i>
                    <?= date('F j, Y', strtotime($qCreated)) ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end gap-1">
                <button type="button" onclick="openViewDrawer(<?= $qId ?>)" title="View" class="action-btn view"><i class="bi bi-eye"></i></button>
                <a href="<?= BASE_URL ?>/index.php?page=admin-questions-edit&id=<?= $qId ?>" title="Edit" class="action-btn edit"><i class="bi bi-pencil"></i></a>
                <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-questions-duplicate" class="m-0 inline-flex">
                    <input type="hidden" name="id" value="<?= $qId ?>">
                    <button type="submit" title="Duplicate" class="action-btn dup"><i class="bi bi-files"></i></button>
                </form>
                <button type="button" onclick="openDeleteModal(<?= $qId ?>, '<?= htmlspecialchars(addslashes(truncateText($qText, 60))) ?>')" title="Delete" class="action-btn danger"><i class="bi bi-trash"></i></button>
            </div>
        </div>
        <?php
        $displayIndex++;
        endforeach;
        ?>
    </div>
    <?php endif; ?>

<?php
$users = $users ?? [];
$search = $search ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$totalUsers = $totalUsers ?? 0;
$studentStats = $studentStats ?? [];
$educationLevels = $educationLevels ?? [];
$selectedEducationLevel = $selectedEducationLevel ?? null;

$hasStudents = count($users) > 0;

$totalStudents = (int)($studentStats['total_students'] ?? $totalUsers);
$highSchoolStudents = (int)($studentStats['high_school_students'] ?? 0);
$undergraduateStudents = (int)($studentStats['undergraduate_students'] ?? 0);
$graduateStudents = (int)($studentStats['graduate_students'] ?? 0);

$hsId = 0; $ugId = 0; $gId = 0;
foreach ($educationLevels as $el) {
    $label = strtolower((string)($el['label'] ?? ''));
    $id = (int)($el['id'] ?? 0);
    if (str_contains($label, 'high school')) $hsId = $id;
    elseif (str_contains($label, 'undergraduate')) $ugId = $id;
    elseif (str_contains($label, 'graduate')) $gId = $id;
}

ob_start();
if (file_exists(__DIR__ . '/partials/summary_stat_card.php')) {
    include __DIR__ . '/partials/summary_stat_card.php';
} elseif (file_exists(__DIR__ . '/../partials/summary_stat_card.php')) {
    include __DIR__ . '/../partials/summary_stat_card.php';
}

$initials = function (string $name): string {
    $name = trim($name);
    if ($name === '') return '?';
    $parts = preg_split('/\s+/', $name);
    if (count($parts) >= 2) return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1));
    return mb_strtoupper(mb_substr($name, 0, 2));
};

$cardDefs = [
    ['key' => 'total', 'label' => 'Total Students', 'count' => $totalStudents, 'icon' => 'bi-people-fill', 'bg' => '#eef2ff', 'color' => '#5B5FEF', 'id' => 0],
    ['key' => 'hs', 'label' => 'High School', 'count' => $highSchoolStudents, 'icon' => 'bi-mortarboard', 'bg' => '#ecfdf5', 'color' => '#059669', 'id' => $hsId],
    ['key' => 'ug', 'label' => 'Undergraduate', 'count' => $undergraduateStudents, 'icon' => 'bi-book', 'bg' => '#fffbeb', 'color' => '#d97706', 'id' => $ugId],
    ['key' => 'g', 'label' => 'Graduate', 'count' => $graduateStudents, 'icon' => 'bi-backpack', 'bg' => '#f3e8ff', 'color' => '#9333ea', 'id' => $gId],
];
?>
<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes iconBounce { 0% { transform: scale(1); } 25% { transform: scale(1.25) rotate(-5deg); } 50% { transform: scale(0.9) rotate(3deg); } 75% { transform: scale(1.1) rotate(-2deg); } 100% { transform: scale(1) rotate(0deg); } }
    @keyframes rowUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes slideRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes slideRightClose { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(50px); } }
    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    @keyframes iconBounce { 0% { transform: scale(1); } 25% { transform: scale(1.25) rotate(-5deg); } 50% { transform: scale(0.9) rotate(3deg); } 75% { transform: scale(1.1) rotate(-2deg); } 100% { transform: scale(1) rotate(0deg); } }

    .page-in { animation: fadeIn 0.5s ease-out both; }
    .up-in { animation: slideUp 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    .row-in { animation: rowUp 0.4s cubic-bezier(0.2,0.9,0.3,1) both; }

    .d1 { animation-delay: 0.05s; }
    .d2 { animation-delay: 0.1s; }
    .d3 { animation-delay: 0.15s; }
    .d4 { animation-delay: 0.2s; }

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
    .stat-card:hover .card-icon-bg {
        transform: scale(1.15) rotate(5deg);
    }
    .stat-card:hover .card-number {
        transform: scale(1.04);
    }
    .stat-card:active { transform: scale(0.97); }
    .stat-card.active {
        border-color: #5B5FEF;
        background: #f8f7ff;
        box-shadow: 0 8px 28px -8px rgba(91,95,239,0.22);
    }
    .stat-card.active .card-icon-bg {
        background: #5B5FEF !important;
        color: #fff !important;
    }
    .card-icon-bg {
        transition: transform 0.3s ease-out, background-color 0.3s ease-out, color 0.3s ease-out;
    }
    .card-number {
        transition: transform 0.3s ease-out;
    }
    .card-icon-bg.bounce {
        animation: iconBounce 0.5s cubic-bezier(0.22,1,0.36,1);
    }

    .student-card {
        border-radius: 12px; padding: 14px 18px;
        background: #fff;
        border: 1px solid #f1f5f9;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, opacity 0.25s ease;
    }
    .student-card:hover {
        transform: translateY(-2px) scale(1.01);
        box-shadow: 0 12px 28px -8px rgba(0,0,0,0.06);
        border-color: #e2e8f0;
    }
    .student-card.is-hidden { display: none !important; opacity: 0; }
    .student-card.student-card-extra { display: none !important; opacity: 0; }
    .student-card:not(.is-hidden):not(.student-card-extra) { display: flex !important; }
    .student-card.anim-in { animation: rowUp 0.4s cubic-bezier(0.2,0.9,0.3,1) both; }
    .student-card.anim-expand { animation: rowUp 0.35s cubic-bezier(0.22,1,0.36,1) both; }

    @media (max-width: 639px) {
        .student-card { flex-direction: column; align-items: flex-start !important; gap: 10px !important; padding: 16px !important; }
        .student-card .btn-view { align-self: flex-end; }
    }

    .btn-view {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 20px; border-radius: 10px;
        border: 1px solid #e2e8f0; background: #fff;
        font-size: 14px; font-weight: 600; color: #5B5FEF;
        cursor: pointer; transition: all 0.15s ease;
    }
    .btn-view:hover { background: #eef2ff; border-color: #5B5FEF; transform: scale(1.05); box-shadow: 0 4px 12px rgba(91,95,239,0.12); }
    .btn-view:active { transform: scale(0.96); }

    .btn-outline {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 18px; border-radius: 10px;
        border: 1.5px solid #e2e8f0; background: #fff;
        font-size: 14px; font-weight: 600; color: #475569;
        text-decoration: none; transition: all 0.15s ease;
    }
    .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; transform: scale(1.03); }
    .btn-outline:active { transform: scale(0.97); }

    .skeleton { background: linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 8px; }

    .drawer-overlay {
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(15,23,42,0.4);
        backdrop-filter: blur(4px);
        animation: fadeIn 0.2s ease-out both;
    }
    .drawer-panel {
        position: fixed; top: 0; right: 0; z-index: 10000;
        width: 100%; max-width: 440px; height: 100vh; height: 100dvh;
        background: #f8fafc;
        box-shadow: -8px 0 32px rgba(0,0,0,0.10);
        animation: slideRight 0.3s cubic-bezier(0.21,0.98,0.35,1) both;
        display: flex; flex-direction: column;
    }
    .drawer-panel.closing { animation: slideRightClose 0.2s ease-in both; }
    .drawer-scroll { flex: 1; overflow-y: auto; overscroll-behavior: contain; padding-bottom: 0; }
    .drawer-header {
        position: sticky; top: 0; z-index: 2;
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 24px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .drawer-close-btn {
        width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;
        border-radius: 10px; border: 1px solid #e2e8f0; background: #fff;
        color: #64748b; cursor: pointer; transition: all 0.15s ease; font-size: 13px;
    }
    .drawer-close-btn:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }
    .drawer-close-btn:active { transform: scale(0.94); }

    .drawer-profile-header {
        display: flex; align-items: center; gap: 16px;
        padding: 24px 24px 20px;
    }
    .drawer-avatar {
        width: 72px; height: 72px; border-radius: 50%; flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .drawer-section { padding: 0 24px; margin-bottom: 24px; }
    .drawer-section-title {
        font-size: 12px; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.05em;
        margin: 0 0 12px 0; display: flex; align-items: center; gap: 6px;
    }
    .drawer-card {
        background: #fff; border: 1px solid #e2e8f0;
        border-radius: 14px; padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .drawer-grid-2 {
        display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
        padding: 0 24px;
    }
    .drawer-info-card {
        background: #fff; border: 1px solid #e2e8f0;
        border-radius: 12px; padding: 14px 16px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .drawer-info-label {
        font-size: 11px; font-weight: 600; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.04em;
        display: flex; align-items: center; gap: 5px; margin: 0;
    }
    .drawer-info-value {
        font-size: 14px; font-weight: 700; color: #0f172a;
        margin: 6px 0 0 0; line-height: 1.3;
    }
    .drawer-check-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .drawer-check-label {
        font-size: 14px; font-weight: 500; color: #334155;
    }
    .drawer-score-card {
        border-radius: 14px; padding: 16px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .drawer-score-label {
        font-size: 11px; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .drawer-score-value {
        font-size: 24px; font-weight: 800; margin: 4px 0 0 0; line-height: 1.2;
    }
    .drawer-progress-track {
        height: 10px; background: #e2e8f0; border-radius: 999px; overflow: hidden;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.06);
    }
    .drawer-progress-fill {
        height: 100%; border-radius: 999px;
        background: linear-gradient(90deg, #5B5FEF, #818cf8);
        width: 0%; transition: width 0.8s cubic-bezier(0.22,1,0.36,1);
        box-shadow: 0 0 8px rgba(91,95,239,0.3);
    }
    .drawer-footer {
        position: sticky; bottom: 0;
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }
    .drawer-btn-close {
        display: block; width: 100%;
        padding: 12px 0; border-radius: 12px;
        border: 1px solid #e2e8f0; background: #fff;
        font-size: 15px; font-weight: 600; color: #475569;
        cursor: pointer; transition: all 0.15s ease;
    }
    .drawer-btn-close:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .drawer-btn-close:active { transform: scale(0.98); }
</style>

<div class="page-in" style="max-width: 1200px; margin: 0 auto; padding: 32px 24px;">
    <div class="up-in d1" style="display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 700; color: #0f172a; margin: 0;">User Management</h1>
            <p style="font-size: 15px; color: #64748b; margin: 8px 0 0 0;">Monitor registered students and assessment progress.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 24px; margin-bottom: 32px;" class="sm-g-2 lg-g-4">
        <?php foreach ($cardDefs as $cd):
            $isActive = ($cd['id'] === $selectedEducationLevel) || ($cd['key'] === 'total' && $selectedEducationLevel === null);
            $url = $cd['id'] > 0 ? '?page=admin-users&education_level=' . $cd['id'] : '?page=admin-users';
            if ($search !== '') $url .= '&search=' . urlencode($search);
        ?>
        <?php
            $delayClass = $cd['key'] === 'total' ? 'd1' : ($cd['key'] === 'hs' ? 'd2' : ($cd['key'] === 'ug' ? 'd3' : 'd4'));
            $counterId = 'count' . ucfirst($cd['key']);
            renderAdminSummaryCard([
                'title' => $cd['label'],
                'value' => '0',
                'valueNumber' => (int)($cd['count'] ?? 0),
                'counterId' => $counterId,
                'icon' => $cd['icon'],
                'iconBg' => $cd['bg'],
                'iconColor' => $cd['color'],
                'delayClass' => $delayClass,
                'filter' => $cd['key'],
                'active' => $isActive,
                'extraClass' => 'summary-stat-card',
            ]);
        ?>

        <?php endforeach; ?>
    </div>

    <div class="up-in d2" style="margin-bottom: 28px;">
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px 24px;">
            <div style="position: relative; max-width: 480px;">
                <span style="position: absolute; top: 50%; left: 14px; transform: translateY(-50%); color: #94a3b8; pointer-events: none; display: flex;">
                    <i class="bi bi-search" style="font-size: 16px;"></i>
                </span>
                <input type="text" id="searchInput" value="<?= htmlspecialchars($search) ?>"
                    placeholder="Search students by name, email or education level..."
                    style="width: 100%; padding: 11px 16px 11px 40px; font-size: 15px; color: #1e293b; background: #fff; border: 2px solid #e2e8f0; border-radius: 12px; outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;">
                <?php if ($search !== ''): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-users<?= $selectedEducationLevel ? '&education_level=' . $selectedEducationLevel : '' ?>"
                   style="position: absolute; top: 50%; right: 12px; transform: translateY(-50%); width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: 0; background: #f1f5f9; color: #94a3b8; cursor: pointer; text-decoration: none;">
                    <i class="bi bi-x" style="font-size: 14px; font-weight: 700;"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="up-in d3">
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px 28px 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
                <div>
                    <h2 style="font-size: 22px; font-weight: 700; color: #0f172a; margin: 0;">Registered Students</h2>
                    <p id="studentSubtitle" style="font-size: 14px; color: #64748b; margin: 4px 0 0 0;"></p>
                </div>
                <?php if (count($users) > 5): ?>
                <button type="button" onclick="toggleStudentView()" id="viewAllBtn" class="btn-outline" style="display: none;">
                    <i class="bi bi-people"></i> <span id="viewAllBtnText">View All</span>
                </button>
                <?php endif; ?>
            </div>

            <?php if (!$hasStudents): ?>
            <div style="padding: 56px 20px; text-align: center;">
                <div style="width: 56px; height: 56px; margin: 0 auto 16px; border-radius: 14px; background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-person-x" style="font-size: 24px; color: #94a3b8;"></i>
                </div>
                <p style="font-size: 16px; font-weight: 600; color: #475569; margin: 0;">No students found</p>
                <p style="font-size: 14px; color: #94a3b8; margin: 6px 0 0 0;">No registered students are available yet.</p>
            </div>
            <?php else: ?>
            <div id="studentList" style="display: flex; flex-direction: column; gap: 10px;">
                <?php foreach ($users as $ri => $student):
                    $uid = (int)($student['user_id'] ?? 0);
                    $name = (string)($student['username'] ?? '');
                    $email = (string)($student['email'] ?? '');
                    $edu = (string)($student['education_level'] ?? '');
                    $img = (string)($student['profile_image'] ?? '');
                    $created = $student['created_at'] ? date('M d, Y', strtotime((string)($student['created_at']))) : 'N/A';
                    $eduDisplay = $edu !== '' ? htmlspecialchars($edu, ENT_QUOTES, 'UTF-8') : 'No education level';
                    $nameDisplay = htmlspecialchars($name ?: 'Student', ENT_QUOTES, 'UTF-8');
                    $emailDisplay = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
                    $init = $initials($name);
                    $delay = 0.05 * $ri;
                ?>
                <div class="student-card row-in" style="animation-delay: <?= $delay ?>s; display: flex; align-items: center; gap: 16px; padding: 14px 18px;"
                     data-student-id="<?= $uid ?>"
                     data-index="<?= $ri ?>"
                     data-name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                     data-email="<?= $emailDisplay ?>"
                     data-education="<?= htmlspecialchars($edu, ENT_QUOTES, 'UTF-8') ?>"
                     onclick="openDrawer(<?= $uid ?>)">
                    <?php if ($img !== '' && file_exists(BASE_PATH . '/public/uploads/profile/' . $img)): ?>
                    <img src="<?= BASE_URL ?>/uploads/profile/<?= rawurlencode($img) ?>" alt="" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                    <?php else: ?>
                    <span style="width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 700; background: linear-gradient(135deg, #eef2ff, #f3e8ff); color: #5B5FEF; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.06);"><?= $init ?></span>
                    <?php endif; ?>
                    <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 4px;">
                        <p style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $nameDisplay ?></p>
                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                            <span style="font-size: 13px; font-weight: 500; color: <?= $edu ? '#64748b' : '#94a3b8' ?>; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-mortarboard-fill" style="font-size: 12px;"></i>
                                <?= $eduDisplay ?>
                            </span>
                            <span style="font-size: 12px; color: #94a3b8; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-calendar3" style="font-size: 11px;"></i>
                                <?= $created ?>
                            </span>
                        </div>
                    </div>
                    <button type="button" onclick="event.stopPropagation(); openDrawer(<?= $uid ?>)" class="btn-view" style="flex-shrink: 0;">
                        <i class="bi bi-eye"></i> View
                    </button>
                </div>
                <?php endforeach; ?>
                <div id="studentEmptyState" style="display: none; padding: 36px 20px; text-align: center; border: 1px dashed #e2e8f0; border-radius: 12px; background: #f8fafc;">
                    <div style="width: 48px; height: 48px; margin: 0 auto 12px; border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-search" style="font-size: 20px; color: #94a3b8;"></i>
                    </div>
                    <p style="font-size: 15px; font-weight: 600; color: #475569; margin: 0;">No students match your criteria</p>
                    <p style="font-size: 13px; color: #94a3b8; margin: 4px 0 0 0;">Try adjusting your search or filter.</p>
                </div>
            </div>

            <?php if ($totalPages > 1): ?>
            <div style="display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                <?php
                $pageBaseUrl = BASE_URL . '/index.php?page=admin-users';
                if ($search !== '') $pageBaseUrl .= '&search=' . urlencode($search);
                if ($selectedEducationLevel !== null && $selectedEducationLevel > 0) $pageBaseUrl .= '&education_level=' . $selectedEducationLevel;
                ?>
                <?php if ($currentPage > 1): ?>
                <a href="<?= $pageBaseUrl . '&page_number=' . ($currentPage - 1) ?>" style="display: inline-flex; align-items: center; gap: 4px; padding: 8px 14px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; font-size: 14px; font-weight: 600; color: #475569; text-decoration: none; transition: all 0.15s ease;">
                    <i class="bi bi-chevron-left" style="font-size: 12px;"></i> Prev
                </a>
                <?php else: ?>
                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 8px 14px; border-radius: 10px; border: 1px solid #f1f5f9; background: #f8fafc; font-size: 14px; font-weight: 600; color: #cbd5e1; cursor: default;">
                    <i class="bi bi-chevron-left" style="font-size: 12px;"></i> Prev
                </span>
                <?php endif; ?>

                <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                if ($startPage > 1): ?>
                <a href="<?= $pageBaseUrl . '&page_number=1' ?>" style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; font-size: 14px; font-weight: 600; color: #475569; text-decoration: none; transition: all 0.15s ease;">1</a>
                <?php if ($startPage > 2): ?>
                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; font-size: 14px; color: #94a3b8;">...</span>
                <?php endif; endif; ?>

                <?php for ($p = $startPage; $p <= $endPage; $p++): ?>
                <?php if ($p === $currentPage): ?>
                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; border-radius: 10px; border: 1px solid #5B5FEF; background: #5B5FEF; font-size: 14px; font-weight: 700; color: #fff; box-shadow: 0 2px 8px rgba(91,95,239,0.25);"><?= $p ?></span>
                <?php else: ?>
                <a href="<?= $pageBaseUrl . '&page_number=' . $p ?>" style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; font-size: 14px; font-weight: 600; color: #475569; text-decoration: none; transition: all 0.15s ease;"><?= $p ?></a>
                <?php endif; endfor; ?>

                <?php if ($endPage < $totalPages): ?>
                <?php if ($endPage < $totalPages - 1): ?>
                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; font-size: 14px; color: #94a3b8;">...</span>
                <?php endif; ?>
                <a href="<?= $pageBaseUrl . '&page_number=' . $totalPages ?>" style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; font-size: 14px; font-weight: 600; color: #475569; text-decoration: none; transition: all 0.15s ease;"><?= $totalPages ?></a>
                <?php endif; ?>

                <?php if ($currentPage < $totalPages): ?>
                <a href="<?= $pageBaseUrl . '&page_number=' . ($currentPage + 1) ?>" style="display: inline-flex; align-items: center; gap: 4px; padding: 8px 14px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; font-size: 14px; font-weight: 600; color: #475569; text-decoration: none; transition: all 0.15s ease;">
                    Next <i class="bi bi-chevron-right" style="font-size: 12px;"></i>
                </a>
                <?php else: ?>
                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 8px 14px; border-radius: 10px; border: 1px solid #f1f5f9; background: #f8fafc; font-size: 14px; font-weight: 600; color: #cbd5e1; cursor: default;">
                    Next <i class="bi bi-chevron-right" style="font-size: 12px;"></i>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<div id="drawerOverlay" class="drawer-overlay hidden" onclick="closeDrawer()"></div>
<div id="drawerPanel" class="drawer-panel hidden">
    <div class="drawer-scroll">
        <div class="drawer-header">
            <p style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Student Profile</p>
            <button onclick="closeDrawer()" class="drawer-close-btn" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div id="drawerSkeleton" class="hidden" style="display: none;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                <div class="skeleton" style="width: 72px; height: 72px; border-radius: 50%; flex-shrink: 0;"></div>
                <div style="flex: 1;">
                    <div class="skeleton" style="height: 20px; width: 160px; margin-bottom: 8px;"></div>
                    <div class="skeleton" style="height: 13px; width: 220px; margin-bottom: 6px;"></div>
                    <div class="skeleton" style="height: 13px; width: 140px;"></div>
                </div>
            </div>
            <div class="skeleton" style="height: 14px; width: 100%; margin-bottom: 10px;"></div>
            <div class="skeleton" style="height: 14px; width: 100%; margin-bottom: 10px;"></div>
            <div class="skeleton" style="height: 14px; width: 75%;"></div>
        </div>

        <div id="drawerBody" style="display: none;">

            <!-- Profile Header -->
            <div class="drawer-profile-header">
                <div id="drawerAvatar" class="drawer-avatar"></div>
                <div style="min-width: 0; flex: 1;">
                    <h3 id="drawerName" style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.3;"></h3>
                    <p id="drawerEmail" style="font-size: 13px; color: #64748b; margin: 4px 0 0 0; word-break: break-all;"></p>
                    <p id="drawerPhone" style="font-size: 13px; color: #94a3b8; margin: 3px 0 0 0;"></p>
                </div>
            </div>

            <!-- Info Cards -->
            <div class="drawer-grid-2" style="margin-bottom: 24px;">
                <div class="drawer-info-card">
                    <span class="drawer-info-label"><i class="bi bi-mortarboard-fill" style="font-size: 11px;"></i> Education Level</span>
                    <p id="drawerEducation" class="drawer-info-value"></p>
                </div>
                <div class="drawer-info-card">
                    <span class="drawer-info-label"><i class="bi bi-calendar3" style="font-size: 11px;"></i> Registered</span>
                    <p id="drawerRegistered" class="drawer-info-value"></p>
                </div>
            </div>

            <!-- Assessment Progress -->
            <div class="drawer-section">
                <p class="drawer-section-title"><i class="bi bi-clipboard2-check" style="font-size: 13px;"></i> Assessment Progress</p>
                <div class="drawer-card" style="padding: 0; overflow: hidden;">
                    <div class="drawer-check-row">
                        <span class="drawer-check-label">Interest</span>
                        <span id="drawerInterestStatus" class="chk-badge"></span>
                    </div>
                    <div class="drawer-check-row">
                        <span class="drawer-check-label">Personality</span>
                        <span id="drawerPersonalityStatus" class="chk-badge"></span>
                    </div>
                    <div class="drawer-check-row">
                        <span class="drawer-check-label">Aptitude</span>
                        <span id="drawerAptitudeStatus" class="chk-badge"></span>
                    </div>
                    <div class="drawer-check-row" style="border-bottom: none;">
                        <span class="drawer-check-label">Work Values</span>
                        <span id="drawerValuesStatus" class="chk-badge"></span>
                    </div>
                </div>
            </div>

            <!-- Assessment Scores -->
            <div class="drawer-section">
                <p class="drawer-section-title"><i class="bi bi-bar-chart-line" style="font-size: 13px;"></i> Assessment Scores</p>
                <div class="drawer-grid-2">
                    <div class="drawer-score-card" style="background: linear-gradient(135deg, #eef2ff, #f0f0ff);">
                        <span class="drawer-score-label" style="color: #818cf8;">Interest</span>
                        <p id="drawerInterestScore" class="drawer-score-value" style="color: #4f46e5;"></p>
                    </div>
                    <div class="drawer-score-card" style="background: linear-gradient(135deg, #ecfdf5, #f0fdf8);">
                        <span class="drawer-score-label" style="color: #34d399;">Personality</span>
                        <p id="drawerPersonalityScore" class="drawer-score-value" style="color: #059669;"></p>
                    </div>
                    <div class="drawer-score-card" style="background: linear-gradient(135deg, #ecfeff, #f0feff);">
                        <span class="drawer-score-label" style="color: #22d3ee;">Aptitude</span>
                        <p id="drawerAptitudeScore" class="drawer-score-value" style="color: #0891b2;"></p>
                    </div>
                    <div class="drawer-score-card" style="background: linear-gradient(135deg, #fffbeb, #fffdf5);">
                        <span class="drawer-score-label" style="color: #fbbf24;">Work Values</span>
                        <p id="drawerValuesScore" class="drawer-score-value" style="color: #d97706;"></p>
                    </div>
                </div>
            </div>

            <!-- Completion -->
            <div class="drawer-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <p class="drawer-section-title" style="margin: 0;"><i class="bi bi-graph-up-arrow" style="font-size: 13px;"></i> Assessment Completion</p>
                    <span id="drawerCompletionPct" style="font-size: 14px; font-weight: 700; color: #5B5FEF;"></span>
                </div>
                <div class="drawer-progress-track">
                    <div id="drawerProgressFill" class="drawer-progress-fill"></div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 6px;">
                    <span id="drawerCompletionDetail" style="font-size: 12px; color: #94a3b8;"></span>
                </div>
            </div>

            <!-- Career Recommendation -->
            <div class="drawer-section">
                <p class="drawer-section-title"><i class="bi bi-award" style="font-size: 13px;"></i> Career Recommendation</p>
                <div id="drawerCareerCard" class="drawer-card">
                    <div id="drawerCareerHas" style="display: none;">
                        <p id="drawerCareer" style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0;"></p>
                        <div style="display: flex; align-items: center; gap: 6px; margin-top: 8px;">
                            <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Match Score</span>
                            <span id="drawerCareerScore" style="font-size: 18px; font-weight: 800; color: #5B5FEF;"></span>
                        </div>
                    </div>
                    <div id="drawerCareerNone" style="display: none; text-align: center; padding: 8px 0;">
                        <div style="width: 40px; height: 40px; margin: 0 auto 10px; border-radius: 10px; background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-compass" style="font-size: 18px; color: #94a3b8;"></i>
                        </div>
                        <p style="font-size: 14px; font-weight: 600; color: #94a3b8; margin: 0;">No recommendation available</p>
                        <p style="font-size: 12px; color: #cbd5e1; margin: 4px 0 0 0;">Complete all assessments to receive a career match</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Info -->
            <div class="drawer-grid-2" style="margin-bottom: 24px;">
                <div class="drawer-info-card">
                    <span class="drawer-info-label"><i class="bi bi-clock-history" style="font-size: 11px;"></i> Latest Login</span>
                    <p id="drawerLastLogin" class="drawer-info-value" style="font-size: 13px;"></p>
                </div>
                <div class="drawer-info-card">
                    <span class="drawer-info-label"><i class="bi bi-shield-check" style="font-size: 11px;"></i> Account Status</span>
                    <p id="drawerStatus" class="drawer-info-value" style="font-size: 13px;"></p>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="drawer-footer">
            <button onclick="closeDrawer()" class="drawer-btn-close">Close</button>
        </div>
    </div>
</div>

<style>
    @media (min-width: 640px) {
        .sm-g-2 { grid-template-columns: repeat(2, 1fr) !important; }
    }
    @media (min-width: 768px) {
        .md-show { display: block !important; }
    }
    @media (min-width: 1024px) {
        .lg-g-4 { grid-template-columns: repeat(4, 1fr) !important; }
    }
    input#searchInput:focus {
        border-color: #5B5FEF !important;
        box-shadow: 0 0 0 3px rgba(91,95,239,0.10) !important;
    }
    #drawerSkeleton.hidden, #drawerBody.hidden { display: none !important; }
    #drawerSkeleton:not(.hidden) { display: block !important; }
    #drawerBody:not(.hidden) { display: block !important; }
    .drawer-overlay.hidden, .drawer-panel.hidden { display: none !important; }

    .chk-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }
    .chk-badge.done { background: #ecfdf5; color: #059669; }
    .chk-badge.pending { background: #f1f5f9; color: #94a3b8; }

    @media (max-width: 479px) {
        .drawer-panel { max-width: 100%; }
        .drawer-profile-header { padding: 20px 20px 16px; }
        .drawer-section { padding: 0 20px; }
        .drawer-grid-2 { padding: 0 20px; }
        .drawer-header { padding: 16px 20px 14px; }
        .drawer-footer { padding: 14px 20px; }
        .drawer-score-value { font-size: 20px; }
    }
</style>

<script>
var studentCards = Array.from(document.querySelectorAll('.student-card'));
var studentEmptyState = document.getElementById('studentEmptyState');
var currentFilter = 'all';
var currentSearch = '';
var showAllStudents = false;
var INITIAL_LIMIT = 5;

function getInitials(name) {
    if (!name || name.trim() === '') return '?';
    var parts = name.trim().split(/\s+/);
    if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    return name.substring(0, 2).toUpperCase();
}

function openDrawer(userId) {
    var overlay = document.getElementById('drawerOverlay');
    var panel = document.getElementById('drawerPanel');
    var skeleton = document.getElementById('drawerSkeleton');
    var body = document.getElementById('drawerBody');
    overlay.classList.remove('hidden');
    panel.classList.remove('hidden', 'closing');
    skeleton.classList.remove('hidden');
    body.classList.add('hidden');
    document.body.style.overflow = 'hidden';

    var progressFill = document.getElementById('drawerProgressFill');
    if (progressFill) progressFill.style.width = '0%';

    fetch('<?= BASE_URL ?>/index.php?page=admin-users-view&id=' + userId)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            skeleton.classList.add('hidden');
            body.classList.remove('hidden');

            var imgHtml;
            if (data.profile_image && data.profile_image.trim() !== '') {
                imgHtml = '<img src="<?= BASE_URL ?>/uploads/profile/' + encodeURIComponent(data.profile_image) + '" alt="" style="width:72px;height:72px;border-radius:50%;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,0.08);">';
            } else {
                imgHtml = '<span style="width:72px;height:72px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;background:linear-gradient(135deg,#eef2ff,#f3e8ff);color:#5B5FEF;box-shadow:0 4px 12px rgba(0,0,0,0.08);">' + getInitials(data.username) + '</span>';
            }
            document.getElementById('drawerAvatar').innerHTML = imgHtml;
            document.getElementById('drawerName').textContent = data.username || 'Student';
            document.getElementById('drawerEmail').textContent = data.email || '';
            document.getElementById('drawerPhone').textContent = data.phone ? data.phone : 'No phone number';
            document.getElementById('drawerEducation').textContent = data.education_level || 'N/A';
            var regDate = data.created_at ? new Date(data.created_at) : null;
            document.getElementById('drawerRegistered').textContent = regDate ? regDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
            document.getElementById('drawerLastLogin').textContent = data.last_login ? new Date(data.last_login).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'Not available';
            document.getElementById('drawerStatus').textContent = data.status_name || 'Active';

            function setStatus(el, completed) {
                el.innerHTML = completed ? '<i class="bi bi-check-circle-fill"></i> Completed' : '<i class="bi bi-circle"></i> Pending';
                el.className = 'chk-badge ' + (completed ? 'done' : 'pending');
            }
            setStatus(document.getElementById('drawerInterestStatus'), parseInt(data.interest_completed || 0) === 1);
            setStatus(document.getElementById('drawerPersonalityStatus'), parseInt(data.personality_completed || 0) === 1);
            setStatus(document.getElementById('drawerAptitudeStatus'), parseInt(data.aptitude_completed || 0) === 1);
            setStatus(document.getElementById('drawerValuesStatus'), parseInt(data.values_completed || 0) === 1);

            function fmt(v) { var n = parseFloat(v); return !isNaN(n) ? n.toFixed(1) + '%' : 'Pending'; }
            document.getElementById('drawerInterestScore').textContent = fmt(data.interest_score);
            document.getElementById('drawerPersonalityScore').textContent = fmt(data.personality_score);
            document.getElementById('drawerAptitudeScore').textContent = fmt(data.aptitude_score);
            document.getElementById('drawerValuesScore').textContent = fmt(data.values_score);

            var completed = parseInt(data.completed_count || 0);
            var total = parseInt(data.total_count || 0);
            var pct = total > 0 ? Math.round((completed / total) * 100) : 0;
            document.getElementById('drawerCompletionPct').textContent = pct + '%';
            document.getElementById('drawerCompletionDetail').textContent = completed + ' of ' + total + ' assessments completed';
            setTimeout(function() { progressFill.style.width = pct + '%'; }, 60);

            if (data.top_career) {
                document.getElementById('drawerCareerHas').style.display = 'block';
                document.getElementById('drawerCareerNone').style.display = 'none';
                document.getElementById('drawerCareer').textContent = data.top_career;
                document.getElementById('drawerCareerScore').textContent = fmt(data.match_score);
            } else {
                document.getElementById('drawerCareerHas').style.display = 'none';
                document.getElementById('drawerCareerNone').style.display = 'block';
            }
        })
        .catch(function() {
            skeleton.classList.add('hidden');
            body.classList.remove('hidden');
            document.getElementById('drawerName').textContent = 'Error loading student data';
        });
}

function closeDrawer() {
    var panel = document.getElementById('drawerPanel');
    panel.classList.add('closing');
    setTimeout(function() {
        document.getElementById('drawerOverlay').classList.add('hidden');
        panel.classList.add('hidden');
        panel.classList.remove('closing');
        document.body.style.overflow = '';
    }, 250);
}

function updateSubtitle() {
    var subtitle = document.getElementById('studentSubtitle');
    if (!subtitle) return;
    var searchValue = (document.getElementById('searchInput') ? document.getElementById('searchInput').value : '').trim();
    var totalLoaded = studentCards.length;
    if (searchValue) {
        var visibleCount = 0;
        studentCards.forEach(function(c) { if (!c.classList.contains('is-hidden')) visibleCount++; });
        subtitle.textContent = 'Showing ' + visibleCount + ' result' + (visibleCount !== 1 ? 's' : '') + ' for "' + searchValue + '"';
    } else if (showAllStudents) {
        subtitle.textContent = 'Showing all ' + totalLoaded + ' registered student' + (totalLoaded !== 1 ? 's' : '');
    } else {
        subtitle.textContent = 'Latest 5 registered students';
    }
}

function toggleStudentView() {
    showAllStudents = !showAllStudents;
    var btnText = document.getElementById('viewAllBtnText');
    if (showAllStudents) {
        btnText.textContent = 'View Less';
    } else {
        btnText.textContent = 'View All';
    }
    applyStudentFilters();
}

function applyExtraCardsVisibility() {
    var searchValue = (document.getElementById('searchInput') ? document.getElementById('searchInput').value : '').trim();
    var searchActive = searchValue !== '';
    var limit = INITIAL_LIMIT;
    var visibleIndex = 0;

    studentCards.forEach(function(card) {
        if (card.classList.contains('is-hidden')) return;

        if (!showAllStudents && !searchActive && visibleIndex >= limit) {
            card.classList.add('student-card-extra');
            card.style.display = 'none';
        } else {
            card.classList.remove('student-card-extra');
            card.style.display = 'flex';
        }
        visibleIndex++;
    });
}

function applyStudentFilters() {
    var searchValue = (document.getElementById('searchInput') ? document.getElementById('searchInput').value : '').trim().toLowerCase();
    var visibleCount = 0;
    var animIndex = 0;

    if (!studentCards.length) {
        updateSubtitle();
        return;
    }

    studentCards.forEach(function(card) {
        var name = (card.getAttribute('data-name') || '').toLowerCase();
        var email = (card.getAttribute('data-email') || '').toLowerCase();
        var education = (card.getAttribute('data-education') || '').toLowerCase();
        var shouldShow = true;

        if (currentFilter === 'hs' && !education.includes('high school')) {
            shouldShow = false;
        } else if (currentFilter === 'ug' && !education.includes('undergraduate')) {
            shouldShow = false;
        } else if (currentFilter === 'g' && !education.includes('graduate')) {
            shouldShow = false;
        }

        if (shouldShow && searchValue) {
            shouldShow = name.includes(searchValue) || email.includes(searchValue) || education.includes(searchValue);
        }

        if (shouldShow) {
            visibleCount++;
            card.classList.remove('is-hidden');
            card.classList.remove('anim-in');
            card.style.animationDelay = (0.05 * animIndex) + 's';
            animIndex++;
            void card.offsetWidth;
            card.classList.add('anim-in');
        } else {
            card.classList.add('is-hidden');
            card.style.display = 'none';
        }
    });

    applyExtraCardsVisibility();
    updateSubtitle();

    if (studentEmptyState) {
        studentEmptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

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

(function() {
    var input = document.getElementById('searchInput');
    if (!input) return;

    var activeCard = document.querySelector('.summary-stat-card.active');
    var hasSearch = input.value.trim() !== '';
    if (activeCard || hasSearch) {
        if (activeCard) {
            currentFilter = activeCard.getAttribute('data-filter') || 'all';
        }
        applyStudentFilters();
    } else {
        applyStudentFilters();
    }

    document.querySelectorAll('.stat-card[data-filter]').forEach(function(card) {
        card.addEventListener('click', function() {
            currentFilter = card.getAttribute('data-filter') || 'all';
            document.querySelectorAll('.stat-card[data-filter]').forEach(function(item) {
                item.classList.toggle('active', item === card);
            });
            applyStudentFilters();
        });
    });

    input.addEventListener('input', function() {
        applyStudentFilters();
    });

    var viewAllBtn = document.getElementById('viewAllBtn');
    if (viewAllBtn && studentCards.length > INITIAL_LIMIT) {
        viewAllBtn.style.display = 'inline-flex';
    }
})();
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

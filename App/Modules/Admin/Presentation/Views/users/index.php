<?php
$users = $users ?? [];
$search = $search ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$totalUsers = $totalUsers ?? 0;
$studentStats = $studentStats ?? [];
$recentStudents = $recentStudents ?? [];
$educationLevels = $educationLevels ?? [];
$selectedEducationLevel = $selectedEducationLevel ?? null;

$hasStudents = count($recentStudents) > 0;

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
    .student-card.is-hidden {
        display: none !important;
        opacity: 0;
    }
    .student-card:not(.is-hidden) {
        display: flex !important;
    }
    .student-card.anim-in {
        animation: rowUp 0.4s cubic-bezier(0.2,0.9,0.3,1) both;
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
        background: rgba(15,23,42,0.35);
        backdrop-filter: blur(4px);
        animation: fadeIn 0.2s ease-out both;
    }
    .drawer-panel {
        position: fixed; top: 0; right: 0; z-index: 10000;
        width: 100%; max-width: 480px; height: 100vh;
        background: #fff;
        box-shadow: -10px 0 40px rgba(0,0,0,0.07);
        overflow-y: auto;
        animation: slideRight 0.35s cubic-bezier(0.21,0.98,0.35,1) both;
    }
    .drawer-panel.closing { animation: slideRightClose 0.25s ease-in both; }

    .chk-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 13px; font-weight: 600; padding: 3px 10px; border-radius: 6px; }
    .chk-badge.done { background: #ecfdf5; color: #059669; }
    .chk-badge.pending { background: #f1f5f9; color: #94a3b8; }
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
                    <p style="font-size: 14px; color: #64748b; margin: 4px 0 0 0;">Latest 5 registered students</p>
                </div>
                <?php if ($totalPages > 1 || $totalUsers > 5): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-users<?= $search ? '&search=' . urlencode($search) : '' ?><?= $selectedEducationLevel ? '&education_level=' . $selectedEducationLevel : '' ?>" class="btn-outline">
                    <i class="bi bi-people"></i> View All
                </a>
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
                <?php $ri = 0; ?>
                <?php foreach ($recentStudents as $student):
                    $ri++;
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
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="drawerOverlay" class="drawer-overlay hidden" onclick="closeDrawer()"></div>
<div id="drawerPanel" class="drawer-panel hidden">
    <div style="padding: 28px 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
            <p style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Student Profile</p>
            <button onclick="closeDrawer()" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 10px; border: 0; background: #f1f5f9; color: #64748b; cursor: pointer;">
                <i class="bi bi-x-lg" style="font-size: 12px;"></i>
            </button>
        </div>

        <div id="drawerSkeleton" class="hidden" style="display: none;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                <div class="skeleton" style="width: 64px; height: 64px; border-radius: 50%; flex-shrink: 0;"></div>
                <div style="flex: 1;">
                    <div class="skeleton" style="height: 20px; width: 180px; margin-bottom: 8px;"></div>
                    <div class="skeleton" style="height: 14px; width: 240px;"></div>
                </div>
            </div>
            <div class="skeleton" style="height: 16px; width: 100%; margin-bottom: 8px;"></div>
            <div class="skeleton" style="height: 16px; width: 75%;"></div>
        </div>

        <div id="drawerBody" style="display: none;">
            <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; margin-bottom: 20px;">
                <div id="drawerAvatar" style="width: 64px; height: 64px; border-radius: 50%; flex-shrink: 0;"></div>
                <div style="min-width: 0; flex: 1;">
                    <h3 id="drawerName" style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0;"></h3>
                    <p id="drawerEmail" style="font-size: 14px; color: #64748b; margin: 4px 0 0 0;"></p>
                    <p id="drawerPhone" style="font-size: 13px; color: #94a3b8; margin: 4px 0 0 0;"></p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                <div style="background: #f8fafc; border-radius: 12px; padding: 14px;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; margin: 0;">Education Level</p>
                    <p id="drawerEducation" style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 6px 0 0 0;"></p>
                </div>
                <div style="background: #f8fafc; border-radius: 12px; padding: 14px;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; margin: 0;">Registration Date</p>
                    <p id="drawerRegistered" style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 6px 0 0 0;"></p>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <p style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; margin: 0 0 12px 0;">Assessment Progress</p>
                <div style="border: 1px solid #f1f5f9; border-radius: 12px; overflow: hidden;">
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 14px; font-weight: 500; color: #334155;">Interest Assessment</span>
                        <span id="drawerInterestStatus" class="chk-badge"></span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 14px; font-weight: 500; color: #334155;">Personality Assessment</span>
                        <span id="drawerPersonalityStatus" class="chk-badge"></span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 14px; font-weight: 500; color: #334155;">Aptitude Assessment</span>
                        <span id="drawerAptitudeStatus" class="chk-badge"></span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px;">
                        <span style="font-size: 14px; font-weight: 500; color: #334155;">Work Values</span>
                        <span id="drawerValuesStatus" class="chk-badge"></span>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <p style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; margin: 0 0 12px 0;">Assessment Scores</p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div style="background: linear-gradient(135deg, #eef2ff, #eef2ff 80%); border-radius: 12px; padding: 14px;">
                        <p style="font-size: 11px; font-weight: 600; color: #818cf8; text-transform: uppercase; letter-spacing: 0.04em; margin: 0;">Interest</p>
                        <p id="drawerInterestScore" style="font-size: 22px; font-weight: 700; color: #4f46e5; margin: 6px 0 0 0;"></p>
                    </div>
                    <div style="background: linear-gradient(135deg, #ecfdf5, #ecfdf5 80%); border-radius: 12px; padding: 14px;">
                        <p style="font-size: 11px; font-weight: 600; color: #34d399; text-transform: uppercase; letter-spacing: 0.04em; margin: 0;">Personality</p>
                        <p id="drawerPersonalityScore" style="font-size: 22px; font-weight: 700; color: #059669; margin: 6px 0 0 0;"></p>
                    </div>
                    <div style="background: linear-gradient(135deg, #ecfeff, #ecfeff 80%); border-radius: 12px; padding: 14px;">
                        <p style="font-size: 11px; font-weight: 600; color: #22d3ee; text-transform: uppercase; letter-spacing: 0.04em; margin: 0;">Aptitude</p>
                        <p id="drawerAptitudeScore" style="font-size: 22px; font-weight: 700; color: #0891b2; margin: 6px 0 0 0;"></p>
                    </div>
                    <div style="background: linear-gradient(135deg, #fffbeb, #fffbeb 80%); border-radius: 12px; padding: 14px;">
                        <p style="font-size: 11px; font-weight: 600; color: #fbbf24; text-transform: uppercase; letter-spacing: 0.04em; margin: 0;">Work Values</p>
                        <p id="drawerValuesScore" style="font-size: 22px; font-weight: 700; color: #d97706; margin: 6px 0 0 0;"></p>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <p style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; margin: 0;">Assessment Completion</p>
                    <span id="drawerCompletionPct" style="font-size: 14px; font-weight: 700; color: #0f172a;"></span>
                </div>
                <div style="height: 8px; background: #f1f5f9; border-radius: 999px; overflow: hidden;">
                    <div id="drawerProgressFill" style="height: 100%; border-radius: 999px; background: #5B5FEF; width: 0%; transition: width 0.6s ease;"></div>
                </div>
            </div>

            <div style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                <p style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; margin: 0 0 8px 0;">Recommended Career</p>
                <div id="drawerCareerBlock">
                    <p id="drawerCareer" style="font-size: 17px; font-weight: 700; color: #0f172a; margin: 0;"></p>
                    <p id="drawerCareerScore" style="font-size: 14px; color: #64748b; margin: 4px 0 0 0;"></p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                <div style="background: #f8fafc; border-radius: 12px; padding: 14px;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; margin: 0;">Latest Login</p>
                    <p id="drawerLastLogin" style="font-size: 14px; font-weight: 600; color: #0f172a; margin: 6px 0 0 0;"></p>
                </div>
                <div style="background: #f8fafc; border-radius: 12px; padding: 14px;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; margin: 0;">Account Status</p>
                    <p id="drawerStatus" style="font-size: 14px; font-weight: 600; color: #0f172a; margin: 6px 0 0 0;"></p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <button onclick="closeDrawer()" style="padding: 12px 0; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff; font-size: 15px; font-weight: 600; color: #475569; cursor: pointer;">
                    Close
                </button>
                <button type="button" style="padding: 12px 0; border-radius: 12px; border: 1px solid #5B5FEF; background: #fff; font-size: 15px; font-weight: 600; color: #5B5FEF; cursor: pointer;">
                    Edit Student
                </button>
            </div>
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
</style>

<script>
var studentCards = Array.from(document.querySelectorAll('.student-card'));
var studentEmptyState = document.getElementById('studentEmptyState');
var currentFilter = 'all';
var currentSearch = '';

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

    fetch('<?= BASE_URL ?>/index.php?page=admin-users-view&id=' + userId)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            skeleton.classList.add('hidden');
            body.classList.remove('hidden');

            var imgHtml;
            if (data.profile_image && data.profile_image.trim() !== '') {
                imgHtml = '<img src="<?= BASE_URL ?>/uploads/profile/' + encodeURIComponent(data.profile_image) + '" alt="" style="width:64px;height:64px;border-radius:50%;object-fit:cover;box-shadow:0 2px 8px rgba(0,0,0,0.06);">';
            } else {
                imgHtml = '<span style="width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;background:linear-gradient(135deg,#eef2ff,#f3e8ff);color:#5B5FEF;box-shadow:0 2px 8px rgba(0,0,0,0.06);">' + getInitials(data.username) + '</span>';
            }
            document.getElementById('drawerAvatar').innerHTML = imgHtml;
            document.getElementById('drawerName').textContent = data.username || 'Student';
            document.getElementById('drawerEmail').textContent = data.email || '';
            document.getElementById('drawerPhone').textContent = data.phone ? 'Phone: ' + data.phone : 'Phone: N/A';
            document.getElementById('drawerEducation').textContent = data.education_level || 'N/A';
            var regDate = data.created_at ? new Date(data.created_at) : null;
            document.getElementById('drawerRegistered').textContent = regDate ? regDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
            document.getElementById('drawerLastLogin').textContent = data.last_login ? new Date(data.last_login).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'N/A';
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
            document.getElementById('drawerCompletionPct').textContent = completed + ' / ' + total + ' (' + pct + '%)';
            document.getElementById('drawerProgressFill').style.width = pct + '%';

            if (data.top_career) {
                document.getElementById('drawerCareer').textContent = data.top_career;
                document.getElementById('drawerCareerScore').textContent = 'Recommendation Score: ' + fmt(data.match_score);
            } else {
                document.getElementById('drawerCareer').textContent = 'No recommendation yet';
                document.getElementById('drawerCareerScore').textContent = '';
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

function applyStudentFilters() {
    var searchValue = (document.getElementById('searchInput') ? document.getElementById('searchInput').value : '').trim().toLowerCase();
    var visibleCount = 0;
    var animIndex = 0;

    if (!studentCards.length) {
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
            card.style.display = 'flex';
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
})();
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

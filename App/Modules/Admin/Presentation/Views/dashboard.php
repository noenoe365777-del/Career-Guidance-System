<?php
$admin = $admin ?? [];
$adminName = trim((string)($admin['full_name'] ?? $admin['username'] ?? 'Admin'));
$totalStudents = (int)($totalStudents ?? 0);
$totalAssessments = (int)($totalAssessments ?? 0);
$totalCareers = (int)($totalCareers ?? 0);
$totalQuestions = (int)($totalQuestions ?? 0);
$totalRecommendations = (int)($totalRecommendations ?? 0);
$activeStudents = (int)($activeStudents ?? 0);
$todayRegistrations = (int)($todayRegistrations ?? 0);
$todayCompletions = (int)($todayCompletions ?? 0);
$overallCompletionRate = (float)($overallCompletionRate ?? 0);
$recentActivity = $recentActivity ?? [];
$recentNotifications = $recentNotifications ?? [];
$completionStats = $completionStats ?? [];
$unreadNotificationCount = (int)($unreadNotificationCount ?? 0);

ob_start();
if (file_exists(__DIR__ . '/partials/summary_stat_card.php')) {
    include __DIR__ . '/partials/summary_stat_card.php';
}
?>
<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }

    .page-in { animation: fadeIn 0.5s ease-out both; }
    .card-in { animation: slideUp 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    .d1 { animation-delay: 0.05s; }
    .d2 { animation-delay: 0.1s; }
    .d3 { animation-delay: 0.15s; }
    .d4 { animation-delay: 0.2s; }
    .d5 { animation-delay: 0.25s; }
    .d6 { animation-delay: 0.3s; }
    .d7 { animation-delay: 0.35s; }
    .d8 { animation-delay: 0.4s; }
    .d9 { animation-delay: 0.45s; }

    @keyframes slideUpCard { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes iconBounce { 0% { transform: scale(1); } 25% { transform: scale(1.25) rotate(-5deg); } 50% { transform: scale(0.9) rotate(3deg); } 75% { transform: scale(1.1) rotate(-2deg); } 100% { transform: scale(1) rotate(0deg); } }

    .page-in { animation: fadeIn 0.5s ease-out both; }
    .card-in { animation: slideUpCard 0.5s cubic-bezier(0.22,1,0.36,1) both; }

    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
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

    .activity-item {
        border-radius: 12px;
        padding: 14px 16px;
        transition: background 0.15s ease, transform 0.15s ease;
    }
    .activity-item:hover {
        background: #f8fafc;
        transform: translateX(4px);
    }

    .action-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        cursor: pointer;
    }
    .action-btn:hover {
        transform: translateY(-2px) scale(1.01);
        box-shadow: 0 12px 28px -12px rgba(91,95,239,0.30);
        border-color: #5B5FEF;
    }
    .action-btn:active { transform: scale(0.98); }

    .dashboard-two-col {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }
    .dashboard-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
    }
</style>

<div class="page-in" style="max-width: 1280px; margin: 0 auto; padding: 32px 24px;">
    <div style="margin-bottom: 36px;">
        <h1 style="font-size: 32px; font-weight: 700; color: #1e293b; margin: 0;">Welcome back, <?= htmlspecialchars($adminName) ?>!👋</h1>

        <p style="font-size: 14px; color: #94a3b8; margin: 4px 0 0 0;"><?= date('l, F j, Y') ?></p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 20px; margin-bottom: 32px;" class="sm-g-2 md-g-3 lg-g-5">
        <?php renderAdminSummaryCard(['title' => 'Total Students', 'value' => '0', 'valueNumber' => (int)$totalStudents, 'counterId' => 'countStudents', 'icon' => 'bi-people-fill', 'iconBg' => '#eef2ff', 'iconColor' => '#5B5FEF', 'delayClass' => 'd1', 'filter' => 'students', 'extraClass' => 'summary-stat-card']); ?>
        <?php renderAdminSummaryCard(['title' => 'Total Questions', 'value' => '0', 'valueNumber' => (int)$totalQuestions, 'counterId' => 'countQuestions', 'icon' => 'bi-patch-question-fill', 'iconBg' => '#eff6ff', 'iconColor' => '#2563eb', 'delayClass' => 'd2', 'filter' => 'questions', 'extraClass' => 'summary-stat-card']); ?>
        <?php renderAdminSummaryCard(['title' => 'Total Assessments', 'value' => '0', 'valueNumber' => (int)$totalAssessments, 'counterId' => 'countAssessments', 'icon' => 'bi-journal-text', 'iconBg' => '#ecfdf5', 'iconColor' => '#059669', 'delayClass' => 'd3', 'filter' => 'assessments', 'extraClass' => 'summary-stat-card']); ?>
        <?php renderAdminSummaryCard(['title' => 'Total Careers', 'value' => '0', 'valueNumber' => (int)$totalCareers, 'counterId' => 'countCareers', 'icon' => 'bi-briefcase-fill', 'iconBg' => '#f3e8ff', 'iconColor' => '#9333ea', 'delayClass' => 'd4', 'filter' => 'careers', 'extraClass' => 'summary-stat-card']); ?>
        <?php renderAdminSummaryCard(['title' => 'Recommendations', 'value' => '0', 'valueNumber' => (int)$totalRecommendations, 'counterId' => 'countRecommendations', 'icon' => 'bi-stars', 'iconBg' => '#fffbeb', 'iconColor' => '#d97706', 'delayClass' => 'd5', 'filter' => 'recommendations', 'extraClass' => 'summary-stat-card']); ?>
    </div>

    <div class="dashboard-two-col">
        <section class="dashboard-card">
            <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0;">Recent Activity</h2>
            <p style="font-size: 14px; color: #94a3b8; margin: 0 0 20px 0;">Latest activities across the system</p>

            <?php if (empty($recentActivity)): ?>
            <div style="padding: 32px 16px; text-align: center; border: 1px dashed #e2e8f0; border-radius: 12px;">
                <p style="font-size: 14px; color: #94a3b8; margin: 0;">No activity recorded yet.</p>
            </div>
            <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <?php
                $activityIcons = [
                    'assessment_completed'     => ['icon' => 'bi-check-circle-fill', 'color' => '#059669', 'bg' => '#ecfdf5'],
                    'career_added'             => ['icon' => 'bi-briefcase-fill',    'color' => '#7c3aed', 'bg' => '#f3e8ff'],
                    'question_added'           => ['icon' => 'bi-patch-question-fill','color' => '#2563eb', 'bg' => '#eff6ff'],
                    'recommendation_generated' => ['icon' => 'bi-stars',             'color' => '#d97706', 'bg' => '#fffbeb'],
                    'assessment_added'         => ['icon' => 'bi-journal-plus',      'color' => '#0891b2', 'bg' => '#ecfeff'],
                ];
                $idx = 0;
                foreach ($recentActivity as $act):
                    $type = $act['type'] ?? '';
                    $info = $activityIcons[$type] ?? ['icon' => 'bi-circle', 'color' => '#64748b', 'bg' => '#f1f5f9'];
                    $subject = htmlspecialchars((string)($act['subject'] ?? ''));
                    $desc = htmlspecialchars((string)($act['description'] ?? ''));
                    $occurred = $act['occurred_at'] ?? '';
                    $time = $occurred ? date('M d, Y g:i A', strtotime($occurred)) : '';
                    $idx++;
                ?>
                <div class="activity-item card-in" style="display: flex; align-items: center; gap: 14px; animation-delay: <?= (0.25 + $idx * 0.05) ?>s;">
                    <div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; background: <?= $info['bg'] ?>; color: <?= $info['color'] ?>; flex-shrink: 0;">
                        <i class="bi <?= $info['icon'] ?>" style="font-size: 18px;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="font-size: 15px; font-weight: 500; color: #1e293b; margin: 0; line-height: 1.4;">
                            <?php if ($subject): ?><span style="font-weight: 600;"><?= $subject ?></span> <?php endif; ?><?= $desc ?>
                        </p>
                    </div>
                    <div style="flex-shrink: 0;">
                        <p style="font-size: 12px; color: #94a3b8; margin: 0; white-space: nowrap;"><?= $time ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>

        <section class="dashboard-card">
            <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0;">Quick Actions</h2>
            <p style="font-size: 14px; color: #94a3b8; margin: 0 0 20px 0;">Jump to the main admin modules</p>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a href="<?= BASE_URL ?>/index.php?page=admin-careers-create" class="action-btn card-in d5">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #f3e8ff; color: #7c3aed; flex-shrink: 0;">
                            <i class="bi bi-plus-lg" style="font-size: 20px; font-weight: 700;"></i>
                        </div>
                        <div>
                            <p style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0;">Add Career</p>
                            <p style="font-size: 13px; color: #94a3b8; margin: 2px 0 0 0;">Create a new career path</p>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right" style="font-size: 18px; color: #cbd5e1;"></i>
                </a>

                <a href="<?= BASE_URL ?>/index.php?page=admin-questions-create" class="action-btn card-in d6">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #eff6ff; color: #2563eb; flex-shrink: 0;">
                            <i class="bi bi-plus-lg" style="font-size: 20px; font-weight: 700;"></i>
                        </div>
                        <div>
                            <p style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0;">Add Question</p>
                            <p style="font-size: 13px; color: #94a3b8; margin: 2px 0 0 0;">Manage assessment questions</p>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right" style="font-size: 18px; color: #cbd5e1;"></i>
                </a>

                <a href="<?= BASE_URL ?>/index.php?page=admin-users" class="action-btn card-in d7">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #eef2ff; color: #5B5FEF; flex-shrink: 0;">
                            <i class="bi bi-people-fill" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <p style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0;">View Students</p>
                            <p style="font-size: 13px; color: #94a3b8; margin: 2px 0 0 0;">Review registered learners</p>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right" style="font-size: 18px; color: #cbd5e1;"></i>
                </a>

                <a href="<?= BASE_URL ?>/index.php?page=admin-reports" class="action-btn card-in d8">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #fffbeb; color: #d97706; flex-shrink: 0;">
                            <i class="bi bi-file-earmark-text-fill" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <p style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0;">View Reports</p>
                            <p style="font-size: 13px; color: #94a3b8; margin: 2px 0 0 0;">Monitor recommendations and activity</p>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right" style="font-size: 18px; color: #cbd5e1;"></i>
                </a>
            </div>
        </section>
    </div>
</div>
<style>
    @media (min-width: 640px) {
        .sm-g-2 { grid-template-columns: repeat(2, 1fr) !important; }
    }
    @media (min-width: 768px) {
        .md-g-3 { grid-template-columns: repeat(3, 1fr) !important; }
    }
    @media (min-width: 992px) {
        .dashboard-two-col { grid-template-columns: 65% 35%; }
        .lg-g-5 { grid-template-columns: repeat(5, 1fr) !important; }
    }
</style>

<script>
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
include __DIR__ . '/layout.php';
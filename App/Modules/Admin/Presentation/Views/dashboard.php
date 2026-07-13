<?php
$admin = $admin ?? [];
$adminName = trim((string)($admin['full_name'] ?? $admin['username'] ?? 'Admin'));
$totalStudents = (int)($totalStudents ?? 0);
$totalAssessments = (int)($totalAssessments ?? 0);
$totalQuestions = (int)($totalQuestions ?? 0);
$totalCareers = (int)($totalCareers ?? 0);
$recentActivity = $recentActivity ?? [];
$recentStudents = $recentStudents ?? [];

ob_start();

$timeAgo = function (string $timestamp): string {
    if ($timestamp === '') return '';
    $t = strtotime($timestamp);
    if ($t === false) return '';
    $diff = time() - $t;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 7200) return '1h ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 172800) return 'Yesterday';
    return floor($diff / 86400) . 'd ago';
};

$formatDate = function (string $ts): string {
    if ($ts === '') return '';
    $t = strtotime($ts);
    return $t ? date('M j, Y', $t) : '';
};

$initials = function (string $name): string {
    $name = trim($name);
    if ($name === '') return '?';
    $parts = preg_split('/\s+/', $name);
    if (count($parts) >= 2) {
        return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1));
    }
    return mb_strtoupper(mb_substr($name, 0, 2));
};
?>
<style>
    /* ----- enhanced animations ----- */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .anim-up { animation: fadeUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both; }
    .anim-in { animation: fadeIn 0.5s ease-out both; }
    .anim-slide-left { animation: slideInLeft 0.5s cubic-bezier(0.22, 1, 0.36, 1) both; }
    .anim-slide-right { animation: slideInRight 0.5s cubic-bezier(0.22, 1, 0.36, 1) both; }

    .d1 { animation-delay: 0.05s; }
    .d2 { animation-delay: 0.10s; }
    .d3 { animation-delay: 0.15s; }
    .d4 { animation-delay: 0.20s; }
    .d5 { animation-delay: 0.25s; }
    .d6 { animation-delay: 0.30s; }

    .card-hover {
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .card-hover:hover {
        transform: translateY(-6px) scale(1.01);
        box-shadow: 0 20px 40px -12px rgba(91,95,239,0.25);
    }

    .stat-number {
        font-variant-numeric: tabular-nums;
        transition: all 0.2s;
    }
    .stat-card {
        position: relative;
        overflow: hidden;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #5B5FEF, #8B5CF6);
        opacity: 0.7;
    }
    .stat-card:nth-child(2)::after {
        background: linear-gradient(90deg, #10B981, #34D399);
    }
    .stat-card:nth-child(3)::after {
        background: linear-gradient(90deg, #8B5CF6, #A78BFA);
    }
    .stat-card:nth-child(4)::after {
        background: linear-gradient(90deg, #F59E0B, #FBBF24);
    }

    .quick-btn {
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .quick-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #5B5FEF, #8B5CF6);
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: inherit;
    }
    .quick-btn:hover::before {
        opacity: 0.08;
    }
    .quick-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -6px rgba(91,95,239,0.3);
        border-color: #5B5FEF;
    }

    .activity-item {
        transition: all 0.2s;
    }
    .activity-item:hover {
        background: rgba(91,95,239,0.04);
        transform: translateX(4px);
    }

    .student-item {
        transition: all 0.2s;
    }
    .student-item:hover {
        background: rgba(91,95,239,0.04);
        transform: translateX(-4px);
    }

    /* smooth scroll & selection */
    * { scroll-behavior: smooth; }
    ::selection { background: #5B5FEF; color: #fff; }
</style>

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 space-y-8">

    <!-- Header – plain, no background, no button -->
    <div class="anim-up d1">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 m-0 tracking-tight">
            👋 Welcome back, <?= htmlspecialchars($adminName) ?>
        </h1>
        <p class="mt-1 text-sm text-slate-500 font-medium"><?= date('l, F j, Y') ?></p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-slate-100 p-6 card-hover anim-up d1">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-[#5B5FEF] text-xl"><i class="bi bi-people"></i></div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Students</span>
            </div>
            <div class="stat-number text-3xl font-extrabold text-slate-900"><?= number_format($totalStudents) ?></div>
            <div class="mt-1 text-xs text-slate-400 flex items-center gap-1">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                Active
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-slate-100 p-6 card-hover anim-up d2">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 text-xl"><i class="bi bi-file-earmark-check"></i></div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Assessments</span>
            </div>
            <div class="stat-number text-3xl font-extrabold text-slate-900"><?= number_format($totalAssessments) ?></div>
            <div class="mt-1 text-xs text-slate-400">Total created</div>
        </div>
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-slate-100 p-6 card-hover anim-up d3">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-violet-50 flex items-center justify-center text-violet-500 text-xl"><i class="bi bi-briefcase"></i></div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Careers</span>
            </div>
            <div class="stat-number text-3xl font-extrabold text-slate-900"><?= number_format($totalCareers) ?></div>
            <div class="mt-1 text-xs text-slate-400">Available paths</div>
        </div>
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-slate-100 p-6 card-hover anim-up d4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 text-xl"><i class="bi bi-question-circle"></i></div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Questions</span>
            </div>
            <div class="stat-number text-3xl font-extrabold text-slate-900"><?= number_format($totalQuestions) ?></div>
            <div class="mt-1 text-xs text-slate-400">In the bank</div>
        </div>
    </div>

    <!-- Main row: Latest Activity + Recent Students -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Latest Activity -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden anim-slide-left d5">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-base m-0 flex items-center gap-2">
                    <i class="bi bi-clock-history text-indigo-500"></i>
                    Latest Activity
                </h3>
                <span class="text-xs text-slate-400 bg-white px-2.5 py-1 rounded-full border border-slate-200">Live</span>
            </div>
            <div class="divide-y divide-slate-50 max-h-[360px] overflow-y-auto">
                <?php if (empty($recentActivity)): ?>
                <div class="px-6 py-12 text-center text-sm text-slate-400">No recent activity.</div>
                <?php else: ?>
                <?php foreach ($recentActivity as $a):
                    $type = $a['type'] ?? '';
                    $subject = htmlspecialchars((string)($a['subject'] ?? ''));
                    $detail = htmlspecialchars((string)($a['detail'] ?? ''));
                    $occurred = $a['occurred_at'] ?? '';
                    $userId = (int)($a['user_id'] ?? 0);

                    if ($type === 'user_registered') {
                        $icon = 'bi-person-plus';
                        $bg = 'bg-blue-50';
                        $color = 'text-blue-600';
                        $desc = 'Registered a new account';
                    } else {
                        $icon = 'bi-check-circle';
                        $bg = 'bg-emerald-50';
                        $color = 'text-emerald-500';
                        $desc = 'Completed ' . $detail;
                    }
                ?>
                <div class="activity-item flex items-center gap-3.5 px-6 py-3.5 transition-colors">
                    <span class="flex items-center justify-center w-10 h-10 rounded-full text-xs font-bold bg-[#5B5FEF]/10 text-[#5B5FEF] shrink-0"><?= $initials($subject) ?></span>
                    <div class="flex-1 min-w-0 flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-semibold text-slate-800 truncate"><?= $subject ?></span>
                        <span class="text-xs text-slate-500 truncate">&middot; <?= $desc ?></span>
                    </div>
                    <span class="shrink-0 text-xs font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full"><?= $timeAgo($occurred) ?></span>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Students -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden anim-slide-right d6">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-base m-0 flex items-center gap-2">
                    <i class="bi bi-person-plus text-emerald-500"></i>
                    Recent Students
                </h3>
                <span class="text-xs text-slate-400 bg-white px-2.5 py-1 rounded-full border border-slate-200"><?= count($recentStudents) ?> new</span>
            </div>
            <div class="divide-y divide-slate-50 max-h-[360px] overflow-y-auto">
                <?php if (empty($recentStudents)): ?>
                <div class="px-6 py-12 text-center text-sm text-slate-400">No students registered yet.</div>
                <?php else: ?>
                <?php foreach ($recentStudents as $s):
                    $name = htmlspecialchars((string)($s['username'] ?? ''));
                    $edu = htmlspecialchars((string)($s['education_level'] ?? ''));
                    $regDate = $s['registered_at'] ?? '';
                    $profileImage = $s['profile_image'] ?? '';
                ?>
                <div class="student-item flex items-center gap-3.5 px-6 py-3.5 transition-colors">
                    <?php if ($profileImage !== '' && file_exists(BASE_PATH . '/public/uploads/profile/' . $profileImage)): ?>
                        <img src="<?= BASE_URL ?>/uploads/profile/<?= rawurlencode($profileImage) ?>" alt="" class="w-10 h-10 rounded-full object-cover shrink-0 ring-2 ring-indigo-50">
                    <?php else: ?>
                        <span class="flex items-center justify-center w-10 h-10 rounded-full text-sm font-bold bg-slate-100 text-slate-600 shrink-0"><?= $initials($name) ?></span>
                    <?php endif; ?>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate m-0"><?= $name ?: 'Student' ?></p>
                        <p class="text-xs text-slate-500 m-0 mt-0.5 flex items-center gap-1">
                            <i class="bi bi-mortarboard text-[10px]"></i>
                            <?= $edu ?: 'N/A' ?>
                        </p>
                    </div>
                    <span class="shrink-0 text-xs text-slate-400 bg-slate-50 px-2 py-0.5 rounded-full"><?= $formatDate($regDate) ?></span>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 anim-in">
        <h3 class="font-bold text-slate-800 text-base m-0 mb-4 flex items-center gap-2">
            <i class="bi bi-lightning-charge-fill text-amber-400"></i>
            Quick Actions
        </h3>
        <div class="flex flex-wrap gap-3">
            <a href="<?= BASE_URL ?>/index.php?page=admin-careers" class="quick-btn inline-flex items-center gap-2.5 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 no-underline transition-all duration-200 hover:border-[#5B5FEF] hover:text-[#5B5FEF]">
                <i class="bi bi-briefcase text-base"></i>
                Manage Careers
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin-questions" class="quick-btn inline-flex items-center gap-2.5 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 no-underline transition-all duration-200 hover:border-[#5B5FEF] hover:text-[#5B5FEF]">
                <i class="bi bi-question-circle text-base"></i>
                Manage Questions
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin-users" class="quick-btn inline-flex items-center gap-2.5 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 no-underline transition-all duration-200 hover:border-[#5B5FEF] hover:text-[#5B5FEF]">
                <i class="bi bi-people text-base"></i>
                View Users
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin-reports" class="quick-btn inline-flex items-center gap-2.5 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 no-underline transition-all duration-200 hover:border-[#5B5FEF] hover:text-[#5B5FEF]">
                <i class="bi bi-bar-chart text-base"></i>
                View Reports
            </a>
        </div>
    </div>

</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
<?php
$admin = $admin ?? [];
$adminName = trim((string)($admin['full_name'] ?? $admin['username'] ?? 'Admin'));
$totalUsers = (int)($totalUsers ?? 0);
$totalAssessments = (int)($totalAssessments ?? 0);
$totalQuestions = (int)($totalQuestions ?? 0);
$totalCareers = (int)($totalCareers ?? 0);
$recentActivity = $recentActivity ?? [];
$systemStatus = $systemStatus ?? [];

ob_start();

$formatTimeAgo = function (string $timestamp): string {
    if ($timestamp === '') {
        return 'Just now';
    }
    $time = strtotime($timestamp);
    if ($time === false) {
        return 'Just now';
    }
    $diff = time() - $time;
    if ($diff < 60) {
        return 'Just now';
    }
    if ($diff < 3600) {
        return floor($diff / 60) . 'm ago';
    }
    if ($diff < 86400) {
        return floor($diff / 3600) . 'h ago';
    }
    return floor($diff / 86400) . 'd ago';
};
?>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.55s cubic-bezier(0.21, 0.98, 0.35, 1) both;
    }
    .animate-fade-in {
        animation: fadeIn 0.7s ease-out both;
    }
    .stagger-1 { animation-delay: 0.05s; }
    .stagger-2 { animation-delay: 0.12s; }
    .stagger-3 { animation-delay: 0.19s; }
    .stagger-4 { animation-delay: 0.26s; }
    .stagger-5 { animation-delay: 0.33s; }
</style>

<div class="max-w-[1400px] mx-auto space-y-8">

    <div class="animate-fade-in-up stagger-1">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Dashboard</p>
        <h1 class="mt-2 text-3xl font-extrabold text-slate-900">Welcome back, <?= htmlspecialchars($adminName) ?></h1>
        <p class="mt-1 text-sm text-slate-500"><?= date('l, F j, Y') ?></p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out animate-fade-in-up stagger-2">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-lg mb-4">
                <i class="bi bi-people"></i>
            </div>
            <p class="text-xs font-semibold text-gray-500 tracking-wide uppercase m-0">Total Users</p>
            <div class="text-3xl font-bold tracking-tight text-blue-600 mt-1"><?= number_format($totalUsers) ?></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out animate-fade-in-up stagger-3">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 text-lg mb-4">
                <i class="bi bi-file-earmark-check"></i>
            </div>
            <p class="text-xs font-semibold text-gray-500 tracking-wide uppercase m-0">Total Assessments</p>
            <div class="text-3xl font-bold tracking-tight text-emerald-500 mt-1"><?= number_format($totalAssessments) ?></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out animate-fade-in-up stagger-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 text-lg mb-4">
                <i class="bi bi-question-circle"></i>
            </div>
            <p class="text-xs font-semibold text-gray-500 tracking-wide uppercase m-0">Total Questions</p>
            <div class="text-3xl font-bold tracking-tight text-amber-500 mt-1"><?= number_format($totalQuestions) ?></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out animate-fade-in-up stagger-5">
            <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center text-violet-500 text-lg mb-4">
                <i class="bi bi-briefcase"></i>
            </div>
            <p class="text-xs font-semibold text-gray-500 tracking-wide uppercase m-0">Total Careers</p>
            <div class="text-3xl font-bold tracking-tight text-violet-500 mt-1"><?= number_format($totalCareers) ?></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card rounded-2xl shadow-[0_4px_20px_-2px_rgba(15,23,42,0.03)] overflow-hidden animate-fade-in stagger-4">
            <div class="p-5 border-b border-slate-100 bg-white">
                <h3 class="font-bold text-slate-800 text-base m-0">Recent Activity</h3>
            </div>
            <div class="divide-y divide-slate-100">
                <?php if (empty($recentActivity)): ?>
                    <div class="px-5 py-8 text-center text-sm text-slate-400">No recent activity recorded.</div>
                <?php else: ?>
                    <?php foreach ($recentActivity as $activity): ?>
                        <?php
                        $type = $activity['type'] ?? '';
                        $subject = htmlspecialchars((string)($activity['subject'] ?? ''));
                        $detail = htmlspecialchars((string)($activity['detail'] ?? ''));
                        $occurredAt = $activity['occurred_at'] ?? '';
                        $timeAgo = $occurredAt ? $formatTimeAgo($occurredAt) : '';

                        $icon = 'bi-circle';
                        $iconBg = 'bg-slate-100';
                        $iconColor = 'text-slate-500';
                        $label = '';

                        if ($type === 'user_registered') {
                            $icon = 'bi-person-plus';
                            $iconBg = 'bg-blue-50';
                            $iconColor = 'text-blue-600';
                            $label = 'New user registered';
                        } elseif ($type === 'assessment_completed') {
                            $icon = 'bi-check-circle';
                            $iconBg = 'bg-emerald-50';
                            $iconColor = 'text-emerald-500';
                            $label = 'Assessment completed';
                        } elseif ($type === 'question_added') {
                            $icon = 'bi-patch-plus';
                            $iconBg = 'bg-amber-50';
                            $iconColor = 'text-amber-500';
                            $label = 'Question added';
                        }
                        ?>
                        <div class="flex items-start gap-3 px-5 py-3.5 transition-all duration-150 hover:bg-slate-50/50">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full <?= $iconBg ?>">
                                <i class="bi <?= $icon ?> text-sm <?= $iconColor ?>"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-800 m-0 truncate">
                                    <?php if ($type === 'user_registered'): ?>
                                        <?= $subject ?>
                                    <?php elseif ($type === 'assessment_completed'): ?>
                                        <?= $subject ?>
                                    <?php elseif ($type === 'question_added'): ?>
                                        New question added
                                    <?php endif; ?>
                                </p>
                                <p class="text-xs text-slate-400 m-0 mt-0.5">
                                    <?php if ($type === 'user_registered'): ?>
                                        New user registered
                                    <?php elseif ($type === 'assessment_completed'): ?>
                                        <?= $detail ?>
                                    <?php elseif ($type === 'question_added'): ?>
                                        <?= $detail ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <span class="shrink-0 text-xs text-slate-400"><?= $timeAgo ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="glass-card rounded-2xl shadow-[0_4px_20px_-2px_rgba(15,23,42,0.03)] overflow-hidden animate-fade-in stagger-5">
            <div class="p-5 border-b border-slate-100 bg-white">
                <h3 class="font-bold text-slate-800 text-base m-0">System Status</h3>
            </div>
            <div class="divide-y divide-slate-100">
                <?php
                $statusItems = [
                    [
                        'label' => 'Database Connection',
                        'active' => $systemStatus['database'] ?? false,
                        'icon' => 'bi-database',
                        'iconBg' => 'bg-sky-50',
                        'iconColor' => 'text-sky-600',
                    ],
                    [
                        'label' => 'Assessment Module',
                        'active' => $systemStatus['assessmentModule'] ?? false,
                        'icon' => 'bi-clipboard-check',
                        'iconBg' => 'bg-indigo-50',
                        'iconColor' => 'text-indigo-500',
                    ],
                    [
                        'label' => 'Career Recommendation Module',
                        'active' => $systemStatus['recommendationModule'] ?? false,
                        'icon' => 'bi-briefcase',
                        'iconBg' => 'bg-violet-50',
                        'iconColor' => 'text-violet-500',
                    ],
                ];
                ?>
                <?php foreach ($statusItems as $item): ?>
                    <div class="flex items-center justify-between px-5 py-4 transition-all duration-150 hover:bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full <?= $item['iconBg'] ?>">
                                <i class="bi <?= $item['icon'] ?> text-sm <?= $item['iconColor'] ?>"></i>
                            </div>
                            <span class="text-sm font-semibold text-slate-700"><?= $item['label'] ?></span>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold tracking-wide
                            <?= $item['active'] ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' ?>">
                            <span class="inline-block h-1.5 w-1.5 rounded-full <?= $item['active'] ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
                            <?= $item['active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>

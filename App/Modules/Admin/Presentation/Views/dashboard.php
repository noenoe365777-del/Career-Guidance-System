<?php
$admin = $admin ?? [];
$activeMenu = $activeMenu ?? 'dashboard';
$adminName = trim((string)($admin['full_name'] ?? $admin['username'] ?? 'Admin'));
$signupTrend = $signupTrend ?? ['labels' => [], 'values' => []];
$completionTrend = $completionTrend ?? ['labels' => [], 'values' => []];
$assessmentBreakdown = $assessmentBreakdown ?? [];
$notifications = $notifications ?? [];
$recentUsers = $recentUsers ?? [];
$recentSubmissions = $recentSubmissions ?? [];
?>
<!doctype html>
<html lang="en" class="h-full bg-[#f4f7fc]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Dashboard') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#2563eb',
                            dark: '#0f4c81'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f4f7fc !important;
        }
        .glass-card {
            background: #ffffff;
            border: 1px solid #eef2f6;
        }
    </style>
</head>
<body class="h-full text-slate-700 antialiased font-sans m-0 p-0">

    <div class="flex h-screen overflow-hidden">
        
        <div class="hidden md:flex md:shrink-0 h-full">
            <?php include __DIR__ . '/sidebar.php'; ?>
        </div>

        <div class="flex flex-col flex-1 min-w-0 h-full overflow-hidden">
            
            <?php include __DIR__ . '/header.php'; ?>

            <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-[#f4f7fc]">
                <div class="max-w-[1400px] mx-auto space-y-8">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        <div class="glass-card p-6 rounded-2xl shadow-[0_4px_20px_-2px_rgba(15,23,42,0.03)] hover:shadow-md transition-all duration-300" data-aos="fade-up" data-aos-delay="50">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-lg mb-4">
                                <i class="bi bi-people"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase m-0">Total Users</p>
                            <div class="text-3xl font-bold tracking-tight text-slate-800 mt-1 stat-value" data-target="<?= (int)($totalUsers ?? 0) ?>">0</div>
                            <div class="mt-4 pt-3 border-t border-slate-50 text-[11px] text-slate-400">All registered users</div>
                        </div>

                        <div class="glass-card p-6 rounded-2xl shadow-[0_4px_20px_-2px_rgba(15,23,42,0.03)] hover:shadow-md transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 text-lg mb-4">
                                <i class="bi bi-file-earmark-check"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase m-0">Assessments</p>
                            <div class="text-3xl font-bold tracking-tight text-slate-800 mt-1 stat-value" data-target="<?= (int)($completedAssessments ?? 856) ?>">0</div>
                            <div class="mt-4 pt-3 border-t border-slate-50 text-[11px] text-slate-400">Total assessments taken</div>
                        </div>

                        <div class="glass-card p-6 rounded-2xl shadow-[0_4px_20px_-2px_rgba(15,23,42,0.03)] hover:shadow-md transition-all duration-300" data-aos="fade-up" data-aos-delay="150">
                            <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center text-purple-500 text-lg mb-4">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase m-0">Careers</p>
                            <div class="text-3xl font-bold tracking-tight text-slate-800 mt-1 stat-value" data-target="<?= (int)($totalCareers ?? 324) ?>">0</div>
                            <div class="mt-4 pt-3 border-t border-slate-50 text-[11px] text-slate-400">Career options available</div>
                        </div>

                        <div class="glass-card p-6 rounded-2xl shadow-[0_4px_20px_-2px_rgba(15,23,42,0.03)] hover:shadow-md transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 text-lg mb-4">
                                <i class="bi bi-question-circle"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase m-0">Questions</p>
                            <div class="text-3xl font-bold tracking-tight text-slate-800 mt-1 stat-value" data-target="<?= (int)($totalQuestions ?? 1152) ?>">0</div>
                            <div class="mt-4 pt-3 border-t border-slate-50 text-[11px] text-slate-400">Total questions</div>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        
                        <div class="glass-card rounded-2xl shadow-[0_4px_20px_-2px_rgba(15,23,42,0.03)] overflow-hidden flex flex-col" data-aos="fade-up">
                            <div class="p-5 flex items-center justify-between border-b border-slate-100 bg-white">
                                <h3 class="font-bold text-slate-800 text-base m-0">Recent Users</h3>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-users" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors duration-150 no-underline">View All</a>
                            </div>
                            
                            <div class="flex-1 overflow-x-auto">
                                <table class="w-full text-left border-collapse m-0">
                                    <thead>
                                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                            <th class="py-3.5 px-6">Name</th>
                                            <th class="py-3.5 px-6">Email</th>
                                            <th class="py-3.5 px-6">Registered On</th>
                                            <th class="py-3.5 px-6 text-right">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-xs text-slate-600 bg-white">
                                        <?php if (empty($recentUsers)): ?>
                                            <tr>
                                                <td colspan="4" class="py-4 px-6 text-center text-sm text-slate-400">No users found.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($recentUsers as $user): ?>
                                                <?php $isUserActive = strtolower($user['status'] ?? 'active') === 'active'; ?>
                                                <tr class="hover:bg-slate-50/30 transition duration-150">
                                                    <td class="py-3.5 px-6 font-medium text-slate-800"><?= htmlspecialchars((string)($user['full_name'] ?? 'Unknown')) ?></td>
                                                    <td class="py-3.5 px-6 text-slate-500"><?= htmlspecialchars((string)($user['email'] ?? '')) ?></td>
                                                    <td class="py-3.5 px-6 text-slate-400"><?= htmlspecialchars((string)($user['created_at'] ?? '')) ?></td>
                                                    <td class="py-3.5 px-6 text-right">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-medium tracking-wide
                                                            <?= $isUserActive ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' ?>">
                                                            <?= htmlspecialchars((string)$user['status']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    
    <script>
        // Init viewport trigger sequence module
        AOS.init({ duration: 600, easing: 'ease-out-cubic', once: true });

        // Micro counter numerical animation script block logic
        document.querySelectorAll('.stat-value[data-target]').forEach((el) => {
            const finalValue = Number(el.getAttribute('data-target') || 0);
            let current = 0;
            const duration = 1000;
            const start = performance.now();
            const step = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                current = Math.floor(progress * finalValue);
                el.textContent = current.toLocaleString();
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        });
    </script>
</body>
</html>
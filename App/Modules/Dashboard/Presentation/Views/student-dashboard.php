<?php
// =======================================================================
// Dashboard Controller Logic Mock Data (Inject variables from router controller)
// =======================================================================
$totalAssessments = $totalAssessments ?? 4;
$completedAssessments = $completedAssessments ?? 1; 
$percentage = $percentage ?? ($totalAssessments > 0 ? ($completedAssessments / $totalAssessments) * 100 : 25);
$studentId = $user['id'] ?? $_SESSION['user']['id'] ?? 'STU-9482';


?>

<!-- Framework & Premium Float Micro-interaction Style Hooks -->
<style>
@keyframes float {
    0%, 100% { transform: translateY(0px) scale(1); }
    50% { transform: translateY(-10px) scale(1.02); }
}
.animate-float-premium {
    animation: float 6s ease-in-out infinite;
}
@keyframes pulseGlow {
    0%, 100% { opacity: 0.15; transform: scale(1); }
    50% { opacity: 0.3; transform: scale(1.08); }
}
.animate-glow {
    animation: pulseGlow 4s ease-in-out infinite;
}
</style>

<div class="flex bg-slate-50/60 min-h-screen text-slate-800 antialiased font-sans overflow-x-hidden">

    <!-- 1. Sidebar Navigation Module -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content Presentation Wrapper Shell View Layout -->
    <div class="flex-1 lg:ml-72 flex flex-col min-w-0 transition-all duration-300">

        <!-- 2. Dynamic Topbar Platform Utility Module -->
        <?php include 'topbar.php'; ?>

        <!-- Content Area View Container Window -->
        <main class="px-4 sm:px-8 lg:px-10 py-8 space-y-8 flex-1 max-w-[1600px] w-full mx-auto">

            <!-- ===================================== -->
            <!-- Section Component: Welcome Header Banner -->
            <!-- ===================================== -->
            <section class="relative bg-white rounded-3xl border border-slate-200/70 shadow-sm overflow-hidden group hover:shadow-md transition-all duration-300">
                <!-- Premium Ambient Background Blur Shape -->
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-gradient-to-br from-indigo-500/10 to-blue-500/10 rounded-full blur-3xl -z-10 group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center p-6 sm:p-10">
                    <!-- Text Segment Copy Block -->
                    <div class="lg:col-span-7 space-y-4 text-center lg:text-left">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                Core Overview
                            </span>
                        </div>
                        
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">
                            Welcome back,<?= htmlspecialchars($user['username'] ?? 'Student') ?>! <span class="inline-block origin-bottom-right hover:animate-[wave_1s_ease-in-out_2]">👋</span>
                        </h1>
                        
                        <p class="text-slate-500 text-sm max-w-md mx-auto lg:mx-0 font-medium leading-relaxed">
                            Complete your assessments to unlock personalized career recommendations.
                        </p>

                        <div class="pt-2">
                            <a href="<?= BASE_URL ?>/index.php?page=assessments" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-semibold shadow-md shadow-indigo-600/10 hover:shadow-lg hover:shadow-indigo-600/20 active:scale-95 transition-all duration-200 text-sm">
                                Continue Assessment
                                <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Right Abstract Vector Visual Graphics Display -->
                    <div class="hidden lg:col-span-5 lg:flex justify-end pr-4 relative">
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-400/10 to-transparent rounded-full blur-2xl animate-glow"></div>
                        <div class="relative max-w-sm w-full animate-float-premium">
                            <img src="<?= BASE_URL ?>/assets/images/welcome.png" class="w-full h-auto object-contain drop-shadow-xl" alt="Dashboard Illustration Graphic Mockup">
                        </div>
                    </div>
                </div>
            </section>

            <!-- ===================================== -->
            <!-- Functional Grid Data Panels Area -->
            <!-- ===================================== -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

                <!-- Left Column Metric Segment Containers -->
                <div class="space-y-8">

                    <!-- Metric Frame: Radial Donut Circle Progress Card Layout Component -->
                    <div class="bg-white rounded-3xl border border-slate-200/70 shadow-sm hover:shadow-md transition-all duration-300 p-6 sm:p-8 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Assessment Progress</h2>
                                <p class="text-slate-400 text-xs font-medium mt-0.5">Overall Completion Status</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                                <i class="fas fa-chart-line text-sm"></i>
                            </div>
                        </div>

                        <!-- Progress Circle Graphic Calculation Container Block Area -->
                        <div class="flex items-center justify-center py-8">
                            <div class="relative w-36 h-36 transform transition-transform duration-500 hover:scale-105">
                                <?php
                                $dash = 408;
                                $offset = max(0, (int)round($dash - ($dash * ($percentage / 100))));
                                ?>
                                <svg class="w-36 h-36 -rotate-90 drop-shadow-sm">
                                    <circle cx="72" cy="72" r="58" stroke="#F1F5F9" stroke-width="10" fill="none" />
                                    <circle cx="72" cy="72" r="58" stroke="#2563EB" stroke-width="10" fill="none" stroke-linecap="round" stroke-dasharray="<?= $dash ?>" stroke-dashoffset="<?= $offset ?>" class="transition-all duration-1000 ease-out" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                    <span class="text-2xl font-black text-slate-900 tracking-tight"><?= round($percentage) ?>%</span>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5"><?= (int)$completedAssessments ?> of <?= (int)$totalAssessments ?> Done</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                            <p class="text-xs font-medium text-slate-500 leading-relaxed">
                                Keep going! You're one step closer to unlocking personalized career maps.
                            </p>
                        </div>
                    </div>

                    <!-- Metric Frame: Lock Gate Authorization Recommendation Container Block -->
                    <div class="bg-white rounded-3xl border border-slate-200/70 shadow-sm hover:shadow-md transition-all duration-300 p-6 sm:p-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Career Recommendation</h2>
                                <p class="text-slate-400 text-xs font-medium mt-0.5">Personalized Pathway Assessment</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-briefcase text-sm"></i>
                            </div>
                        </div>

                        <div class="flex flex-col items-center text-center py-6">
                            <?php if((int)$completedAssessments === (int)$totalAssessments){ ?>
                                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-2xl mb-4 border border-emerald-100 shadow-inner animate-bounce">
                                    <i class="fas fa-unlock-alt"></i>
                                </div>
                                <h3 class="text-base font-bold text-emerald-600">Recommendations Unlocked</h3>
                                <p class="mt-2 text-xs font-medium text-slate-500 max-w-xs leading-relaxed">
                                    Congratulations! Your core testing profile matrix analysis has computed a match layout strategy.
                                </p>
                                <a href="<?= BASE_URL ?>/index.php?page=recommendation" class="mt-5 w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">
                                    View Recommendation
                                </a>
                            <?php } else { ?>
                                <div class="w-14 h-14 bg-slate-50 text-slate-400 border border-slate-200/60 rounded-2xl flex items-center justify-center text-xl mb-4 shadow-sm">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Locked</h3>
                                <p class="mt-2 text-xs font-medium text-slate-400 max-w-[210px] leading-relaxed">
                                    Complete all remaining items to open your customized matrix framework.
                                </p>
                                <button disabled class="mt-5 w-full bg-slate-100 text-slate-400 py-2.5 rounded-xl text-xs font-semibold cursor-not-allowed border border-slate-200/40">
                                    Locked
                                </button>
                            <?php } ?>
                        </div>
                    </div>

                </div>

                <!-- Right-Side Matrix List Actions Stack Column -->
                <div class="xl:col-span-2 space-y-8">

                    <!-- Assessment Item Matrix Roadmap Framework View Container -->
                    <div class="bg-white rounded-3xl border border-slate-200/70 shadow-sm hover:shadow-md transition-all duration-300 p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Assessment Status</h2>
                                <p class="text-slate-400 text-xs font-medium mt-0.5">Complete each to build your profile path structure.</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-clipboard-check text-sm"></i>
                            </div>
                        </div>

                        <!-- Process Map List Layout Group Container Panel Rows -->
                        <div class="space-y-3">
                            
                            <!-- Custom Helper to clean row badges dynamic output syntax -->
                            <?php
                            if (!function_exists('renderModernRowButton')) {
                                function renderModernRowButton($status) {
                                    $statusClean = strtolower($status ?? '');
                                    if ($statusClean === 'completed') {
                                        return '<span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-lg"><span class="w-1 h-1 rounded-full bg-emerald-500"></span>Completed</span>';
                                    }
                                    if ($statusClean === 'pending' || $statusClean === 'in progress') {
                                        return '<span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 rounded-lg animate-pulse"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>In Progress</span>';
                                    }
                                    return '<span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-slate-400 bg-slate-50 border border-slate-100 rounded-lg"><i class="fas fa-lock text-[10px]"></i>Locked</span>';
                                }
                            }
                            ?>

                            <!-- Item row block: Personality Assessment -->
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-slate-200/80 hover:bg-slate-50/50 transition-all duration-200 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-violet-50 border border-violet-100 text-violet-600 flex items-center justify-center text-lg transition-transform group-hover:scale-105">
                                        <i class="fas fa-smile-beam"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Personality Assessment</h3>
                                        <p class="text-xs text-slate-400 font-medium mt-0.5">Discover your core personality traits.</p>
                                    </div>
                                </div>
                                <div><?= renderModernRowButton($statusMap['Personality'] ?? 'Completed') ?></div>
                            </div>

                            <!-- Item row block: Interest Assessment -->
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-slate-200/80 hover:bg-slate-50/50 transition-all duration-200 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg transition-transform group-hover:scale-105">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Interest Assessment</h3>
                                        <p class="text-xs text-slate-400 font-medium mt-0.5">Map out matching vocational dynamics.</p>
                                    </div>
                                </div>
                                <div><?= renderModernRowButton($statusMap['Interest'] ?? 'In Progress') ?></div>
                            </div>

                            <!-- Item row block: Aptitude Assessment -->
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-slate-200/80 hover:bg-slate-50/50 transition-all duration-200 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-lg transition-transform group-hover:scale-105">
                                        <i class="fas fa-brain"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Aptitude Assessment</h3>
                                        <p class="text-xs text-slate-400 font-medium mt-0.5">Complete interest tracking rules first.</p>
                                    </div>
                                </div>
                                <div><?= renderModernRowButton($statusMap['Aptitude'] ?? 'Locked') ?></div>
                            </div>

                            <!-- Item row block: Career Values Assessment -->
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-slate-200/80 hover:bg-slate-50/50 transition-all duration-200 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-lg transition-transform group-hover:scale-105">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Career Values Assessment</h3>
                                        <p class="text-xs text-slate-400 font-medium mt-0.5">Evaluate long-term career environment drivers.</p>
                                    </div>
                                </div>
                                <div><?= renderModernRowButton($statusMap['Career Values'] ?? 'Locked') ?></div>
                            </div>

                        </div>
                    </div>

                    <!-- Layout Segment Panel Footer: Strategic Action Hub Banner Hint Link -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-3xl p-6 text-white flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm relative overflow-hidden group">
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/10 rounded-full blur-xl group-hover:scale-120 transition-transform"></div>
                        <div class="flex items-center gap-4 text-center sm:text-left flex-col sm:flex-row">
                            <div class="w-11 h-11 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-lg shadow-inner">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm tracking-tight">Need help with choice routing?</h4>
                                <p class="text-xs text-blue-100 mt-0.5 max-w-sm">Take your time and answer honestly for the best results. Your future starts with self-discovery.</p>
                            </div>
                        </div>
                        <a href="<?= BASE_URL ?>/index.php?page=faq" class="bg-white text-indigo-600 hover:bg-blue-50 px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-sm">
                            Read Guide Matrix
                        </a>
                    </div>

                </div>

            </div>
        </main>
    </div>
</div>
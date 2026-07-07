<?php
/**
 * View: Home Landing Page
 * Location: C:\xampp\htdocs\career-guidance-system\App\Modules\Home\Presentation\Views\home.php
 */
?>

<!-- Modern Premium Animation and Interaction Styles -->
<style>
    /* Entry animation for content columns */
    @keyframes revealPremium {
        0% { 
            opacity: 0; 
            transform: translateY(12px); 
        }
        100% { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    .animate-reveal {
        animation: revealPremium 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Live subtle floating loop for showcase graphics */
    @keyframes floatLive {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-6px); }
        100% { transform: translateY(0px); }
    }
    .animate-float {
        animation: floatLive 4s ease-in-out infinite;
    }
</style>

<!-- ================= HERO SECTION ================= -->
<section class="relative overflow-hidden bg-white min-h-[55vh] flex items-center selection:bg-blue-600 selection:text-white py-6 sm:py-8 border-b border-slate-50">
    
    <!-- Subtle Ambient Background Accents -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-20%] right-[-5%] w-[35rem] h-[35rem] bg-blue-50/40 rounded-full filter blur-[100px] opacity-60"></div>
        <div class="absolute bottom-[5%] left-[-5%] w-[25rem] h-[25rem] bg-indigo-50/30 rounded-full filter blur-[80px] opacity-50"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10 w-full">
        <!-- 12-Column Layout Matrix -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-4 items-center">
            
            <!-- Left Narrative Column -->
            <div class="lg:col-span-7 space-y-4 opacity-0 animate-reveal">
                
                <div class="space-y-2">
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#0f2d59] leading-[1.12]">
                        Discover the Right <br class="hidden md:inline" />Career Path for You
                    </h1>

                    <!-- Paragraph Description -->
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-xl font-normal">
                        Take assessments, explore career options, and get personalized guidance to build a successful future.
                    </p>
                </div>

                <!-- Call To Actions -->
                <div class="flex flex-wrap gap-2.5 pt-0.5">
                    <a href="<?= BASE_URL ?>/index.php?page=assessments"
                       class="inline-flex items-center justify-center px-4 h-10 rounded-md bg-blue-600 text-white font-semibold text-xs shadow-sm hover:bg-blue-700 active:scale-[0.98] transition-all duration-150">
                        Take Assessment
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=careers"
                       class="inline-flex items-center justify-center px-4 h-10 rounded-md bg-white border border-blue-600 text-blue-600 font-semibold text-xs hover:bg-blue-50 active:scale-[0.98] transition-all duration-150">
                        Explore Careers
                    </a>
                </div>

                <!-- Inline Mini-Feature Badging Row -->
                <div class="pt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 border-t border-slate-100">
                    
                    <!-- Feature 1: Discover Your Strengths -->
                    <div class="flex items-start gap-2 group">
                        <div class="w-8 h-8 flex-shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-105 transition-transform duration-200">
                            <i class="fas fa-brain text-[11px]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-[#0f2d59] leading-snug">Discover Your Strengths</h4>
                            <p class="text-[10.5px] text-slate-400 leading-tight">Scientifically designed assessments</p>
                        </div>
                    </div>

                    <!-- Feature 2: Personalized Analysis -->
                    <div class="flex items-start gap-2 group">
                        <div class="w-8 h-8 flex-shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-105 transition-transform duration-200">
                            <i class="fas fa-chart-pie text-[11px]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-[#0f2d59] leading-snug">Personalized Analysis</h4>
                            <p class="text-[10.5px] text-slate-400 leading-tight">Detailed insights about your skills</p>
                        </div>
                    </div>

                    <!-- Feature 3: Career Recommendations -->
                    <div class="flex items-start gap-2 group">
                        <div class="w-8 h-8 flex-shrink-0 rounded-full bg-pink-50 flex items-center justify-center text-pink-500 group-hover:scale-105 transition-transform duration-200">
                            <i class="fas fa-route text-[11px]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-[#0f2d59] leading-snug">Career Recommendations</h4>
                            <p class="text-[10.5px] text-slate-400 leading-tight">Find best paths that match profile</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Showcase Graphic Column -->
            <div class="lg:col-span-5 relative w-full flex justify-center lg:justify-end opacity-0 animate-reveal [animation-delay:150ms] z-10">
                <!-- Combines live float loop, rich transitions, and high fidelity hover shadow changes -->
                <div class="animate-float relative w-full max-w-xs sm:max-w-sm lg:max-w-full overflow-hidden rounded-xl shadow-md border border-slate-100/80 bg-white p-1 transition-all duration-300 hover:shadow-2xl hover:border-blue-100 group">
                    <img
                        src="<?= BASE_URL ?>/assets/images/home.png"
                        class="w-full h-auto object-cover rounded-lg transform transition-transform duration-500 ease-out group-hover:scale-[1.02]"
                        alt="Career Guidance System Interface">
                    
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-500/5 via-transparent to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none rounded-lg"></div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= HOW IT WORKS SECTION ================= -->
<section class="py-6 sm:py-8 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Centered Section Header -->
        <div class="text-center mb-5 max-w-2xl mx-auto">
            <div class="inline-block pb-1 relative">
                <h2 class="text-xl font-bold text-[#0f2d59] tracking-tight">
                    How It Works
                </h2>
                <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-blue-600 rounded-full mx-auto w-full"></div>
            </div>
        </div>

        <!-- 4-Step Process Grid (Utilizes items-stretch + h-full for perfectly uniform card dimensions) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-stretch relative">
            <?php
            $steps = [
                [
                    'num' => '1',
                    'title' => 'Take Assessment',
                    'desc' => 'Answer questions about interests and personality.',
                    'numBg' => 'bg-blue-50',
                    'numText' => 'text-blue-500'
                ],
                [
                    'num' => '2',
                    'title' => 'Get Your Results',
                    'desc' => 'Our system analyzes your responses and generates metrics.',
                    'numBg' => 'bg-emerald-50',
                    'numText' => 'text-emerald-500'
                ],
                [
                    'num' => '3',
                    'title' => 'View Recommendations',
                    'desc' => 'Get personalized career options matching your profile.',
                    'numBg' => 'bg-pink-50',
                    'numText' => 'text-pink-500'
                ],
                [
                    'num' => '4',
                    'title' => 'Explore Details',
                    'desc' => 'Explore structural data, salaries, and growth trends.',
                    'numBg' => 'bg-amber-50',
                    'numText' => 'text-amber-500'
                ]
            ];

            foreach($steps as $index => $step):
            ?>
            <div class="relative group flex flex-col items-center md:items-start text-center md:text-left bg-white border border-slate-100 rounded-lg p-4 shadow-sm hover:shadow-md h-full w-full transition-all duration-200">
                
                <!-- Sequential Step Badge Component -->
                <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[10px] shadow-inner <?= $step['numBg'] ?> <?= $step['numText'] ?>">
                    <?= $step['num'] ?>
                </div>

                <!-- Step Title & Description Typography -->
                <h3 class="font-bold text-sm text-[#0f2d59] mt-3 tracking-tight">
                    <?= $step['title'] ?>
                </h3>

                <p class="text-xs text-slate-400 mt-1.5 leading-relaxed font-normal">
                    <?= $step['desc'] ?>
                </p>

                <!-- Vector Connector Arrows -->
                <?php if($index < 3): ?>
                <div class="hidden md:flex absolute top-1/2 -right-2.5 transform -translate-y-1/2 z-20 text-slate-300 group-hover:text-slate-400 group-hover:translate-x-0.5 transition-all duration-200">
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </div>
                <?php endif; ?>

            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>




<style>
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(24px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .hero-enter { animation: fadeInUp 0.6s ease-out forwards; }
    .hero-enter-d1 { animation-delay: 0.1s; }
    .hero-enter-d2 { animation-delay: 0.2s; }
    .hero-enter-d3 { animation-delay: 0.3s; }
    .hero-enter-d4 { animation-delay: 0.4s; }
    .fade-section {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    .fade-section.visible { opacity: 1; transform: translateY(0); }
    .card-enter {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }
    .fade-section.visible .card-enter { opacity: 1; transform: translateY(0); }
    .fade-section.visible .card-enter:nth-child(1) { transition-delay: 0ms; }
    .fade-section.visible .card-enter:nth-child(2) { transition-delay: 100ms; }
    .fade-section.visible .card-enter:nth-child(3) { transition-delay: 200ms; }
    .fade-section.visible .card-enter:nth-child(4) { transition-delay: 300ms; }
</style>

<?php
$success = $_SESSION['success'] ?? null;
if ($success) {
    echo '<div class="mx-auto mb-6 max-w-5xl rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">' . htmlspecialchars($success) . '</div>';
    unset($_SESSION['success']);
}

$colorMap = [
    'personality' => ['from' => 'from-blue-500', 'to' => 'to-blue-600', 'btn' => 'bg-blue-600 hover:bg-blue-700'],
    'interest'    => ['from' => 'from-pink-500', 'to' => 'to-pink-600', 'btn' => 'bg-pink-600 hover:bg-pink-700'],
    'aptitude'    => ['from' => 'from-green-500', 'to' => 'to-green-600', 'btn' => 'bg-green-600 hover:bg-green-700'],
    'values'      => ['from' => 'from-orange-500', 'to' => 'to-orange-600', 'btn' => 'bg-orange-600 hover:bg-orange-700'],
];
?>

<main class="flex-1 bg-slate-50">

    

    <!-- Assessment Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="fade-section text-center mb-10 lg:mb-12">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-[11px] font-bold uppercase tracking-wider mb-4">
                <i class="fas fa-layer-group text-[10px]"></i>
                Assessments
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#0f2d59]">Discover Your Ideal Career</h2>
            <p class="mt-3 text-base text-slate-500 max-w-lg mx-auto">
                <?= !empty($isLoggedIn) ? 'Complete each assessment to build your career profile.' : 'Start with a free preview, no account required.' ?>
            </p>
        </div>

        <div class="fade-section">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
                <?php foreach ($assessments as $assessment):
                    $slug = $assessment['slug'];
                    $c = $colorMap[$slug] ?? $colorMap['personality'];
                    $previewCount = $assessment['preview_questions'] ?? 5;
                    $totalCount = $assessment['questions_count'] ?? 0;
                ?>
                <div class="card-enter group relative bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="p-5 lg:p-6 flex flex-col gap-3 h-full">

                        <!-- Icon -->
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br <?= $c['from'] ?> <?= $c['to'] ?> flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 flex-shrink-0">
                            <i class="<?= $assessment['icon'] ?> text-white text-xl"></i>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-bold text-slate-900"><?= htmlspecialchars($assessment['title']) ?></h3>

                        <!-- Description -->
                        <p class="text-sm text-slate-500 leading-relaxed flex-1"><?= htmlspecialchars($assessment['description']) ?></p>

                        <?php if (!empty($isLoggedIn)): ?>
                            <?php $status = $assessment['progress']['status'] ?? 'not_started'; ?>
                            <!-- Logged-in: full question count -->
                            <div class="flex items-center gap-3 text-xs text-slate-400 font-medium">
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="fas fa-list-check text-[10px]"></i>
                                    <?= $totalCount ?> Questions
                                </span>
                            </div>

                            <!-- Progress bar for in_progress -->
                            <?php if ($status === 'completed'): ?>
                                <span class="w-full rounded-full bg-emerald-50 px-3 py-1.5 text-emerald-700 text-xs font-bold text-center border border-emerald-200">
                                    <i class="fas fa-check-circle mr-1"></i>Completed
                                </span>
                            <?php elseif ($status === 'in_progress'): ?>
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span>Progress</span>
                                        <span class="font-semibold"><?= $assessment['progress']['answered'] ?? 0 ?>/<?= $totalCount ?></span>
                                    </div>
                                    <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                        <?php $pct = $totalCount > 0 ? round((($assessment['progress']['answered'] ?? 0) / $totalCount) * 100) : 0; ?>
                                        <div class="h-full rounded-full bg-gradient-to-r <?= $c['from'] ?> <?= $c['to'] ?>" style="width: <?= min(100, $pct) ?>%"></div>
                                    </div>
                                </div>
                                <span class="w-full rounded-full bg-amber-50 px-3 py-1.5 text-amber-700 text-xs font-bold text-center border border-amber-200">
                                    <i class="fas fa-play mr-1"></i>In progress
                                </span>
                            <?php else: ?>
                                <span class="w-full rounded-full bg-slate-50 px-3 py-1.5 text-slate-500 text-xs font-bold text-center border border-slate-200">
                                    <i class="fas fa-clock mr-1"></i>Not started
                                </span>
                            <?php endif; ?>

                            <!-- Logged-in buttons -->
                            <div class="mt-1 flex flex-col gap-2">
                                <a href="<?= BASE_URL ?>/index.php?page=<?= htmlspecialchars($assessment['page']) ?>"
                                   class="<?= $c['btn'] ?> w-full rounded-xl py-3 text-center font-semibold text-white transition-all duration-300 hover:scale-105 hover:-translate-y-0.5 inline-flex items-center justify-center gap-2 text-sm shadow-md hover:shadow-lg">
                                    <i class="fas <?= $status === 'completed' ? 'fa-chart-bar' : ($status === 'in_progress' ? 'fa-play' : 'fa-play') ?> text-xs"></i>
                                    <?= $status === 'completed' ? 'View Result' : ($status === 'in_progress' ? 'Continue Assessment' : 'Start Assessment') ?>
                                </a>
                            </div>

                        <?php else: ?>
                            <!-- Guest: preview badge -->
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-[10px] font-bold uppercase tracking-wider w-fit">
                                <i class="fas fa-eye text-[8px]"></i>
                                Free Preview
                                <span class="w-px h-2.5 bg-indigo-200"></span>
                                <?= $previewCount ?> Questions
                            </span>

                    

                           

<!-- Guest buttons -->
                            <div class="mt-1 flex flex-col gap-2">
                                <a href="<?= BASE_URL ?>/index.php?page=guest-question&assessment=<?= $assessment['id'] ?>"
                                   class="<?= $c['btn'] ?> w-full rounded-xl py-3 text-center font-semibold text-white transition-all duration-300 hover:scale-105 hover:-translate-y-0.5 inline-flex items-center justify-center gap-2 text-sm shadow-md hover:shadow-lg">
                                    <i class="fas fa-play text-xs"></i>
                                    Start Free Preview
                                </a>
                                <a href="<?= BASE_URL ?>/index.php?page=login"
                                   class="w-full rounded-xl border border-slate-200 bg-white py-3 text-center font-semibold text-slate-600 transition-all duration-300 hover:border-indigo-200 hover:text-indigo-600 hover:bg-indigo-50/50 text-sm inline-flex items-center justify-center gap-2">
                                    <i class="fas fa-unlock text-xs"></i>
                                    Create Free Account
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    
</main>

<script>
    (function() {
        var sections = document.querySelectorAll('.fade-section');
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            sections.forEach(function(s) { observer.observe(s); });
        } else {
            function checkVisibility() {
                for (var i = 0; i < sections.length; i++) {
                    var rect = sections[i].getBoundingClientRect();
                    if (rect.top < window.innerHeight - 60) {
                        sections[i].classList.add('visible');
                    }
                }
            }
            checkVisibility();
            window.addEventListener('scroll', checkVisibility);
            window.addEventListener('resize', checkVisibility);
        }
    })();
</script>

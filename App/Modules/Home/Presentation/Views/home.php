<style>
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(24px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    @keyframes growWidth {
        0% { width: 0; }
        100% { width: 2.5rem; }
    }
    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    .animate-float {
        animation: float 4s ease-in-out infinite;
    }
    .fade-section {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    .fade-section.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .stagger-card {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }
    .fade-section.visible .stagger-card {
        opacity: 1;
        transform: translateY(0);
    }
    .fade-section.visible .stagger-card:nth-child(1) { transition-delay: 0ms; }
    .fade-section.visible .stagger-card:nth-child(2) { transition-delay: 100ms; }
    .fade-section.visible .stagger-card:nth-child(3) { transition-delay: 200ms; }
    .fade-section.visible .stagger-card:nth-child(4) { transition-delay: 300ms; }
    .underline-grow {
        width: 0;
        transition: width 0.6s ease-out;
    }
    .fade-section.visible .underline-grow {
        width: 2.5rem;
    }
</style>

<section class="relative bg-gradient-to-b from-white to-slate-50 py-10 lg:py-14">
    <div class="absolute top-[-15%] right-[-8%] w-[30rem] h-[30rem] bg-blue-100/50 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] left-[-5%] w-[25rem] h-[25rem] bg-indigo-100/30 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">

            <div class="animate-fade-in">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-[#0f2d59] leading-[1.12]">
                    Discover the Right<br>Career Path for You
                </h1>
                <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-lg">
                    Take assessments, explore career options, and get personalized guidance to build a successful future.
                </p>
                <div class="flex flex-wrap gap-3 mt-7">
                    <a href="<?= BASE_URL ?>/index.php?page=assessments"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-start via-brand-mid to-brand-end text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 active:scale-[0.98] transition-all duration-300 text-sm">
                        <i class="fas fa-pencil-alt text-xs"></i>
                        Take Assessment
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=careers"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-slate-200 text-slate-700 font-semibold text-sm hover:border-indigo-300 hover:text-indigo-600 hover:shadow-md hover:-translate-y-1 active:scale-[0.98] transition-all duration-300">
                        <i class="fas fa-briefcase text-xs"></i>
                        Explore Careers
                    </a>
                </div>
            </div>

            <div class="flex justify-center lg:justify-end">
                <div class="relative w-full max-w-sm lg:max-w-md group animate-float">
                    <div class="relative overflow-hidden rounded-2xl shadow-lg border border-slate-100 bg-white transition-all duration-500 group-hover:shadow-xl group-hover:shadow-indigo-200/40">
                        <img src="<?= BASE_URL ?>/assets/images/home.png"
                             class="w-full h-auto object-cover transition-all duration-500 group-hover:scale-105 group-hover:-rotate-1"
                             alt="Career Guidance System Interface">
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="fade-section py-10 lg:py-12 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 py-6 px-6 lg:px-10">
            <div class="text-center mb-6">
                <span class="inline-block text-[11px] font-bold text-indigo-600 uppercase tracking-[0.2em] mb-2">How It Works</span>
                <div class="flex justify-center mt-3">
                    <span class="inline-block h-0.5 rounded-full bg-gradient-to-r from-indigo-500 to-indigo-300 underline-grow"></span>
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-6 max-w-4xl mx-auto stagger-group">
                <div class="group bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 hover:border-indigo-300 transition-all duration-300 overflow-hidden relative flex flex-col stagger-card">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-full -mr-12 -mt-12 blur-2xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="p-5 relative flex flex-col gap-2.5 h-full">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 flex-shrink-0">
                            <i class="fas fa-clipboard-check text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Take Assessment</h3>
                        <p class="text-xs text-slate-500 leading-relaxed flex-1">Answer carefully designed questions about your interests, personality, and aptitude.</p>
                        
                    </div>
                </div>

                <div class="group bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 hover:border-indigo-300 transition-all duration-300 overflow-hidden relative flex flex-col stagger-card">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50/50 rounded-full -mr-12 -mt-12 blur-2xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="p-5 relative flex flex-col gap-2.5 h-full">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 flex-shrink-0">
                            <i class="fas fa-chart-pie text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Get Results</h3>
                        <p class="text-xs text-slate-500 leading-relaxed flex-1">Receive detailed insights and a personalized analysis of your strengths and preferences.</p>
                       
                    </div>
                </div>

                <div class="group bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 hover:border-indigo-300 transition-all duration-300 overflow-hidden relative flex flex-col stagger-card">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50/50 rounded-full -mr-12 -mt-12 blur-2xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="p-5 relative flex flex-col gap-2.5 h-full">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 flex-shrink-0">
                            <i class="fas fa-compass text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Explore Careers</h3>
                        <p class="text-xs text-slate-500 leading-relaxed flex-1">Browse career options matched to your profile and learn about each path in detail.</p>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="fade-section py-10 lg:py-12 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#0f2d59]">Popular Careers</h2>
            <div class="flex justify-center mt-4">
                <span class="inline-block h-0.5 rounded-full bg-gradient-to-r from-indigo-500 to-indigo-300 underline-grow"></span>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 stagger-group">
            <?php
            $popular = [
                ['name' => 'Software Engineer', 'category' => 'Technology', 'icon' => 'fa-code', 'slug' => 'software-engineer', 'color' => 'indigo', 'desc' => 'Design and develop software systems that power businesses and everyday life.'],
                ['name' => 'Data Analyst', 'category' => 'Technology', 'icon' => 'fa-database', 'slug' => 'data-analyst', 'color' => 'indigo', 'desc' => 'Interpret complex datasets to drive data-driven decision-making.'],
                ['name' => 'UX Designer', 'category' => 'Creative Arts', 'icon' => 'fa-paint-brush', 'slug' => 'ux-designer', 'color' => 'orange', 'desc' => 'Craft intuitive and accessible user experiences for digital products.'],
                ['name' => 'Nurse', 'category' => 'Healthcare', 'icon' => 'fa-heartbeat', 'slug' => 'nurse', 'color' => 'emerald', 'desc' => 'Provide compassionate patient care and collaborate with medical teams.'],
            ];
            $colorClasses = [
                'indigo' => ['from-indigo-500', 'to-indigo-600', 'bg-indigo-100', 'text-indigo-600'],
                'orange' => ['from-amber-500', 'to-orange-500', 'bg-amber-100', 'text-amber-600'],
                'emerald' => ['from-emerald-500', 'to-emerald-600', 'bg-emerald-100', 'text-emerald-600'],
            ];
            foreach ($popular as $i => $p):
                $c = $colorClasses[$p['color']];
            ?>
            <a href="<?= BASE_URL ?>/index.php?page=career-detail&id=<?= $p['slug'] ?>"
               class="group bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 hover:border-indigo-300 transition-all duration-300 overflow-hidden relative flex flex-col stagger-card">
                <div class="absolute top-0 right-0 w-32 h-32 bg-<?= $p['color'] ?>-50/50 rounded-full -mr-12 -mt-12 blur-2xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="p-5 relative flex flex-col gap-2.5 h-full">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br <?= $c[0] ?> <?= $c[1] ?> flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 flex-shrink-0">
                        <i class="fas <?= $p['icon'] ?> text-white text-sm"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900"><?= htmlspecialchars($p['name']) ?></h3>
                    <span class="inline-block self-start px-2.5 py-1 rounded-full <?= $c[2] ?> <?= $c[3] ?> text-[10px] font-semibold uppercase tracking-wider"><?= htmlspecialchars($p['category']) ?></span>
                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-2"><?= htmlspecialchars($p['desc']) ?></p>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 group-hover:text-indigo-800 transition-colors mt-auto pt-1">
                    View Details
                        <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-8">
            <a href="<?= BASE_URL ?>/index.php?page=careers"
               class="inline-flex items-center gap-2 bg-white text-indigo-600 font-bold px-8 py-3.5 rounded-full border border-indigo-200 shadow-sm hover:shadow-xl hover:bg-indigo-50 hover:border-indigo-300 hover:-translate-y-1 active:scale-[0.98] transition-all duration-300 text-sm">
                View All Careers
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

    </div>
</section>

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
            }, { threshold: 0.15 });

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

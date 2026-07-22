<style>
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(24px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInScale {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
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
    .stagger-item {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.4s ease-out, transform 0.4s ease-out;
    }
    .fade-section.visible .stagger-item {
        opacity: 1;
        transform: translateY(0);
    }
    .fade-section.visible .stagger-item:nth-child(1) { transition-delay: 0ms; }
    .fade-section.visible .stagger-item:nth-child(2) { transition-delay: 80ms; }
    .fade-section.visible .stagger-item:nth-child(3) { transition-delay: 160ms; }
    .fade-section.visible .stagger-item:nth-child(4) { transition-delay: 240ms; }
    .hero-animate {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    .badge-animate {
        animation: fadeInScale 0.5s ease-out 0.2s both;
    }
    .title-animate {
        animation: fadeInUp 0.6s ease-out 0.15s both;
    }
    .subtitle-animate {
        animation: fadeInUp 0.6s ease-out 0.3s both;
    }
</style>

<div class="bg-white">

    <section class="bg-white py-8 lg:py-10 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <span class="inline-flex items-center gap-1.5 py-2 px-4 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100 mb-4 badge-animate">
                About Us
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-[#0f2d59] leading-[1.12] title-animate">
                About
                <span class="text-blue-600">Career Guidance System</span>
            </h1>
            <p class="text-sm text-slate-500 max-w-xl mx-auto font-medium mt-3 leading-relaxed subtitle-animate">
                Helping students discover the right career path through assessments and personalized career guidance.
            </p>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 py-4 fade-section">
        <div class="bg-white rounded-xl border border-slate-100 shadow-md p-6">
            <div class="flex items-center gap-3 mb-4 stagger-item">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
                <h2 class="text-lg font-bold text-[#0f2d59]">Who We Are</h2>
            </div>
            <p class="text-slate-600 text-sm leading-relaxed stagger-item">
                Career Guidance is a web-based platform designed to help students understand their interests, identify their strengths, and explore suitable career opportunities.
            </p>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 py-4 fade-section">
        <div class="bg-white rounded-xl border border-slate-100 shadow-md p-6">
            <div class="flex items-center gap-3 mb-4 stagger-item">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-cog text-white text-sm"></i>
                </div>
                <h2 class="text-lg font-bold text-[#0f2d59]">What We Do</h2>
            </div>
            <div class="grid sm:grid-cols-2 gap-3">
                <div class="stagger-item flex items-center gap-3 p-3 rounded-xl bg-blue-50/50 border border-blue-100/60 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200">
                    <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-clipboard-check text-white text-xs"></i>
                    </div>
                    <span class="font-semibold text-slate-800 text-sm">Career Assessments</span>
                </div>
                <div class="stagger-item flex items-center gap-3 p-3 rounded-xl bg-blue-50/50 border border-blue-100/60 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200">
                    <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-route text-white text-xs"></i>
                    </div>
                    <span class="font-semibold text-slate-800 text-sm">Career Recommendations</span>
                </div>
                <div class="stagger-item flex items-center gap-3 p-3 rounded-xl bg-blue-50/50 border border-blue-100/60 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200">
                    <div class="w-9 h-9 rounded-lg bg-blue-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-book-open text-white text-xs"></i>
                    </div>
                    <span class="font-semibold text-slate-800 text-sm">Career Information</span>
                </div>
                <div class="stagger-item flex items-center gap-3 p-3 rounded-xl bg-blue-50/50 border border-blue-100/60 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200">
                    <div class="w-9 h-9 rounded-lg bg-indigo-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-chart-line text-white text-xs"></i>
                    </div>
                    <span class="font-semibold text-slate-800 text-sm">Progress Tracking</span>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 py-4 pb-10 fade-section">
        <div class="bg-white rounded-xl border border-slate-100 shadow-md p-6">
            <div class="flex items-center gap-3 mb-4 stagger-item">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-flag-checkered text-white text-sm"></i>
                </div>
                <h2 class="text-lg font-bold text-[#0f2d59]">Our Goal</h2>
            </div>
            <p class="text-slate-600 text-sm leading-relaxed mb-5 stagger-item">
                To support students in making confident and informed career decisions for their future.
            </p>
            <a href="<?= BASE_URL ?>/index.php?page=student-assessments-v2"
               class="stagger-item inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 text-sm">
                <i class="fas fa-pencil-alt text-xs"></i>
                Take Assessment
            </a>
        </div>
    </section>

</div>

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

<style>
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInScale {
        0% { opacity: 0; transform: scale(0.96); }
        100% { opacity: 1; transform: scale(1); }
    }
    .filter-pill.active {
        background: linear-gradient(to right, var(--brand-start, #6366f1), var(--brand-mid, #8b5cf6), var(--brand-end, #a855f7));
        color: #fff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    .career-card {
        transition: opacity 0.35s ease, transform 0.35s ease;
    }
    .career-card.page-enter {
        opacity: 0;
        transform: translateY(12px);
    }
    .career-card.page-enter-active {
        opacity: 1;
        transform: translateY(0);
    }
    .career-card.page-leave {
        opacity: 0;
        transform: translateY(-12px);
    }
    .career-card[data-visible="false"] { display: none; }
    .pagination-btn {
        transition: all 0.2s ease;
    }
    .pagination-btn.active-page {
        background: linear-gradient(to right, var(--brand-start, #6366f1), var(--brand-mid, #8b5cf6), var(--brand-end, #a855f7));
        color: #fff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    .pagination-btn:not(.active-page):not(:disabled):hover {
        background: #eef2ff;
        border-color: #a5b4fc;
    }
    .anim-search {
        opacity: 0;
        animation: fadeInScale 0.5s ease-out 0.1s both;
    }
    .anim-filters {
        opacity: 0;
        animation: fadeInUp 0.5s ease-out 0.2s both;
    }
    .anim-heading {
        opacity: 0;
        animation: fadeInUp 0.5s ease-out 0.15s both;
    }
    .anim-card-init {
        opacity: 0;
        transform: translateY(16px) scale(0.97);
        transition: opacity 0.4s ease-out, transform 0.4s ease-out;
    }
    .anim-card-init.visible {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    .anim-card-init:nth-child(1) { transition-delay: 0ms; }
    .anim-card-init:nth-child(2) { transition-delay: 50ms; }
    .anim-card-init:nth-child(3) { transition-delay: 100ms; }
    .anim-card-init:nth-child(4) { transition-delay: 150ms; }
    .anim-card-init:nth-child(5) { transition-delay: 200ms; }
    .anim-card-init:nth-child(6) { transition-delay: 250ms; }
    .anim-card-init:nth-child(7) { transition-delay: 300ms; }
    .anim-card-init:nth-child(8) { transition-delay: 350ms; }
</style>

<section class="bg-gradient-to-b from-slate-50 to-white pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="pt-8 sm:pt-10 mb-8">
            <div class="max-w-xl mx-auto anim-search">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" placeholder="Search careers..." class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 text-sm font-medium shadow-lg shadow-slate-200/50 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10 anim-filters">
            <div class="hidden sm:flex flex-wrap items-center gap-2" id="filterPills">
                <button class="filter-pill active px-4 py-2 rounded-full text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200" data-category="">All</button>
                <button class="filter-pill px-4 py-2 rounded-full text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200" data-category="Technology">Technology</button>
                <button class="filter-pill px-4 py-2 rounded-full text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200" data-category="Healthcare">Healthcare</button>
                <button class="filter-pill px-4 py-2 rounded-full text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200" data-category="Finance">Finance</button>
                <button class="filter-pill px-4 py-2 rounded-full text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200" data-category="Engineering">Engineering</button>
                <button class="filter-pill px-4 py-2 rounded-full text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200" data-category="Education">Education</button>
                <button class="filter-pill px-4 py-2 rounded-full text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200" data-category="Business">Business</button>
                <button class="filter-pill px-4 py-2 rounded-full text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200" data-category="Creative Arts">Creative Arts</button>
            </div>

            <div class="sm:hidden w-full">
                <select id="categorySelect" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 font-medium shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                    <option value="">All Categories</option>
                    <option value="Technology">Technology</option>
                    <option value="Healthcare">Healthcare</option>
                    <option value="Finance">Finance</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Education">Education</option>
                    <option value="Business">Business</option>
                    <option value="Creative Arts">Creative Arts</option>
                </select>
            </div>

            <span class="hidden sm:block text-xs text-slate-500 font-medium" id="showingInfo">Showing 1–8 of 14 careers</span>
        </div>

        <div>
            <div class="flex items-center gap-3 mb-6 anim-heading">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-th-large text-white text-xs"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Explore Careers</h2>
            </div>
            <div class="grid gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4" id="careerGrid">
                <?php
                $careers = [
                    ['name' => 'Software Engineer', 'category' => 'Technology', 'icon' => 'fa-code', 'color' => 'indigo', 'high_demand' => true, 'description' => 'Design, develop, and maintain software systems that power businesses and improve everyday life through code.'],
                    ['name' => 'Data Analyst', 'category' => 'Technology', 'icon' => 'fa-database', 'color' => 'indigo', 'high_demand' => true, 'description' => 'Interpret complex datasets, generate actionable insights, and support data-driven decision-making across organizations.'],
                    ['name' => 'Doctor', 'category' => 'Healthcare', 'icon' => 'fa-stethoscope', 'color' => 'emerald', 'high_demand' => false, 'description' => 'Diagnose and treat illnesses, provide preventive care, and educate patients on maintaining their health.'],
                    ['name' => 'Nurse', 'category' => 'Healthcare', 'icon' => 'fa-heartbeat', 'color' => 'emerald', 'high_demand' => true, 'description' => 'Provide compassionate patient care, administer treatments, and collaborate with medical teams in clinical settings.'],
                    ['name' => 'Accountant', 'category' => 'Finance', 'icon' => 'fa-calculator', 'color' => 'blue', 'high_demand' => false, 'description' => 'Prepare financial records, ensure tax compliance, and provide strategic financial guidance to organizations.'],
                    ['name' => 'Financial Analyst', 'category' => 'Finance', 'icon' => 'fa-chart-bar', 'color' => 'blue', 'high_demand' => false, 'description' => 'Analyze financial data, prepare reports, and guide investment decisions to maximize organizational profitability.'],
                    ['name' => 'Civil Engineer', 'category' => 'Engineering', 'icon' => 'fa-hard-hat', 'color' => 'cyan', 'high_demand' => true, 'description' => 'Plan, design, and oversee construction of infrastructure projects such as roads, bridges, and buildings.'],
                    ['name' => 'Mechanical Engineer', 'category' => 'Engineering', 'icon' => 'fa-cogs', 'color' => 'cyan', 'high_demand' => true, 'description' => 'Design and develop mechanical systems, from innovative product prototypes to large-scale industrial machinery.'],
                    ['name' => 'Teacher', 'category' => 'Education', 'icon' => 'fa-chalkboard-teacher', 'color' => 'purple', 'high_demand' => false, 'description' => 'Educate and inspire students, develop curricula, and foster a positive learning environment for academic growth.'],
                    ['name' => 'Professor', 'category' => 'Education', 'icon' => 'fa-graduation-cap', 'color' => 'purple', 'high_demand' => false, 'description' => 'Teach at the university level, conduct research, and mentor students in advanced academic and professional fields.'],
                    ['name' => 'Marketing Manager', 'category' => 'Business', 'icon' => 'fa-chart-line', 'color' => 'orange', 'high_demand' => false, 'description' => 'Develop marketing strategies, oversee campaigns, and drive brand growth through market research and creative initiatives.'],
                    ['name' => 'Business Analyst', 'category' => 'Business', 'icon' => 'fa-briefcase', 'color' => 'indigo', 'high_demand' => false, 'description' => 'Analyze business processes, identify improvement opportunities, and bridge the gap between IT and business stakeholders.'],
                    ['name' => 'UX Designer', 'category' => 'Creative Arts', 'icon' => 'fa-paint-brush', 'color' => 'orange', 'high_demand' => false, 'description' => 'Craft intuitive user experiences by researching user needs and designing accessible, visually engaging interfaces.'],
                    ['name' => 'Graphic Designer', 'category' => 'Creative Arts', 'icon' => 'fa-palette', 'color' => 'pink', 'high_demand' => false, 'description' => 'Create compelling visual content for brands, marketing campaigns, and digital media using design tools.'],
                ];

                $colorMap = [
                    'indigo' => ['bg' => 'indigo', 'text' => 'indigo', 'grad' => 'from-indigo-500 to-indigo-600'],
                    'emerald' => ['bg' => 'emerald', 'text' => 'emerald', 'grad' => 'from-emerald-500 to-emerald-600'],
                    'blue' => ['bg' => 'blue', 'text' => 'blue', 'grad' => 'from-blue-500 to-blue-600'],
                    'orange' => ['bg' => 'amber', 'text' => 'amber', 'grad' => 'from-amber-500 to-orange-500'],
                    'cyan' => ['bg' => 'cyan', 'text' => 'cyan', 'grad' => 'from-cyan-500 to-cyan-600'],
                    'purple' => ['bg' => 'purple', 'text' => 'purple', 'grad' => 'from-purple-500 to-purple-600'],
                    'pink' => ['bg' => 'pink', 'text' => 'pink', 'grad' => 'from-pink-500 to-pink-600'],
                ];

                $idx = 0;
                foreach ($careers as $career):
                    $c = $colorMap[$career['color']];
                    $slug = strtolower(str_replace(' ', '-', $career['name']));
                ?>
                    <div class="career-card anim-card-init group bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 hover:border-<?= $c['bg'] ?>-200 transition-all duration-300 flex flex-col overflow-hidden relative" data-category="<?= htmlspecialchars($career['category']) ?>" data-index="<?= $idx ?>">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-<?= $c['bg'] ?>-50/50 rounded-full -mr-12 -mt-12 blur-2xl pointer-events-none group-hover:opacity-100 opacity-0 transition-opacity duration-300"></div>
                        <div class="p-4 sm:p-5 flex flex-col flex-1 relative">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br <?= $c['grad'] ?> flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas <?= $career['icon'] ?> text-white text-xs"></i>
                                </div>
                                <?php if ($career['high_demand']): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        High Demand
                                    </span>
                                <?php endif; ?>
                            </div>
                            <span class="inline-block self-start px-2 py-1 rounded-full bg-<?= $c['bg'] ?>-100 text-<?= $c['text'] ?>-700 text-[10px] font-semibold uppercase tracking-wider mb-1"><?= htmlspecialchars($career['category']) ?></span>
                            <h3 class="text-sm font-bold text-slate-900"><?= htmlspecialchars($career['name']) ?></h3>
                            <p class="mt-1 text-xs text-slate-500 leading-snug line-clamp-2 flex-1"><?= htmlspecialchars($career['description']) ?></p>
                            <div class="mt-3 pt-2 border-t border-slate-100">
                                <a href="<?= BASE_URL ?>/index.php?page=career-detail&id=<?= $slug ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-<?= $c['text'] ?>-600 hover:text-<?= $c['text'] ?>-800 transition-colors group/link">
                                    View Details
                                    <i class="fas fa-arrow-right text-[10px] group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php $idx++; endforeach; ?>
            </div>
        </div>

        <div id="noResults" class="text-center py-16 hidden">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-slate-400 text-xl"></i>
            </div>
            <p class="text-slate-500 text-sm font-medium">No careers found.</p>
        </div>

        <div id="pagination" class="hidden mt-10 flex items-center justify-center gap-2">
            <button id="prevPage" class="pagination-btn inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold border border-slate-200 bg-white text-slate-600 disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                <i class="fas fa-chevron-left text-[10px]"></i>
                Previous
            </button>
            <div id="pageNumbers" class="flex items-center gap-1"></div>
            <button id="nextPage" class="pagination-btn inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold border border-slate-200 bg-white text-slate-600 disabled:opacity-40 disabled:cursor-not-allowed">
                Next
                <i class="fas fa-chevron-right text-[10px]"></i>
            </button>
        </div>

    </div>
</section>

<script>
    (function() {
        const PER_PAGE = 8;
        const searchInput = document.getElementById('searchInput');
        const filterPills = document.querySelectorAll('.filter-pill');
        const categorySelect = document.getElementById('categorySelect');
        const careerCards = document.querySelectorAll('.career-card');
        const showingInfo = document.getElementById('showingInfo');
        const noResults = document.getElementById('noResults');
        const pagination = document.getElementById('pagination');
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const pageNumbers = document.getElementById('pageNumbers');

        let currentPage = 1;
        let isAnimating = false;
        let initialLoad = true;

        function animateInitialCards() {
            const firstBatch = Array.from(careerCards).slice(0, PER_PAGE);
            firstBatch.forEach(function(card, i) {
                card.style.transitionDelay = (i * 60) + 'ms';
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        card.classList.add('visible');
                    });
                });
            });
        }

        function getFilteredIndices() {
            const query = (searchInput.value || '').toLowerCase().trim();
            const activePill = document.querySelector('.filter-pill.active');
            const category = (activePill ? activePill.dataset.category : categorySelect.value) || '';
            const indices = [];

            careerCards.forEach(function(card) {
                const name = card.querySelector('h3').textContent.toLowerCase();
                const matchesSearch = !query || name.includes(query);
                const matchesCategory = !category || card.dataset.category === category;
                if (matchesSearch && matchesCategory) {
                    indices.push(parseInt(card.dataset.index));
                }
            });

            return indices;
        }

        function updatePagination(total) {
            const totalPages = Math.max(1, Math.ceil(total / PER_PAGE));
            if (currentPage > totalPages) currentPage = totalPages;

            if (totalPages <= 1) {
                pagination.classList.add('hidden');
            } else {
                pagination.classList.remove('hidden');
            }

            prevBtn.disabled = currentPage <= 1;

            pageNumbers.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = 'pagination-btn w-9 h-9 rounded-xl text-xs font-semibold border border-slate-200 bg-white text-slate-600';
                if (i === currentPage) {
                    btn.classList.add('active-page');
                }
                btn.textContent = i;
                btn.addEventListener('click', function() { goToPage(i); });
                pageNumbers.appendChild(btn);
            }

            nextBtn.disabled = currentPage >= totalPages;
        }

        function applyPage(visibleIndices, animate) {
            const start = (currentPage - 1) * PER_PAGE;
            const end = Math.min(start + PER_PAGE, visibleIndices.length);
            const pageIndices = visibleIndices.slice(start, end);

            careerCards.forEach(function(card) {
                const idx = parseInt(card.dataset.index);
                const onPage = pageIndices.indexOf(idx) !== -1;

                if (onPage) {
                    card.dataset.visible = 'true';
                    card.style.display = '';
                    if (animate) {
                        card.classList.remove('anim-card-init');
                        card.classList.add('page-enter');
                        card.style.transitionDelay = '';
                        requestAnimationFrame(function() {
                            requestAnimationFrame(function() {
                                card.classList.remove('page-enter');
                                card.classList.add('page-enter-active');
                                setTimeout(function() {
                                    card.classList.remove('page-enter-active');
                                }, 350);
                            });
                        });
                    }
                } else {
                    card.dataset.visible = 'false';
                    card.style.display = 'none';
                }
            });

            const total = visibleIndices.length;
            const showingStart = total > 0 ? start + 1 : 0;
            const showingEnd = Math.min(end, total);
            showingInfo.textContent = total > 0
                ? 'Showing ' + showingStart + '\u2013' + showingEnd + ' of ' + total + ' career' + (total !== 1 ? 's' : '')
                : '0 careers';

            noResults.classList.toggle('hidden', total > 0);
            updatePagination(total);
        }

        function goToPage(page) {
            if (isAnimating || page === currentPage) return;
            currentPage = page;
            renderView(true);
        }

        function renderView(animate) {
            isAnimating = true;
            const indices = getFilteredIndices();
            applyPage(indices, animate);
            setTimeout(function() { isAnimating = false; }, 400);
        }

        searchInput.addEventListener('input', function() {
            currentPage = 1;
            renderView(false);
        });

        filterPills.forEach(function(pill) {
            pill.addEventListener('click', function() {
                filterPills.forEach(function(p) { p.classList.remove('active'); });
                this.classList.add('active');
                categorySelect.value = this.dataset.category || '';
                currentPage = 1;
                renderView(true);
            });
        });

        categorySelect.addEventListener('change', function() {
            filterPills.forEach(function(p) {
                p.classList.toggle('active', p.dataset.category === this.value);
            }, this);
            if (!this.value) {
                filterPills[0].classList.add('active');
            }
            currentPage = 1;
            renderView(true);
        });

        prevBtn.addEventListener('click', function() {
            if (currentPage > 1) goToPage(currentPage - 1);
        });

        nextBtn.addEventListener('click', function() {
            const total = getFilteredIndices().length;
            const totalPages = Math.max(1, Math.ceil(total / PER_PAGE));
            if (currentPage < totalPages) goToPage(currentPage + 1);
        });

        renderView(false);
        setTimeout(animateInitialCards, 50);
    })();
</script>

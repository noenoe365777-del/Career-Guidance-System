<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-indigo-800 to-blue-900">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-indigo-500/20 rounded-full blur-[120px]"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
        <div class="max-w-3xl mx-auto text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-indigo-200 text-xs font-semibold uppercase tracking-[0.2em] mb-6">Your Future Starts Here</span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight">Explore Career Opportunities</h1>
            <p class="mt-5 text-lg sm:text-xl text-indigo-200/80 max-w-2xl mx-auto">Discover the career that aligns with your skills, interests, and personality. Take the first step toward a fulfilling professional journey.</p>
            <form class="mt-10 max-w-xl mx-auto flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Search careers..." class="w-full pl-11 pr-4 py-3.5 rounded-xl border-0 bg-white text-slate-900 placeholder-slate-400 text-sm font-medium shadow-lg shadow-indigo-900/20 focus:ring-2 focus:ring-indigo-400 outline-none transition-all">
                </div>
                <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-brand-start to-brand-mid text-white font-semibold text-sm rounded-xl shadow-lg hover:scale-105 hover:shadow-indigo-500/30 transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </form>
        </div>
    </div>
</section>



<!-- Careers Section -->
<section class="py-16 bg-slate-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
            <div>
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">Browse Categories</span>
                <h2 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-900">Explore Careers</h2>
                <p class="mt-2 text-slate-500 text-sm">Select a category to filter careers or browse all listings below.</p>
            </div>
            <div class="w-full sm:w-64">
                <select class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 font-medium shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                    <option value="">All Categories</option>
                    <option value="technology">Technology</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="education">Education</option>
                    <option value="finance">Finance</option>
                    <option value="creative">Creative Arts</option>
                    <option value="engineering">Engineering</option>
                </select>
            </div>
        </div>

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            <?php
            $careers = [
                [
                    'name' => 'Software Engineer',
                    'category' => 'Technology',
                    'description' => 'Design, develop, and maintain software systems that power businesses and improve everyday life through code.',
                ],
                [
                    'name' => 'Data Analyst',
                    'category' => 'Technology',
                    'description' => 'Interpret complex datasets, generate actionable insights, and support data-driven decision-making across organizations.',
                ],
                [
                    'name' => 'UX Designer',
                    'category' => 'Creative Arts',
                    'description' => 'Craft intuitive user experiences by researching user needs and designing accessible, visually engaging interfaces.',
                ],
                [
                    'name' => 'Registered Nurse',
                    'category' => 'Healthcare',
                    'description' => 'Provide compassionate patient care, administer treatments, and collaborate with medical teams in clinical settings.',
                ],
                [
                    'name' => 'Financial Analyst',
                    'category' => 'Finance',
                    'description' => 'Analyze financial data, prepare reports, and guide investment decisions to maximize organizational profitability.',
                ],
                [
                    'name' => 'Civil Engineer',
                    'category' => 'Engineering',
                    'description' => 'Plan, design, and oversee construction of infrastructure projects such as roads, bridges, and buildings.',
                ],
                [
                    'name' => 'Teacher',
                    'category' => 'Education',
                    'description' => 'Educate and inspire students, develop curricula, and foster a positive learning environment for academic growth.',
                ],
                [
                    'name' => 'Graphic Designer',
                    'category' => 'Creative Arts',
                    'description' => 'Create compelling visual content for brands, marketing campaigns, and digital media using design tools.',
                ],
            ];

            foreach ($careers as $career):
            ?>
                <div class="group bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg hover:border-indigo-200 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-block px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[11px] font-semibold uppercase tracking-wider"><?= htmlspecialchars($career['category']) ?></span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900"><?= htmlspecialchars($career['name']) ?></h3>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed flex-1"><?= htmlspecialchars($career['description']) ?></p>
                        <div class="mt-5 pt-4 border-t border-slate-100">
                            <a href="<?= BASE_URL ?>/index.php?page=careers&id=<?= urlencode(strtolower(str_replace(' ', '-', $career['name']))) ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors group/link">
                                View Details
                                <i class="fas fa-arrow-right text-[11px] group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

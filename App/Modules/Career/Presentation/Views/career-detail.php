<div class="bg-gray">

    <section class="relative overflow-hidden bg-gradient-to-b from-white via-indigo-50/20 to-slate-50 py-16 lg:py-20">
        <div class="absolute top-0 right-0 -z-10 h-72 w-72 rounded-full bg-indigo-200/30 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -z-10 h-96 w-96 rounded-full bg-blue-200/20 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <a href="<?= BASE_URL ?>/index.php?page=careers" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors mb-6">
                <i class="fas fa-arrow-left text-xs"></i>
                Back to Careers
            </a>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="max-w-2xl">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-semibold uppercase tracking-wider"><?= htmlspecialchars($career['category']) ?></span>
                        <?php if (!empty($career['high_demand'])): ?>
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-semibold uppercase tracking-wider">High Demand</span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#0f2d59] leading-[1.12]"><?= htmlspecialchars($career['name']) ?></h1>
                    <p class="mt-4 text-base sm:text-lg text-slate-500 leading-relaxed"><?= htmlspecialchars($career['description']) ?></p>
                </div>
                <div class="flex-shrink-0">
                    <a href="<?= BASE_URL ?>/index.php?page=assessments" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-start via-brand-mid to-brand-end text-white font-bold px-7 py-3 rounded-xl shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 text-sm">
                        <i class="fas fa-pencil-alt text-xs"></i>
                        Take Assessment
                    </a>
                </div>
            </div>

        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-graduation-cap text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Education</h2>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3 text-sm text-slate-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 flex-shrink-0 mt-2"></span>
                        <?= htmlspecialchars($career['education']) ?>
                    </li>
                    <?php if (!empty($career['certification'])): ?>
                    <li class="flex items-start gap-3 text-sm text-slate-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0 mt-2"></span>
                        <?= htmlspecialchars($career['certification']) ?>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-coins text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Salary</h2>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">
                    <?= number_format($career['salary_min']) ?> – <?= number_format($career['salary_max']) ?>
                    <span class="text-sm font-medium text-slate-400">MMK/month</span>
                </p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-blue-500 flex items-center justify-center shadow-md">
                        <i class="fas fa-chart-line text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Job Outlook</h2>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed"><?= htmlspecialchars($career['job_outlook']) ?> demand expected in the coming years.</p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center shadow-md">
                        <i class="fas fa-map-marked-alt text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Work Environment</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($career['work_environment'] as $env): ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-medium">
                        <i class="fas <?= htmlspecialchars($env['icon']) ?> text-violet-500 text-[10px]"></i>
                        <?= htmlspecialchars($env['place']) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 lg:pb-12">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-tools text-white text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Required Skills</h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php foreach (array_slice($career['skills'], 0, 5) as $skill): ?>
                <span class="px-4 py-2 rounded-full bg-slate-100 text-slate-700 text-sm font-medium hover:bg-indigo-100 hover:text-indigo-700 transition-colors"><?= htmlspecialchars($skill) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 lg:pb-12">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-check-circle text-white text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Is This Right For You?</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <?php foreach (array_slice($career['right_for_you'], 0, 4) as $item): ?>
                <div class="flex items-start gap-3 p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100/60">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-check text-white text-[10px]"></i>
                    </div>
                    <span class="text-sm font-medium text-slate-700"><?= htmlspecialchars($item) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if (!empty($relatedCareers)): ?>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md">
                <i class="fas fa-briefcase text-white text-sm"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-900">Related Careers</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($relatedCareers as $relatedSlug => $related): ?>
            <a href="<?= BASE_URL ?>/index.php?page=career-detail&id=<?= $relatedSlug ?>" class="group bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md mb-4">
                    <i class="fas <?= htmlspecialchars($related['icon'] ?? 'fa-briefcase') ?> text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors"><?= htmlspecialchars($related['name']) ?></h3>
                <span class="inline-block mt-1.5 px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-semibold uppercase tracking-wider"><?= htmlspecialchars($related['category']) ?></span>
                <p class="mt-3 text-sm text-slate-500 leading-relaxed line-clamp-2"><?= htmlspecialchars($related['description']) ?></p>
                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 mt-4 group-hover:gap-2.5 transition-all">
                    View Details
                    <i class="fas fa-arrow-right text-xs"></i>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</div>

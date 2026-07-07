<?php
/**
 * Careers overview page
 */
?>

<section class="py-10 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Explore Careers</p>
            <h1 class="mt-3 text-3xl sm:text-4xl font-bold text-slate-900">Discover career paths that match your strengths</h1>
            <p class="mt-4 text-lg text-slate-600">
                Browse a curated set of career options, understand what they involve, and use the assessment results to find the most suitable choices for your future.
            </p>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <?php
            $careers = [
                [
                    'title' => 'Software Engineer',
                    'summary' => 'Build applications, solve technical problems, and create digital products for businesses and users.',
                    'fit' => 'Best for analytical thinkers and problem solvers.'
                ],
                [
                    'title' => 'Data Analyst',
                    'summary' => 'Turn raw information into insights and help teams make smarter decisions.',
                    'fit' => 'Best for detail-oriented people who enjoy patterns and numbers.'
                ],
                [
                    'title' => 'UX Designer',
                    'summary' => 'Design user-friendly interfaces and improve the experience of websites and apps.',
                    'fit' => 'Best for creative thinkers who care about people and usability.'
                ],
                [
                    'title' => 'Graphic Designer',
                    'summary' => 'Create visual brands, digital graphics, and campaigns that communicate clearly.',
                    'fit' => 'Best for imaginative individuals with strong visual taste.'
                ],
                [
                    'title' => 'Project Manager',
                    'summary' => 'Coordinate teams, plans, and resources to deliver projects successfully.',
                    'fit' => 'Best for organized leaders with communication skills.'
                ],
                [
                    'title' => 'Teacher',
                    'summary' => 'Guide learners, share knowledge, and help others grow academically and personally.',
                    'fit' => 'Best for patient and supportive people who enjoy mentoring.'
                ],
            ];

            foreach ($careers as $career):
            ?>
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900"><?= htmlspecialchars($career['title']); ?></h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        <?= htmlspecialchars($career['summary']); ?>
                    </p>
                    <div class="mt-4 rounded-lg bg-blue-50 px-3 py-2 text-sm text-blue-700">
                        <?= htmlspecialchars($career['fit']); ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

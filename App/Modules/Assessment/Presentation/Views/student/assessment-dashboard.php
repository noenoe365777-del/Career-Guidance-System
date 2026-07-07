
<div class="max-w-7xl mx-auto px-6 py-16">

    <!-- Page Heading -->
    <div class="text-center mb-14 animate-fadeUp">

        <h1 class="text-5xl font-extrabold text-blue-900">
            My Assessments
        </h1>

        <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
            Welcome! Complete all assessments below to unlock your personalized career recommendations.
        </p>

    </div>


    <!-- Cards -->
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">

        <?php foreach($assessments as $assessment): ?>

        <div
        class="bg-white
               rounded-3xl
               shadow-lg
               p-8
               flex
               flex-col
               items-center
               hover:-translate-y-3
               hover:shadow-2xl
               transition-all
               duration-500">

            <!-- Icon -->
            <div
            class="w-24
                   h-24
                   rounded-full
                   flex
                   items-center
                   justify-center
                   <?= $assessment['iconBg']; ?>
                   mb-6
                   transition
                   duration-500
                   hover:scale-110">

                <i class="<?= $assessment['icon']; ?> <?= $assessment['iconColor']; ?> text-5xl"></i>

            </div>


            <!-- Title -->
            <h2 class="text-2xl font-bold text-center text-gray-800">

                <?= $assessment['title']; ?>

            </h2>


            <!-- Description -->
            <p class="text-center text-gray-600 mt-4 leading-7 flex-grow">

                <?= $assessment['description']; ?>

            </p>


            <!-- Questions -->
            <div class="mt-6">

                <span class="bg-gray-100 px-4 py-2 rounded-full text-sm font-medium">

                    <?= $assessment['questions']; ?>

                </span>

            </div>


            <!-- Button -->
            <a
            href="<?= BASE_URL ?>/index.php?page=<?= $assessment['page']; ?>"
            class="<?= $assessment['button']; ?>
                   text-white
                   w-full
                   text-center
                   mt-8
                   py-3
                   rounded-xl
                   font-semibold
                   transition
                   duration-300
                   hover:scale-105">

                <i class="fas fa-play mr-2"></i>

                Start Assessment

            </a>

        </div>

        <?php endforeach; ?>

    </div>

</div>

<?php

$extraJs="/career-guidance-system/Public/assets/js/assessment.js";



?>
<?php
$result = $_SESSION['guest_result'] ?? null;
unset($_SESSION['guest_result']);
?>

<main class="flex-1 bg-slate-50/50">
    <div class="mx-auto max-w-3xl px-6 py-16 text-center">
        <?php if ($result): ?>
            <div class="rounded-full bg-green-100 w-20 h-20 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check-circle text-4xl text-green-600"></i>
            </div>

            <h1 class="text-4xl font-extrabold text-gray-900">Assessment Complete!</h1>
            <p class="mt-3 text-lg text-gray-600">You completed the <?= htmlspecialchars($result['title']) ?>.</p>

            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Your Score</p>
                <p class="mt-2 text-5xl font-extrabold text-indigo-600"><?= (int)$result['score'] ?>%</p>
                <p class="mt-4 text-sm text-slate-600"><?= htmlspecialchars($result['summary']) ?></p>
            </div>

            <div class="mt-10 rounded-2xl border border-amber-200 bg-amber-50 p-8">
                <h2 class="text-xl font-bold text-amber-900">Want to save your results?</h2>
                <p class="mt-2 text-sm text-amber-700">Register or log in to keep your assessment history and unlock personalized career recommendations.</p>
                <div class="mt-6 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                    <a href="<?= BASE_URL ?>/index.php?page=register"
                       class="rounded-xl bg-gradient-to-r from-brand-start to-brand-mid px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:scale-105">
                        <i class="fas fa-user-plus mr-2"></i>Register for Free
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=login"
                       class="rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                        <i class="fas fa-sign-in-alt mr-2"></i>Log In
                    </a>
                </div>
            </div>

            <div class="mt-8">
                <a href="<?= BASE_URL ?>/index.php?page=assessments"
                   class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Assessments
                </a>
            </div>
        <?php else: ?>
            <div class="rounded-full bg-slate-100 w-20 h-20 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-file-alt text-4xl text-slate-400"></i>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-900">No Result Found</h1>
            <p class="mt-3 text-lg text-gray-600">You haven't completed any assessment yet.</p>
            <a href="<?= BASE_URL ?>/index.php?page=assessments"
               class="mt-8 inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-indigo-700">
                <i class="fas fa-arrow-left mr-2"></i>Browse Assessments
            </a>
        <?php endif; ?>
    </div>
</main>

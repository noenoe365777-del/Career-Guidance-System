<?php
$success = $_SESSION['success'] ?? null;
if ($success) {
    echo '<div class="mx-auto mb-6 max-w-5xl rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">' . htmlspecialchars($success) . '</div>';
    unset($_SESSION['success']);
}
?>

<main class="flex-1 bg-slate-50/50">
    <div class="mx-auto max-w-7xl px-6 py-16">
        <div class="mb-14 text-center">
            <span class="inline-flex items-center rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-[#15479A]">
                📝 Career Assessments
            </span>
            <h1 class="mt-5 text-4xl font-extrabold leading-tight text-gray-900 lg:text-5xl">
                Choose the assessment that fits you best
            </h1>
            <p class="mx-auto mt-4 max-w-3xl text-lg leading-8 text-gray-600">
                Try a quick preview as a guest or sign in to save progress, resume later, and unlock personalized career recommendations.
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-4">
            <?php foreach ($assessments as $assessment): ?>
                <div class="flex flex-col items-center rounded-3xl border border-gray-100 bg-white p-8 shadow-lg transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl">
                    <div class="mb-6 flex h-24 w-24 items-center justify-center rounded-full <?= $assessment['iconBg']; ?> transition duration-500 hover:scale-110">
                        <i class="<?= $assessment['icon']; ?> <?= $assessment['iconColor']; ?> text-5xl"></i>
                    </div>

                    <h2 class="text-center text-2xl font-bold text-gray-800">
                        <?= htmlspecialchars($assessment['title']); ?>
                    </h2>

                    <p class="mt-4 flex-grow text-center leading-7 text-gray-600">
                        <?= htmlspecialchars($assessment['description']); ?>
                    </p>

                    <div class="mt-6">
                        <span class="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium">
                            <?= htmlspecialchars($assessment['questions']); ?>
                        </span>
                    </div>

                    <div class="mt-8 flex w-full flex-col gap-3">
                        <?php if (!empty($isLoggedIn)):
                            $status = $assessment['progress']['status'] ?? 'not_started';
                        ?>
                            <div class="mt-2 text-sm font-medium text-slate-500">
                                <?php if ($status === 'completed'): ?>
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-700">Completed</span>
                                <?php elseif ($status === 'in_progress'): ?>
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-700">In progress</span>
                                <?php else: ?>
                                    <span class="rounded-full bg-slate-50 px-3 py-1 text-slate-500">Not started</span>
                                <?php endif; ?>
                            </div>
                            <a href="<?= BASE_URL ?>/index.php?page=<?= htmlspecialchars($assessment['page']); ?>"
                               class="<?= $assessment['button']; ?> w-full rounded-xl py-3 text-center font-semibold text-white transition duration-300 hover:scale-105">
                                <i class="fas fa-play mr-2"></i>
                                <?php if ($status === 'completed'): ?>
                                    View Result
                                <?php elseif ($status === 'in_progress'): ?>
                                    Continue Assessment
                                <?php else: ?>
                                    Start Assessment
                                <?php endif; ?>
                            </a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/index.php?page=<?= htmlspecialchars($assessment['page']); ?>"
                               class="<?= $assessment['button']; ?> w-full rounded-xl py-3 text-center font-semibold text-white transition duration-300 hover:scale-105">
                                <i class="fas fa-play mr-2"></i>
                                Try as Guest
                            </a>
                            <a href="<?= BASE_URL ?>/index.php?page=login"
                               class="w-full rounded-xl border border-slate-200 bg-white py-3 text-center font-semibold text-slate-700 transition duration-300 hover:border-slate-300 hover:bg-slate-50">
                                <i class="fas fa-lock mr-2"></i>
                                Login to Save
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<?php
$catalog = [
    ['slug' => 'personality', 'title' => 'Personality Assessment', 'link' => 'personality'],
    ['slug' => 'interest', 'title' => 'Interest Assessment', 'link' => 'interest'],
    ['slug' => 'aptitude', 'title' => 'Aptitude Assessment', 'link' => 'aptitude'],
    ['slug' => 'values', 'title' => 'Career Values Assessment', 'link' => 'values'],
];
$success = $_SESSION['success'] ?? null;
if ($success) {
    echo '<div class="mx-auto mb-6 max-w-5xl rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">' . htmlspecialchars($success) . '</div>';
    unset($_SESSION['success']);
}
?>

<main class="flex-1 bg-slate-50/50">
    <div class="mx-auto max-w-6xl px-6 py-16">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Assessment Progress</p>
                    <h1 class="mt-2 text-4xl font-extrabold text-slate-900">Your progress at a glance</h1>
                    <p class="mt-3 max-w-2xl text-slate-600">
                        Keep moving through each assessment to build a richer profile. Guests can preview the flow, while logged-in students can save and resume later.
                    </p>
                </div>
                <?php if (!empty($user)): ?>
                    <a href="<?= BASE_URL ?>/index.php?page=assessments" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                        Return to Dashboard
                    </a>
                <?php else: ?>
                    <div class="flex gap-3">
                        <a href="<?= BASE_URL ?>/index.php?page=login" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Login</a>
                        <a href="<?= BASE_URL ?>/index.php?page=register" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Register</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-8 grid gap-4 lg:grid-cols-2">
                <?php foreach ($catalog as $item): ?>
                    <?php $entry = $progress[$item['slug']] ?? null; $guestEntry = $guestProgress[$item['slug']] ?? null; $isCompleted = !empty($entry) && (($entry['status'] ?? '') === 'completed' || ($entry['is_completed'] ?? false)); ?>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900"><?= htmlspecialchars($item['title']); ?></h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    <?= $isCompleted ? 'Completed and saved for your account' : (!empty($entry) ? 'In progress' : (!empty($guestEntry) ? 'Saved as a guest preview' : 'Not started yet')) ?>
                                </p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold <?= $isCompleted ? 'bg-emerald-50 text-emerald-700' : (!empty($entry) || !empty($guestEntry) ? 'bg-amber-50 text-amber-700' : 'bg-slate-200 text-slate-600') ?>">
                                <?= $isCompleted ? 'Completed' : (!empty($entry) ? 'In Progress' : (!empty($guestEntry) ? 'Guest Preview' : 'Pending')) ?>
                            </span>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-sm text-slate-600">
                            <span><?= $isCompleted ? 'Completed' : (!empty($entry) ? 'In progress' : (!empty($guestEntry) ? 'Preview completed' : 'Ready to start')) ?></span>
                            <a href="<?= BASE_URL ?>/index.php?page=<?= htmlspecialchars($item['link']) ?>" class="font-semibold text-blue-600 hover:text-blue-700">Open</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

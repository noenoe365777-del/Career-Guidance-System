<?php
$pageTitle = $pageTitle ?? 'Forgot Password';
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? null;
unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['success']);
?>

<section class="min-h-[60vh] flex items-center justify-center py-20">
    <div class="max-w-lg w-full bg-white rounded-2xl p-8 shadow">
        <h2 class="text-2xl font-bold mb-4">Forgot Password</h2>

        <?php if ($success): ?>
            <div class="p-3 mb-4 rounded bg-emerald-100 text-emerald-800"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (isset($errors['email'])): ?>
            <div class="p-3 mb-4 rounded bg-red-100 text-red-800"><?= htmlspecialchars($errors['email']) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/index.php?page=forgot-password" method="post" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Registered Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                       class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm" />
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold">Send Reset Code</button>
                <a href="<?= BASE_URL ?>/index.php?page=login" class="text-sm text-slate-500">Back to login</a>
            </div>
        </form>
    </div>
</section>

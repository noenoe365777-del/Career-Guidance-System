<?php
$pageTitle = $pageTitle ?? 'Reset Password';
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
$pendingEmail = $_SESSION['pending_password_reset_email'] ?? '';
unset($_SESSION['errors'], $_SESSION['old']);
?>

<section class="min-h-[60vh] flex items-center justify-center py-20">
    <div class="max-w-lg w-full bg-white rounded-2xl p-8 shadow">
        <h2 class="text-2xl font-bold mb-4">Reset Your Password</h2>

        <?php if (isset($errors['password'])): ?>
            <div class="p-3 mb-4 rounded bg-red-100 text-red-800"><?= htmlspecialchars($errors['password']) ?></div>
        <?php endif; ?>

        <?php if (isset($errors['confirm_password'])): ?>
            <div class="p-3 mb-4 rounded bg-red-100 text-red-800"><?= htmlspecialchars($errors['confirm_password']) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/index.php?page=reset-password" method="post" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Registered Email</label>
                <input type="email" disabled value="<?= htmlspecialchars($pendingEmail) ?>"
                       class="mt-1 block w-full rounded-lg border-gray-200 bg-slate-100 shadow-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">New Password</label>
                <input type="password" name="password" required
                       class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Confirm New Password</label>
                <input type="password" name="confirm_password" required
                       class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm" />
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold">Reset Password</button>
                <a href="<?= BASE_URL ?>/index.php?page=login" class="text-sm text-slate-500">Back to login</a>
            </div>
        </form>
    </div>
</section>

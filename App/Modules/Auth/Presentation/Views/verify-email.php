<?php
$pageTitle = $pageTitle ?? 'Verify Email';
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? null;
$pendingEmail = $_SESSION['pending_verification_email'] ?? '';
$pendingUserId = $_SESSION['pending_verification_user_id'] ?? 0;
unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['success']);
?>

<section class="min-h-[60vh] flex items-center justify-center py-20">
    <div class="max-w-lg w-full bg-white rounded-2xl p-8 shadow">
        <h2 class="text-2xl font-bold mb-4">Verify Your Email</h2>

        <?php if ($success): ?>
            <div class="p-3 mb-4 rounded bg-emerald-100 text-emerald-800"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (isset($errors['verification'])): ?>
            <div class="p-3 mb-4 rounded bg-red-100 text-red-800"><?= htmlspecialchars($errors['verification']) ?></div>
        <?php endif; ?>

        <?php if (isset($errors['resend'])): ?>
            <div class="p-3 mb-4 rounded bg-red-100 text-red-800"><?= htmlspecialchars($errors['resend']) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/index.php?page=verify-email" method="post" class="space-y-4">
            <input type="hidden" name="user_id" value="<?= (int)($old['user_id'] ?? $pendingUserId) ?>" />

            <div>
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? $pendingEmail) ?>" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Verification Code</label>
                <input type="text" name="code" required maxlength="6" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm" />
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold">Verify</button>
                <a href="<?= BASE_URL ?>/index.php?page=login" class="text-sm text-slate-500">Back to login</a>
            </div>
        </form>

        <div class="mt-4 border-t pt-4">
            <form action="<?= BASE_URL ?>/index.php?page=resend-verification" method="post" class="flex gap-3 items-center">
                <input type="hidden" name="email" value="<?= htmlspecialchars($old['email'] ?? $pendingEmail) ?>" />
                <input type="hidden" name="user_id" value="<?= (int)($old['user_id'] ?? $pendingUserId) ?>" />
                <button type="submit" class="px-4 py-2 bg-white border border-slate-200 rounded-lg">Resend Code</button>
                <span class="text-sm text-slate-500">Didn't receive the code? Click to resend.</span>
            </form>
        </div>
    </div>
</section>

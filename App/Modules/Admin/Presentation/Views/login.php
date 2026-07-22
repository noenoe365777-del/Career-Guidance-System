<?php
$error = $error ?? ($_SESSION['error'] ?? '');
unset($_SESSION['error']);
$pageTitle = 'Admin Login';
?>
<?php require BASE_PATH . '/App/Views/partials/header.php'; ?>

<main class="flex flex-1 items-center justify-center px-4 py-12">
    <div class="w-full max-w-[400px]">

       

        <div class="rounded-xl border border-[#E5E7EB] bg-white px-8 py-10 shadow-sm">
            <div class="mb-7 text-center">
                <h2 class="text-xl font-bold text-[#111827]">Administrator Login</h2>
                <p class="mt-2 text-sm leading-relaxed text-[#6B7280]">Sign in to manage students, assessments, career recommendations, and reports.</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-login" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-[#111827]">Email</label>
                    <input type="email" id="email" name="email" required placeholder="you@example.com"
                        class="mt-1.5 block w-full rounded-lg border border-[#E5E7EB] bg-white px-4 py-2.5 text-sm text-[#111827] placeholder-[#9CA3AF] outline-none transition-colors focus:border-[#4F46E5] focus:ring-2 focus:ring-[#4F46E5]/15">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#111827]">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password"
                        class="mt-1.5 block w-full rounded-lg border border-[#E5E7EB] bg-white px-4 py-2.5 text-sm text-[#111827] placeholder-[#9CA3AF] outline-none transition-colors focus:border-[#4F46E5] focus:ring-2 focus:ring-[#4F46E5]/15">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex cursor-pointer items-center gap-2 select-none">
                        <input type="checkbox" name="remember"
                            class="h-4 w-4 rounded border-[#D1D5DB] text-[#4F46E5] outline-none transition-colors focus:ring-2 focus:ring-[#4F46E5]/15">
                        <span class="text-sm text-[#6B7280]">Remember me</span>
                    </label>
                    <a href="#" class="text-sm font-medium text-[#4F46E5] no-underline transition-colors hover:text-[#4338CA]">Forgot password?</a>
                </div>

                <button type="submit"
                    class="w-full cursor-pointer rounded-lg bg-[#4F46E5] px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[#4338CA] active:bg-[#3730A3]">
                    Sign in
                </button>
            </form>

            <div class="mt-8 text-center text-xs text-[#9CA3AF]">
                Authorized administrators only.
            </div>
        </div>
    </div>
</main>

<?php require BASE_PATH . '/App/Views/partials/footer.php'; ?>

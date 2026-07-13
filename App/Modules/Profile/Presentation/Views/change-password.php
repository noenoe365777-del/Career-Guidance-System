<?php
$errors = $_SESSION['errors'] ?? [];
$successMsg = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>
<div class="mx-auto w-full max-w-2xl px-4 py-6 sm:px-6 sm:py-8">

    <!-- Toast -->
    <?php if ($successMsg): ?>
    <div id="successToast" class="fixed right-6 top-24 z-50 flex max-w-sm items-center gap-3 rounded-2xl border border-emerald-200 bg-white p-4 shadow-xl transition-all duration-500">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
            <i class="fas fa-check text-sm"></i>
        </div>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-slate-900">Success</p>
            <p class="text-xs text-slate-500"><?= htmlspecialchars($successMsg) ?></p>
        </div>
        <button type="button" onclick="this.closest('#successToast').remove()" class="ml-2 flex h-6 w-6 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
    <script>setTimeout(() => { const t = document.getElementById('successToast'); if (t) t.remove(); }, 4000);</script>
    <?php endif; ?>

    <!-- Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Change Password</h1>
            <p class="mt-0.5 text-sm text-slate-500">Update your account password.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=profile" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 no-underline transition-all duration-200 hover:bg-slate-50">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to Profile
        </a>
    </div>

    <!-- Password Form Card -->
    <section class="rounded-[20px] border border-[#E5E7EB] bg-white p-6 shadow-sm sm:p-8">
        <form action="<?= BASE_URL ?>/index.php?page=update-password" method="POST" novalidate>
            <input type="hidden" name="_redirect" value="change-password">

            <?php if (!empty($errors)): ?>
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="text-xs font-semibold text-red-700">Please fix the following errors:</p>
                <ul class="mt-2 list-inside list-disc space-y-1 text-xs text-red-600">
                    <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="mb-6 border-b border-slate-100 pb-4">
                <h2 class="text-base font-bold text-slate-900">Password</h2>
                <p class="mt-0.5 text-sm text-slate-500">Choose a strong password you haven't used before.</p>
            </div>

            <div class="space-y-5">
                <!-- Current Password -->
                <div>
                    <label for="currentPassword" class="mb-1.5 block text-xs font-semibold text-slate-700">Current Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="currentPassword" name="current_password" required
                            class="w-full rounded-xl border border-[#E5E7EB] px-4 py-2.5 pr-10 text-sm text-slate-900 outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20">
                        <button type="button" onclick="togglePassword('currentPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label for="newPassword" class="mb-1.5 block text-xs font-semibold text-slate-700">New Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="newPassword" name="new_password" required
                            class="w-full rounded-xl border border-[#E5E7EB] px-4 py-2.5 pr-10 text-sm text-slate-900 outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20">
                        <button type="button" onclick="togglePassword('newPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    <!-- Strength bars -->
                    <div class="mt-3 grid grid-cols-4 gap-2">
                        <div id="bar1" class="h-2 rounded bg-gray-200 transition-all duration-300"></div>
                        <div id="bar2" class="h-2 rounded bg-gray-200 transition-all duration-300"></div>
                        <div id="bar3" class="h-2 rounded bg-gray-200 transition-all duration-300"></div>
                        <div id="bar4" class="h-2 rounded bg-gray-200 transition-all duration-300"></div>
                    </div>
                    <p id="strengthText" class="mt-1 text-xs font-semibold text-red-500">Weak</p>
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="confirmPassword" class="mb-1.5 block text-xs font-semibold text-slate-700">Confirm New Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="confirmPassword" name="confirm_password" required
                            class="w-full rounded-xl border border-[#E5E7EB] px-4 py-2.5 pr-10 text-sm text-slate-900 outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20">
                        <button type="button" onclick="togglePassword('confirmPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    <p id="matchMessage" class="mt-1 text-xs"></p>
                </div>
            </div>

            <!-- Submit -->
            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                <a href="<?= BASE_URL ?>/index.php?page=profile" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#E5E7EB] bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 no-underline transition-all duration-200 hover:bg-slate-50 sm:w-auto">
                    Cancel
                </a>
                <button type="submit" id="submitBtn" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#5B5CEB] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#4a4bd6] disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                    <span id="btnText">Update Password</span>
                </button>
            </div>
        </form>
    </section>

</div>

<?php if (isset($extraJs)): ?>
<script src="<?= BASE_URL ?>/<?= $extraJs ?>"></script>
<?php endif; ?>

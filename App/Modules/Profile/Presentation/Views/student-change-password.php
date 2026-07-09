<?php $pageTitle = "Change Password"; ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-10 tracking-tight text-slate-800">
    <div class="max-w-2xl mx-auto space-y-8">

        <?php if (!empty($_SESSION['errors'])): ?>
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center shrink-0 mt-0.5">
                <i class="fas fa-times text-white text-sm"></i>
            </div>
            <div>
                <h3 class="font-bold text-red-700 text-sm">Error</h3>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
        <div class="rounded-2xl border border-green-200 bg-green-50 p-4 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center shrink-0 mt-0.5">
                <i class="fas fa-check text-white text-sm"></i>
            </div>
            <div>
                <h3 class="font-bold text-green-700 text-sm">Success</h3>
                <p class="text-green-600 text-sm mt-1"><?= htmlspecialchars($_SESSION['success']) ?></p>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 sm:px-8 pt-6 sm:pt-8 pb-2">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Change Password</h1>
                        <p class="text-sm text-slate-500">Update your account password securely.</p>
                    </div>
                </div>
            </div>

            <div class="px-6 sm:px-8 pb-6 sm:pb-8 pt-4">
                <form action="index.php?page=update-password" method="POST" class="space-y-5">
                    <input type="hidden" name="_redirect" value="student-change-password">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Current Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="password" name="current_password" required placeholder="Enter current password" class="w-full pl-11 pr-11 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-900 placeholder-slate-400 shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                            <button type="button" onclick="togglePassword('currentPassword',this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">New Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="password" name="new_password" required placeholder="Enter new password" class="w-full pl-11 pr-11 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-900 placeholder-slate-400 shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                            <button type="button" onclick="togglePassword('newPassword',this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm New Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="password" name="confirm_password" required placeholder="Confirm new password" class="w-full pl-11 pr-11 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-900 placeholder-slate-400 shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                            <button type="button" onclick="togglePassword('confirmPassword',this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Password Requirements</p>
                        <ul class="space-y-1.5 text-xs text-slate-500">
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-[4px] text-slate-400"></i>Minimum 8 characters</li>
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-[4px] text-slate-400"></i>At least one uppercase letter</li>
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-[4px] text-slate-400"></i>At least one lowercase letter</li>
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-[4px] text-slate-400"></i>At least one number</li>
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-[4px] text-slate-400"></i>At least one special character</li>
                        </ul>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <a href="<?= BASE_URL ?>/index.php?page=profile" class="flex-1 flex items-center justify-center gap-2 h-11 px-4 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold text-sm shadow-sm hover:bg-slate-50 transition">Cancel</a>
                        <button type="submit" class="flex-1 flex items-center justify-center gap-2 h-11 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-500 text-white font-semibold text-sm shadow-sm hover:from-indigo-700 hover:to-indigo-600 transition">
                            <i class="fas fa-key text-xs opacity-80"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
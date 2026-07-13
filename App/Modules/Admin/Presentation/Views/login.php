<?php
$error = $error ?? ($_SESSION['error'] ?? '');
unset($_SESSION['error']);
?>
<style>
    .bg-grid { background-image: radial-gradient(circle at 1px 1px, rgba(99,102,241,0.08) 1px, transparent 0); background-size: 40px 40px; }
    .shape-blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.3; pointer-events: none; }
</style>

<div class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-[#EEF2FF] via-white to-[#F5F3FF] p-4 sm:p-6 overflow-hidden">
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="shape-blob w-[400px] h-[400px] bg-[#6366F1] -top-20 -left-20"></div>
        <div class="shape-blob w-[350px] h-[350px] bg-[#8B5CF6] bottom-10 -right-10"></div>
        <div class="shape-blob w-[250px] h-[250px] bg-[#A78BFA] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>
    </div>
    <div class="fixed inset-0 bg-grid pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-[420px] my-8">
        <div class="rounded-[20px] bg-white shadow-[0_8px_40px_rgba(99,102,241,0.10)] border border-[#E5E7EB] p-8 sm:p-10">
            <div class="text-center mb-7">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-lg shadow-indigo-200 mb-4">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900">Admin Portal</h1>
                <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">Welcome back. Sign in to manage students, careers, assessments, and recommendations.</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-semibold text-red-700 flex items-center gap-2.5">
                <i class="fas fa-circle-exclamation text-sm"></i>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-login" class="space-y-4">
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="fas fa-envelope"></i></span>
                        <input type="email" id="email" name="email" required placeholder="Enter your email"
                            class="w-full rounded-xl border border-[#E5E7EB] bg-white pl-10 pr-4 py-2.5 text-sm text-slate-900 outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="fas fa-lock"></i></span>
                        <input type="password" id="password" name="password" required placeholder="Enter your password"
                            class="w-full rounded-xl border border-[#E5E7EB] bg-white pl-10 pr-4 py-2.5 text-sm text-slate-900 outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-[#5B5CEB] accent-[#5B5CEB] outline-none">
                        <span class="text-xs font-medium text-slate-600">Remember me</span>
                    </label>
                    <a href="#" class="text-xs font-semibold text-[#5B5CEB] hover:text-[#4a4bd6] no-underline transition-colors duration-200">Forgot Password?</a>
                </div>

                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-[#5B5CEB] to-[#6366F1] px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-indigo-200 transition-all duration-200 hover:from-[#4a4bd6] hover:to-[#5B5CEB] hover:shadow-lg hover:shadow-indigo-200 active:scale-[0.98]">
                    <i class="fas fa-arrow-right-to-bracket mr-2"></i>
                    Sign In
                </button>
            </form>

            <div class="mt-7 pt-5 border-t border-slate-100 text-center">
                <div class="inline-flex items-center gap-2 text-xs text-slate-400">
                    <i class="fas fa-lock text-slate-300"></i>
                    <span>Authorized Personnel Only</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$pageTitle = $pageTitle ?? 'Reset Password';
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
$pendingEmail = $_SESSION['pending_password_reset_email'] ?? '';
unset($_SESSION['errors'], $_SESSION['old']);
?>

<style>
    @keyframes slideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    .reset-page-bg {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        background: #f8f9fc;
    }
    .reset-page-bg::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 350px;
        height: 350px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .reset-page-bg::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -80px;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(139,92,246,0.1) 0%, transparent 70%);
        pointer-events: none;
    }
    .reset-card {
        animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        position: relative;
        z-index: 1;
    }
    .input-field {
        width: 100%;
        height: 48px;
        padding: 0 16px 0 48px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: #ffffff;
        font-size: 15px;
        color: #0f172a;
        outline: none;
        transition: all 0.15s ease;
    }
    .input-field:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
    }
    .input-field::placeholder { color: #94a3b8; }
    .input-field.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
    }
    .toggle-btn {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s ease;
    }
    .toggle-btn:hover { color: #6366f1; }
    .toggle-btn svg { width: 20px; height: 20px; }
    .error-banner {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 10px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        font-size: 14px;
        font-weight: 500;
    }
    .success-banner {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 10px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 14px;
        font-weight: 500;
    }
    .password-req {
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
    }
    .password-req.met { color: #16a34a; }
    .password-req svg { width: 14px; height: 14px; flex-shrink: 0; }
</style>

<div class="reset-page-bg">
    <div class="reset-card w-full max-w-md mx-4 bg-white rounded-2xl border border-slate-200/80 shadow-[0_6px_30px_rgba(0,0,0,0.05)] p-6 sm:p-8">

      

        <!-- Lock Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#15479A] to-blue-700 flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
            </div>
        </div>

        <!-- Heading -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Reset Password</h1>
            <p class="text-slate-500 mt-1.5 text-sm">Create a new password for your account</p>
        </div>

        <!-- Email Badge -->
        <div class="mb-6">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1.5">Registered Email</p>
            <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-sm font-medium text-slate-800">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <?= htmlspecialchars($pendingEmail) ?>
            </div>
        </div>

        <!-- Error Messages -->
        <?php if (isset($errors['password'])): ?>
            <div class="error-banner mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                <?= htmlspecialchars($errors['password']) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errors['confirm_password'])): ?>
            <div class="error-banner mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                <?= htmlspecialchars($errors['confirm_password']) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errors['general'])): ?>
            <div class="error-banner mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                <?= htmlspecialchars($errors['general']) ?>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-banner mb-4" role="alert">
                <i class="bi bi-check-circle-fill text-green-500"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Form -->
        <form action="<?= BASE_URL ?>/index.php?page=reset-password" method="post" id="resetForm" novalidate>

            <!-- New Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">New Password</label>
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    <input type="password" name="password" id="password" required
                           placeholder="Enter new password"
                           class="input-field"
                           value="<?= htmlspecialchars($old['password'] ?? '') ?>"
                           autocomplete="new-password"
                           <?= isset($errors['password']) ? 'aria-invalid="true" aria-describedby="password-error"' : '' ?> />
                    <button type="button" class="toggle-btn" id="togglePassword" aria-label="Toggle password visibility">
                        <svg id="eyeOpen1" class="block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eyeClosed1" class="hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0012 12c4.478 0 8.268-2.943 9.542-7-1.274 4.057-5.064 7-9.542 7a10.477 10.477 0 01-1.56-3.777M12 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18"/>
                        </svg>
                    </button>
                </div>
               
              
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="confirm_password" class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm New Password</label>
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    <input type="password" name="confirm_password" id="confirm_password" required
                           placeholder="Confirm new password"
                           class="input-field"
                           value="<?= htmlspecialchars($old['confirm_password'] ?? '') ?>"
                           autocomplete="new-password"
                           <?= isset($errors['confirm_password']) ? 'aria-invalid="true" aria-describedby="confirm-error"' : '' ?> />
                    <button type="button" class="toggle-btn" id="toggleConfirm" aria-label="Toggle password visibility">
                        <svg id="eyeOpen2" class="block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eyeClosed2" class="hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0012 12c4.478 0 8.268-2.943 9.542-7-1.274 4.057-5.064 7-9.542 7a10.477 10.477 0 01-1.56-3.777M12 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Reset Button -->
            <button type="submit" class="w-full h-11 rounded-lg bg-gradient-to-r from-[#15479A] to-blue-700 text-white font-semibold shadow hover:shadow-lg hover:scale-[1.01] transition duration-200 flex items-center justify-center gap-2" id="resetBtn">
                <span id="resetBtnText">Reset Password</span>
                <svg id="resetSpinner" class="hidden animate-spin w-4.5 h-4.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            </button>
        </form>

        <!-- Back to Login Link -->
        <p class="text-center text-sm text-slate-500 mt-5">
            <a href="<?= BASE_URL ?>/index.php?page=login" class="font-medium text-[#15479A] hover:text-blue-700 hover:underline transition-colors duration-150">
                Back to Login
            </a>
        </p>
    </div>
</div>

<script>
(function() {
    'use strict';

    // Password toggle for new password
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen1 = document.getElementById('eyeOpen1');
    const eyeClosed1 = document.getElementById('eyeClosed1');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeOpen1.classList.toggle('hidden', isPassword);
            eyeOpen1.classList.toggle('block', !isPassword);
            eyeClosed1.classList.toggle('hidden', !isPassword);
            eyeClosed1.classList.toggle('block', isPassword);
        });
    }

    // Password toggle for confirm password
    const toggleConfirm = document.getElementById('toggleConfirm');
    const confirmInput = document.getElementById('confirm_password');
    const eyeOpen2 = document.getElementById('eyeOpen2');
    const eyeClosed2 = document.getElementById('eyeClosed2');

    if (toggleConfirm && confirmInput) {
        toggleConfirm.addEventListener('click', function() {
            const isPassword = confirmInput.type === 'password';
            confirmInput.type = isPassword ? 'text' : 'password';
            eyeOpen2.classList.toggle('hidden', isPassword);
            eyeOpen2.classList.toggle('block', !isPassword);
            eyeClosed2.classList.toggle('hidden', !isPassword);
            eyeClosed2.classList.toggle('block', isPassword);
        });
    }

    // Password requirements validation
    const reqLength = document.querySelector('.req-length');
    const reqUppercase = document.querySelector('.req-uppercase');
    const reqNumber = document.querySelector('.req-number');

    function updatePasswordRequirements(value) {
        const hasLength = value.length >= 8;
        const hasUppercase = /[A-Z]/.test(value);
        const hasNumber = /\d/.test(value);

        updateReq(reqLength, hasLength);
        updateReq(reqUppercase, hasUppercase);
        updateReq(reqNumber, hasNumber);
    }

    function updateReq(el, met) {
        if (!el) return;
        const svg = el.querySelector('svg');
        const span = el.querySelector('span');
        if (met) {
            el.classList.add('met');
            if (svg) { svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>'; svg.classList.remove('text-red-500'); svg.classList.add('text-green-500'); }
        } else {
            el.classList.remove('met');
            if (svg) { svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>'; svg.classList.remove('text-green-500'); svg.classList.add('text-red-500'); }
        }
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            updatePasswordRequirements(this.value);
        });
    }

    // Form submission loading state
    const resetForm = document.getElementById('resetForm');
    const resetBtn = document.getElementById('resetBtn');
    const resetBtnText = document.getElementById('resetBtnText');
    const resetSpinner = document.getElementById('resetSpinner');

    if (resetForm && resetBtn) {
        resetForm.addEventListener('submit', function(e) {
            const pwd = passwordInput?.value || '';
            const confirm = confirmInput?.value || '';

            if (pwd.length < 8 || !/[A-Z]/.test(pwd) || !/\d/.test(pwd)) {
                // Let browser handle required validation, but prevent submit if requirements not met
                if (pwd && (pwd.length < 8 || !/[A-Z]/.test(pwd) || !/\d/.test(pwd))) {
                    e.preventDefault();
                    passwordInput.focus();
                    return;
                }
            }

            if (pwd !== confirm) {
                e.preventDefault();
                confirmInput.focus();
                return;
            }

            resetBtn.disabled = true;
            resetBtnText.textContent = 'Resetting...';
            resetSpinner.classList.remove('hidden');
        });
    }
})();
</script>
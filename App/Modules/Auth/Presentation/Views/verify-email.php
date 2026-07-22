<?php
$pageTitle = $pageTitle ?? 'Verify Email';
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
$pendingEmail = $_SESSION['pending_verification_email'] ?? '';
$pendingUserId = $_SESSION['pending_verification_user_id'] ?? 0;
unset($_SESSION['errors'], $_SESSION['old']);

$displayEmail = $old['email'] ?? $pendingEmail ?? '';
$emailParts = explode('@', $displayEmail);
$maskedEmail = (count($emailParts) === 2 && strlen($emailParts[0]) > 2)
    ? substr($emailParts[0], 0, 2) . str_repeat('*', max(0, strlen($emailParts[0]) - 2)) . '@' . $emailParts[1]
    : $displayEmail;
?>

<style>
    @keyframes slideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes shake { 0%, 100% { transform: translateX(0); } 20%, 60% { transform: translateX(-4px); } 40%, 80% { transform: translateX(4px); } }
    .otp-page-bg {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        background: #f8f9fc;
    }
    .otp-page-bg::before {
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
    .otp-page-bg::after {
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
    .otp-card {
        animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        position: relative;
        z-index: 1;
    }
    .otp-input-box {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: #ffffff;
        font-size: 24px;
        font-weight: 700;
        text-align: center;
        color: #0f172a;
        outline: none;
        transition: all 0.15s ease;
        caret-color: transparent;
    }
    .otp-input-box:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        transform: scale(1.03);
    }
    .otp-input-box.filled {
        border-color: #6366f1;
        background: #f5f3ff;
    }
    .otp-input-box.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
        animation: shake 0.35s ease;
    }
    .otp-input-box::placeholder { color: #cbd5e1; font-size: 24px; }
    .otp-input-box::-webkit-outer-spin-button,
    .otp-input-box::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .timer-display { font-variant-numeric: tabular-nums; }
    .input-field {
        width: 100%;
        height: 48px;
        padding: 0 16px;
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
</style>

<div class="otp-page-bg">
    <div class="otp-card w-full max-w-[420px] mx-4 bg-white rounded-2xl border border-slate-200/80 shadow-[0_6px_30px_rgba(0,0,0,0.05)] p-6 sm:p-8">

        <!-- Back Button + Header -->
        <div class="relative flex items-center justify-center mb-5">
            <a href="<?= BASE_URL ?>/index.php?page=login"
               class="absolute left-0 inline-flex items-center gap-1.5 text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors duration-150"
               aria-label="Back to login">
                <i class="bi bi-arrow-left text-base"></i>
                <span class="hidden sm:inline">Back</span>
            </a>
            <span class="text-sm font-semibold text-slate-500 tracking-wide uppercase">Verification</span>
        </div>

        <!-- Heading -->
        <div class="text-center mb-4">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Verify Your Email</h1>
        </div>

        <!-- Error Messages -->
        <?php if (isset($errors['verification'])): ?>
            <div class="mb-4 flex items-center gap-2.5 rounded-lg bg-red-50 border border-red-200 px-3 py-2.5 text-sm text-red-700 font-medium" role="alert" id="otp-error-msg">
                <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                <?= htmlspecialchars($errors['verification']) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errors['resend'])): ?>
            <div class="mb-4 flex items-center gap-2.5 rounded-lg bg-red-50 border border-red-200 px-3 py-2.5 text-sm text-red-700 font-medium" role="alert">
                <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                <?= htmlspecialchars($errors['resend']) ?>
            </div>
        <?php endif; ?>

        <!-- OTP Form -->
        <form action="<?= BASE_URL ?>/index.php?page=verify-email" method="post" id="otpForm" novalidate>
            <input type="hidden" name="user_id" value="<?= (int)$pendingUserId ?>" />
            <input type="hidden" name="email" value="<?= htmlspecialchars($displayEmail) ?>" />
            <input type="hidden" name="code" id="otpHiddenInput" value="" />

            <!-- Email Info -->
            <div class="text-center mb-4">
                <p class="text-sm text-slate-500">We sent a code to</p>
                <p class="text-sm font-semibold text-slate-800"><?= htmlspecialchars($maskedEmail) ?></p>
            </div>

            <!-- OTP Inputs -->
            <div class="flex justify-center gap-2.5 sm:gap-3 mb-4" role="group" aria-label="Verification code inputs">
                <input type="text" inputmode="numeric" maxlength="1" class="otp-input-box" data-index="0" autocomplete="one-time-code" aria-label="Digit 1" required />
                <input type="text" inputmode="numeric" maxlength="1" class="otp-input-box" data-index="1" aria-label="Digit 2" required />
                <input type="text" inputmode="numeric" maxlength="1" class="otp-input-box" data-index="2" aria-label="Digit 3" required />
                <input type="text" inputmode="numeric" maxlength="1" class="otp-input-box" data-index="3" aria-label="Digit 4" required />
                <input type="text" inputmode="numeric" maxlength="1" class="otp-input-box" data-index="4" aria-label="Digit 5" required />
                <input type="text" inputmode="numeric" maxlength="1" class="otp-input-box" data-index="5" aria-label="Digit 6" required />
            </div>

            <!-- Timer -->
            <div class="text-center mb-4">
                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-500 timer-display">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span id="timerText">04:59</span>
                </span>
            </div>

            <!-- Verify Button -->
            <button type="submit" class="w-full h-11 rounded-lg bg-gradient-to-r from-[#15479A] to-blue-700 text-white font-semibold shadow hover:shadow-lg hover:scale-[1.01] transition duration-200 flex items-center justify-center gap-2" id="verifyBtn" disabled>
                <span id="verifyBtnText">Verify Code</span>
                <svg id="verifySpinner" class="hidden animate-spin w-4.5 h-4.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            </button>
        </form>

        <!-- Resend Code -->
        <form action="<?= BASE_URL ?>/index.php?page=resend-verification" method="post" class="mt-4" id="resendForm">
            <input type="hidden" name="email" value="<?= htmlspecialchars($displayEmail) ?>" />
            <input type="hidden" name="user_id" value="<?= (int)$pendingUserId ?>" />
            <button type="submit" class="w-full text-sm font-medium text-[#15479A] hover:text-blue-700 hover:underline transition-colors duration-150 flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Resend code
            </button>
        </form>
    </div>
</div>

<script>
(function() {
    'use strict';

    var boxes = document.querySelectorAll('.otp-input-box');
    var hiddenInput = document.getElementById('otpHiddenInput');
    var verifyBtn = document.getElementById('verifyBtn');
    var verifyForm = document.getElementById('otpForm');
    var errorMsg = document.getElementById('otp-error-msg');
    var totalSeconds = 299;
    var timerEl = document.getElementById('timerText');
    var timerInterval = null;

    function updateHiddenInput() {
        var code = '';
        boxes.forEach(function(b) { code += b.value; });
        hiddenInput.value = code;
        verifyBtn.disabled = code.length < 6;
    }

    function moveToNext(index) {
        if (index < boxes.length - 1) boxes[index + 1].focus();
    }

    function moveToPrev(index) {
        if (index > 0) boxes[index - 1].focus();
    }

    boxes.forEach(function(box, i) {
        box.addEventListener('input', function() {
            var val = this.value.replace(/[^0-9]/g, '');
            this.value = val.substring(0, 1);
            if (val) {
                this.classList.add('filled');
                moveToNext(i);
            } else {
                this.classList.remove('filled');
            }
            updateHiddenInput();
        });

        box.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace') {
                if (!this.value && i > 0) {
                    boxes[i - 1].value = '';
                    boxes[i - 1].classList.remove('filled');
                    moveToPrev(i);
                } else {
                    this.classList.remove('filled');
                }
                updateHiddenInput();
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                moveToPrev(i);
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                moveToNext(i);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (!verifyBtn.disabled) verifyForm.requestSubmit();
            }
        });

        box.addEventListener('focus', function() { this.select(); });

        box.addEventListener('paste', function(e) {
            e.preventDefault();
            var pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            if (pasted) {
                for (var j = 0; j < Math.min(pasted.length, 6); j++) {
                    boxes[j].value = pasted[j];
                    boxes[j].classList.add('filled');
                }
                updateHiddenInput();
                boxes[Math.min(pasted.length, 5)].focus();
            }
        });
    });

    if (errorMsg) {
        boxes.forEach(function(b) { b.classList.add('error'); });
        setTimeout(function() { boxes.forEach(function(b) { b.classList.remove('error'); }); }, 600);
    }

    verifyForm.addEventListener('submit', function(e) {
        if (hiddenInput.value.length < 6) { e.preventDefault(); return; }
        verifyBtn.disabled = true;
        document.getElementById('verifyBtnText').textContent = 'Verifying...';
        document.getElementById('verifySpinner').classList.remove('hidden');
    });

    function startTimer() {
        timerInterval = setInterval(function() {
            totalSeconds--;
            if (totalSeconds <= 0) {
                clearInterval(timerInterval);
                timerEl.textContent = '00:00';
                return;
            }
            var m = Math.floor(totalSeconds / 60);
            var s = totalSeconds % 60;
            timerEl.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        }, 1000);
    }

    startTimer();
    if (boxes.length > 0) boxes[0].focus();
})();
</script>
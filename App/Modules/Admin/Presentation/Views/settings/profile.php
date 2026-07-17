<?php
$admin = $admin ?? [];
$pageTitle = $pageTitle ?? 'My Profile';
$activeMenu = $activeMenu ?? 'profile';

$adminId = (int)($admin['id'] ?? 0);
$adminName = trim((string)($admin['full_name'] ?? $admin['username'] ?? 'Admin'));
$adminEmail = trim((string)($admin['email'] ?? ''));
$adminRole = trim((string)($admin['role_name'] ?? $admin['role'] ?? 'Administrator'));

$profile = $profile ?? [];
$pName = trim((string)($profile['username'] ?? $adminName));
$pEmail = trim((string)($profile['email'] ?? $adminEmail));
$pPhone = trim((string)($profile['phone'] ?? ''));
$pAddress = trim((string)($profile['address'] ?? ''));
$pBio = trim((string)($profile['bio'] ?? ''));
$pImage = trim((string)($profile['profile_image'] ?? ''));

$errors = $errors ?? [];
$success = $success ?? null;

$hasImage = $pImage !== '';
$imageUrl = '';
if ($hasImage) {
    $imgPath = BASE_PATH . '/public/uploads/profile/' . $pImage;
    if (file_exists($imgPath)) {
        $imageUrl = BASE_URL . '/uploads/profile/' . rawurlencode($pImage);
    }
}

$flashMessage = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

ob_start();
?>
<style>
    :root {
        --primary: #5B5FEF;
        --primary-light: #EEF0FF;
        --primary-dark: #4A4ED9;
        --bg-body: #F8FAFC;
        --card-radius: 20px;
    }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .anim-card { animation: fadeSlideUp 0.45s cubic-bezier(0.16,1,0.3,1) both; }
    .anim-delay-1 { animation-delay: 0.05s; }
    .anim-delay-2 { animation-delay: 0.10s; }
    .anim-delay-3 { animation-delay: 0.15s; }
    .anim-delay-4 { animation-delay: 0.20s; }
    .anim-delay-5 { animation-delay: 0.25s; }

    body { background: var(--bg-body); }

    .profile-header {
        background: linear-gradient(135deg, #5B5FEF 0%, #7C5CFC 50%, #A78BFA 100%);
        border-radius: var(--card-radius);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 10%;
        width: 250px;
        height: 250px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }

    .profile-avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.5);
        flex-shrink: 0;
        overflow: hidden;
        position: relative;
    }

    .profile-card {
        background: #fff;
        border-radius: var(--card-radius);
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(15,23,42,0.04);
        overflow: hidden;
    }
    .profile-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .form-input {
        width: 100%;
        padding: 0.65rem 0.9rem;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.875rem;
        color: #334155;
        background: #fff;
        transition: all 0.2s ease;
        outline: none;
    }
    .form-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(91,95,239,0.1);
    }
    .form-input.is-invalid { border-color: #ef4444; }
    .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }
    .form-input::placeholder { color: #94a3b8; }
    .form-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.4rem;
    }
    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .btn-profile {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
        text-decoration: none;
    }
    .btn-profile.primary {
        background: var(--primary);
        color: #fff;
    }
    .btn-profile.primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px -4px rgba(91,95,239,0.4); }
    .btn-profile.primary:active { transform: translateY(0); }
    .btn-profile.primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
    .btn-profile.secondary {
        background: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .btn-profile.secondary:hover { background: #f8fafc; border-color: #cbd5e1; }
    .btn-profile.danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .btn-profile.danger:hover { background: #fee2e2; }

    .btn-header-action {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
        text-decoration: none;
        background: rgba(255,255,255,0.2);
        color: #fff;
        backdrop-filter: blur(4px);
    }
    .btn-header-action:hover {
        background: rgba(255,255,255,0.35);
        transform: translateY(-1px);
    }
    .btn-header-action:active { transform: translateY(0); }

    .avatar-upload-area {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 3px dashed #e2e8f0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        overflow: hidden;
        position: relative;
    }
    .avatar-upload-area:hover { border-color: var(--primary); background: var(--primary-light); }
    .avatar-upload-area img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
    }
    .avatar-upload-area input { display: none; }

    .security-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 0;
    }
    .security-item:not(:last-child) { border-bottom: 1px solid #f1f5f9; }
    .security-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        background: #e2e8f0;
        border-radius: 9999px;
        cursor: pointer;
        transition: background 0.2s ease;
        flex-shrink: 0;
    }
    .toggle-switch.active { background: var(--primary); }
    .toggle-switch::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
        transition: transform 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }
    .toggle-switch.active::after { transform: translateX(20px); }

    .device-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 0;
    }
    .device-item:not(:last-child) { border-bottom: 1px solid #f1f5f9; }

    @media (max-width: 640px) {
        .profile-header { padding: 1.5rem; }
        .profile-header-inner { flex-direction: column; text-align: center; }
        .profile-header-actions { flex-wrap: wrap; justify-content: center; }
        .profile-card-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
    }
</style>

<div class="max-w-[960px] mx-auto">

    <!-- ============================================================ -->
    <!-- PROFILE HEADER                                                -->
    <!-- ============================================================ -->
    <div class="profile-header anim-card anim-delay-1">
        <div class="profile-header-inner flex items-start gap-5 relative z-10">
            <div class="profile-avatar">
                <div id="avatarPreview" class="w-full h-full rounded-full bg-white/20 flex items-center justify-center text-white text-3xl font-bold overflow-hidden relative">
                    <span id="avatarInitial" class="<?= $hasImage ? 'hidden' : '' ?>"><?= htmlspecialchars(strtoupper(substr($pName, 0, 1))) ?></span>
                    <img id="avatarImg" src="<?= $imageUrl ?>" alt="" class="<?= $hasImage ? '' : 'hidden' ?> w-full h-full object-cover absolute inset-0">
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-white"><?= htmlspecialchars($pName) ?></h1>
                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                    <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white">
                        <i class="bi bi-shield-check text-[10px]"></i>
                        <?= htmlspecialchars($adminRole) ?>
                    </span>
                    <span class="text-sm text-white/70 flex items-center gap-1.5">
                        <i class="bi bi-envelope"></i>
                        <?= htmlspecialchars($pEmail) ?>
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-2 mt-4 profile-header-actions">
                    <button type="button" class="btn-header-action" id="uploadBtn">
                        <i class="bi bi-camera"></i> Upload Photo
                    </button>
                    <a href="#personal-info" class="btn-header-action" onclick="document.getElementById('field_name').focus(); return false;">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </a>
                    <a href="#security-card" class="btn-header-action" onclick="document.getElementById('field_current_password').focus(); return false;">
                        <i class="bi bi-lock"></i> Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MESSAGES                                                      -->
    <!-- ============================================================ -->

    <?php if ($success !== null): ?>
    <div class="mt-6 anim-card anim-delay-1 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-medium text-emerald-800">
        <i class="bi bi-check-circle-fill text-emerald-500"></i>
        <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <?php if ($flashMessage !== null): ?>
    <div class="mt-6 anim-card anim-delay-1 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-medium text-emerald-800">
        <i class="bi bi-check-circle-fill text-emerald-500"></i>
        <?= htmlspecialchars($flashMessage['text'] ?? '') ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
    <div class="mt-6 anim-card anim-delay-1 rounded-xl border border-red-200 bg-red-50 px-5 py-3">
        <ul class="space-y-1">
            <?php foreach ($errors as $err): ?>
            <li class="flex items-start gap-2 text-sm text-red-700">
                <i class="bi bi-exclamation-circle-fill text-red-400 mt-0.5"></i>
                <span><?= htmlspecialchars($err) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- ============================================================ -->
    <!-- FORM                                                          -->
    <!-- ============================================================ -->

    <form id="profileForm" action="<?= BASE_URL ?>/index.php?page=admin-profile" method="POST" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="remove_image" id="removeImageInput" value="">

    <div class="mt-6 space-y-6">

        <!-- ============================================================ -->
        <!-- PERSONAL INFORMATION                                          -->
        <!-- ============================================================ -->
        <div id="personal-info" class="profile-card anim-card anim-delay-2">
            <div class="profile-card-header">
                <h3 class="text-sm font-bold text-slate-800">Personal Information</h3>
            </div>
            <div class="p-5 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label" for="field_name">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" id="field_name" name="username" value="<?= htmlspecialchars($pName) ?>" placeholder="Full name" required>
                    </div>
                    <div>
                        <label class="form-label" for="field_email">Email <span class="text-red-500">*</span></label>
                        <input type="email" class="form-input" id="field_email" name="email" value="<?= htmlspecialchars($pEmail) ?>" placeholder="Email address" required>
                    </div>
                    <div>
                        <label class="form-label" for="field_phone">Phone Number</label>
                        <input type="text" class="form-input" id="field_phone" name="phone" value="<?= htmlspecialchars($pPhone) ?>" placeholder="+1 (555) 000-0000">
                    </div>
                    <div>
                        <label class="form-label" for="field_address">Address</label>
                        <input type="text" class="form-input" id="field_address" name="address" value="<?= htmlspecialchars($pAddress) ?>" placeholder="Street, City, Country">
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- ABOUT / BIO                                                  -->
        <!-- ============================================================ -->
        <div class="profile-card anim-card anim-delay-3">
            <div class="profile-card-header">
                <h3 class="text-sm font-bold text-slate-800">About / Bio</h3>
            </div>
            <div class="p-5 sm:p-6">
                <textarea class="form-input form-textarea" id="field_bio" name="bio" placeholder="Write a short bio about yourself..."><?= htmlspecialchars($pBio) ?></textarea>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- SECURITY - CHANGE PASSWORD                                   -->
        <!-- ============================================================ -->
        <div id="security-card" class="profile-card anim-card anim-delay-4">
            <div class="profile-card-header">
                <h3 class="text-sm font-bold text-slate-800">Security</h3>
            </div>
            <div class="p-5 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="form-label" for="field_current_password">Current Password</label>
                        <input type="password" class="form-input" id="field_current_password" name="current_password" placeholder="Enter current password">
                    </div>
                    <div>
                        <label class="form-label" for="field_new_password">New Password</label>
                        <input type="password" class="form-input" id="field_new_password" name="new_password" placeholder="Enter new password">
                    </div>
                    <div>
                        <label class="form-label" for="field_confirm_password">Confirm Password</label>
                        <input type="password" class="form-input" id="field_confirm_password" name="confirm_password" placeholder="Confirm new password">
                    </div>
                </div>

                <hr class="my-5 border-slate-100">

                <div class="security-item">
                    <div class="security-icon bg-indigo-50 text-indigo-600">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-700">Last Login</p>
                        <p class="text-xs text-slate-400 mt-0.5">Today at <?= date('h:i A') ?></p>
                    </div>
                </div>

                <div class="security-item">
                    <div class="security-icon bg-amber-50 text-amber-600">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-700">Recent Devices</p>
                        <div class="mt-2 space-y-1">
                            <div class="device-item">
                                <i class="bi bi-window text-slate-400 text-sm"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-slate-600 truncate">Chrome on Windows</p>
                                    <p class="text-xs text-slate-400">Current session</p>
                                </div>
                                <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="security-item">
                    <div class="security-icon bg-purple-50 text-purple-600">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-700">Two-Factor Authentication</p>
                        <p class="text-xs text-slate-400 mt-0.5">Add an extra layer of security to your account</p>
                    </div>
                    <div class="toggle-switch" id="twoFactorToggle"></div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- SAVE / CANCEL                                                -->
        <!-- ============================================================ -->
        <div class="flex items-center gap-3 anim-card anim-delay-5">
            <button type="submit" class="btn-profile primary" id="saveProfileBtn">
                <i class="bi bi-check-lg"></i> Save Changes
            </button>
            <button type="button" class="btn-profile secondary" onclick="window.history.back()">
                Cancel
            </button>
        </div>

    </div>

    </form>

    <!-- Hidden upload area (used for photo upload via header button) -->
    <div class="hidden">
        <div class="avatar-upload-area" id="avatarUploadArea">
            <span id="uploadPlaceholder" class="flex flex-col items-center text-slate-400">
                <i class="bi bi-camera text-2xl"></i>
                <span class="text-xs mt-1">Upload</span>
            </span>
            <img id="uploadPreview" src="" alt="" class="hidden w-full h-full object-cover absolute inset-0">
            <input type="file" id="avatarInput" name="profile_image" accept=".jpg,.jpeg,.png,.webp">
            <button type="button" id="removeAvatarBtn">Remove</button>
        </div>
    </div>

</div>

<script>
(function() {
    'use strict';

    var avatarInput = document.getElementById('avatarInput');
    var uploadPreview = document.getElementById('uploadPreview');
    var uploadPlaceholder = document.getElementById('uploadPlaceholder');
    var avatarInitial = document.getElementById('avatarInitial');
    var avatarImg = document.getElementById('avatarImg');
    var removeImageInput = document.getElementById('removeImageInput');
    var removeBtn = document.getElementById('removeAvatarBtn');

    // ---- Avatar Upload ----
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(ev) {
                if (uploadPreview) {
                    uploadPreview.src = ev.target.result;
                    uploadPreview.classList.remove('hidden');
                    if (uploadPlaceholder) uploadPlaceholder.classList.add('hidden');
                }
                if (avatarInitial) avatarInitial.classList.add('hidden');
                if (avatarImg) {
                    avatarImg.src = ev.target.result;
                    avatarImg.classList.remove('hidden');
                }
            };
            reader.readAsDataURL(file);
            if (removeImageInput) removeImageInput.value = '';
        });
    }

    // ---- Remove Avatar ----
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            if (uploadPreview) { uploadPreview.src = ''; uploadPreview.classList.add('hidden'); }
            if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
            if (avatarInput) avatarInput.value = '';
            if (avatarInitial) avatarInitial.classList.remove('hidden');
            if (avatarImg) { avatarImg.src = ''; avatarImg.classList.add('hidden'); }
            if (removeImageInput) removeImageInput.value = '1';
        });
    }

    // ---- Upload Button ----
    var uploadBtn = document.getElementById('uploadBtn');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
            if (avatarInput) avatarInput.click();
        });
    }

    // ---- Two-Factor Toggle ----
    var toggle = document.getElementById('twoFactorToggle');
    if (toggle) {
        toggle.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    }

})();
</script>

<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@media print {
    .btn-profile, #avatarUploadArea { display: none !important; }
    .profile-card { break-inside: avoid; border: 1px solid #e2e8f0 !important; }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
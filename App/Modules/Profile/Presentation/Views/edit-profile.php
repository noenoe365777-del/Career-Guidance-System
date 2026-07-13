<?php
$userData = $_SESSION['user'] ?? [];
$profileEmail = htmlspecialchars($profile['email'] ?? $userData['email'] ?? '');
$profileName = htmlspecialchars($profile['username'] ?? $userData['username'] ?? '');
$profilePhone = htmlspecialchars($profile['phone'] ?? '');
$profileAddress = htmlspecialchars($profile['address'] ?? '');
$profileGender = $profile['gender'] ?? '';
$profileEducation = $profile['education_level'] ?? '';
$profileDob = $profile['date_of_birth'] ?? '';
$profileImageRaw = $profile['profile_image'] ?? ($userData['profile_image'] ?? '');
$memberSince = $userData['created_at'] ?? '';
$firstLetter = $profileName !== '' ? mb_strtoupper(mb_substr($profileName, 0, 1)) : 'S';

$profileImageUrl = '';
if ($profileImageRaw !== '') {
    $newPath = BASE_PATH . '/public/uploads/profile/' . $profileImageRaw;
    $legacyPath = BASE_PATH . '/Public/assets/images/' . $profileImageRaw;
    if (file_exists($newPath)) {
        $profileImageUrl = BASE_URL . '/uploads/profile/' . rawurlencode($profileImageRaw);
    } elseif (file_exists($legacyPath)) {
        $profileImageUrl = BASE_URL . '/assets/images/' . rawurlencode($profileImageRaw);
    }
}

$hasImage = $profileImageUrl !== '';

$errors = $_SESSION['errors'] ?? [];
$successMsg = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>
<div class="mx-auto w-full max-w-4xl px-4 py-6 sm:px-6 sm:py-8">

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
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Edit Profile</h1>
            <p class="mt-0.5 text-sm text-slate-500">Update your personal information.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/index.php?page=profile" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 no-underline transition-all duration-200 hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit" form="editProfileForm" id="saveBtn" class="inline-flex items-center gap-2 rounded-xl bg-[#5B5CEB] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#4a4bd6] disabled:cursor-not-allowed disabled:opacity-50">
                <span id="saveBtnText">Save Changes</span>
                <span id="saveBtnSpinner" class="hidden"><i class="fas fa-circle-notch fa-spin"></i></span>
            </button>
        </div>
    </div>

    <!-- Profile Card -->
    <section class="mb-6 rounded-[20px] border border-[#E5E7EB] bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start sm:gap-8">
            <!-- Avatar -->
            <div class="relative shrink-0">
                <div class="h-[120px] w-[120px] overflow-hidden rounded-full border-4 border-slate-100 shadow-sm">
                    <?php if ($hasImage): ?>
                        <img src="<?= $profileImageUrl ?>" alt="" class="h-full w-full object-cover" id="profilePreview">
                    <?php else: ?>
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-600 text-white" id="profilePreview">
                            <span class="text-4xl font-bold"><?= $firstLetter ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <label for="photoInput" class="absolute -bottom-1 -right-1 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full border-2 border-white bg-[#5B5CEB] text-white shadow-sm transition-colors duration-200 hover:bg-[#4a4bd6]">
                    <i class="fas fa-camera text-xs"></i>
                </label>
                <form id="photoUploadForm" action="<?= BASE_URL ?>/index.php?page=update-profile-image&redirect=edit-profile" method="post" enctype="multipart/form-data" class="hidden">
                    <input type="file" id="photoInput" name="profile_image" accept=".jpg,.jpeg,.png,.webp">
                </form>
            </div>

            <!-- Info -->
            <div class="flex min-w-0 flex-1 flex-col items-center text-center sm:items-start sm:text-left">
                <h2 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($profileName) ?></h2>
                <p class="mt-0.5 text-sm text-slate-500"><?= htmlspecialchars($profileEmail) ?></p>
                <?php if ($profileEducation): ?>
                <span class="mt-2 inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-[11px] font-semibold text-indigo-700"><?= htmlspecialchars($profileEducation) ?></span>
                <?php endif; ?>
                <?php if ($memberSince): ?>
                <p class="mt-2 text-xs text-slate-400">Member since <?= date('F Y', strtotime($memberSince)) ?></p>
                <?php endif; ?>
                <div class="mt-4 flex items-center gap-3">
                    <label for="photoInput" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-[#E5E7EB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 no-underline transition-all duration-200 hover:bg-slate-50">
                        <i class="fas fa-camera text-xs"></i>
                        Upload Photo
                    </label>
                    <?php if ($hasImage): ?>
                    <button type="button" id="removePhotoBtn" class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2 text-xs font-semibold text-red-600 no-underline transition-all duration-200 hover:bg-red-50">
                        <i class="fas fa-trash-can text-xs"></i>
                        Remove Photo
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Personal Information -->
    <section class="rounded-[20px] border border-[#E5E7EB] bg-white p-6 shadow-sm sm:p-8">
        <form id="editProfileForm" action="<?= BASE_URL ?>/index.php?page=update-profile" method="POST" novalidate>
            <div class="mb-6 border-b border-slate-100 pb-4">
                <h2 class="text-base font-bold text-slate-900">Personal Information</h2>
                <p class="mt-0.5 text-sm text-slate-500">Manage your personal details and contact information.</p>
            </div>

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

            <div class="grid gap-5 sm:grid-cols-2">
                <!-- Full Name -->
                <div>
                    <label for="field_username" class="mb-1.5 block text-xs font-semibold text-slate-700">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="field_username" name="username" value="<?= htmlspecialchars($profileName) ?>" required
                        class="w-full rounded-xl border border-[#E5E7EB] px-4 py-2.5 text-sm text-slate-900 outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20">
                    <p class="mt-1 hidden text-xs text-red-500" id="error_username">Full name is required.</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">Email</label>
                    <input type="email" value="<?= htmlspecialchars($profileEmail) ?>" readonly
                        class="w-full cursor-not-allowed rounded-xl border border-[#E5E7EB] bg-slate-50 px-4 py-2.5 text-sm text-slate-500 outline-none">
                </div>

                <!-- Phone -->
                <div>
                    <label for="field_phone" class="mb-1.5 block text-xs font-semibold text-slate-700">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" id="field_phone" name="phone" value="<?= htmlspecialchars($profilePhone) ?>" required
                        class="w-full rounded-xl border border-[#E5E7EB] px-4 py-2.5 text-sm text-slate-900 outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20">
                    <p class="mt-1 hidden text-xs text-red-500" id="error_phone">Phone number is required.</p>
                </div>

                <!-- Gender -->
                <div>
                    <label for="field_gender" class="mb-1.5 block text-xs font-semibold text-slate-700">Gender</label>
                    <select id="field_gender" name="gender"
                        class="w-full rounded-xl border border-[#E5E7EB] px-4 py-2.5 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20">
                        <option value="">Select Gender</option>
                        <option value="Male" <?= $profileGender === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $profileGender === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="field_dob" class="mb-1.5 block text-xs font-semibold text-slate-700">Date of Birth</label>
                    <input type="date" id="field_dob" name="date_of_birth" value="<?= htmlspecialchars($profileDob) ?>"
                        class="w-full rounded-xl border border-[#E5E7EB] px-4 py-2.5 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20">
                </div>

                <!-- Education Level -->
                <div>
                    <label for="field_education" class="mb-1.5 block text-xs font-semibold text-slate-700">Education Level <span class="text-red-500">*</span></label>
                    <select id="field_education" name="education_level" required
                        class="w-full rounded-xl border border-[#E5E7EB] px-4 py-2.5 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20">
                        <option value="">Select Education Level</option>
                        <option value="High School" <?= $profileEducation === 'High School' ? 'selected' : '' ?>>High School</option>
                        <option value="Undergraduate" <?= $profileEducation === 'Undergraduate' ? 'selected' : '' ?>>Undergraduate</option>
                        <option value="Graduate" <?= $profileEducation === 'Graduate' ? 'selected' : '' ?>>Graduate</option>
                    </select>
                    <p class="mt-1 hidden text-xs text-red-500" id="error_education">Education level is required.</p>
                </div>

                <!-- Address (full width) -->
                <div class="sm:col-span-2">
                    <label for="field_address" class="mb-1.5 block text-xs font-semibold text-slate-700">Address</label>
                    <textarea id="field_address" name="address" rows="2"
                        class="w-full rounded-xl border border-[#E5E7EB] px-4 py-2.5 text-sm text-slate-900 outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-[#5B5CEB] focus:ring-2 focus:ring-[#5B5CEB]/20"><?= htmlspecialchars($profileAddress) ?></textarea>
                </div>
            </div>

            <!-- Mobile sticky footer -->
            <div class="mt-8 flex flex-col gap-3 border-t border-slate-100 pt-6 sm:hidden">
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#5B5CEB] px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#4a4bd6]">
                    Save Changes
                </button>
                <a href="<?= BASE_URL ?>/index.php?page=profile" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#E5E7EB] bg-white px-5 py-3 text-sm font-semibold text-slate-700 no-underline transition-all duration-200 hover:bg-slate-50">
                    Cancel
                </a>
            </div>
        </form>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editProfileForm');
    const saveBtn = document.getElementById('saveBtn');
    const saveBtnText = document.getElementById('saveBtnText');
    const saveBtnSpinner = document.getElementById('saveBtnSpinner');
    const photoInput = document.getElementById('photoInput');
    const profilePreview = document.getElementById('profilePreview');

    // Track changes for enable/disable save button
    const initialValues = {};
    const fields = form.querySelectorAll('input[name], select[name], textarea[name]');
    fields.forEach(f => { initialValues[f.name] = f.value; });

    function checkChanges() {
        let changed = false;
        fields.forEach(f => { if (f.value !== initialValues[f.name]) changed = true; });
        saveBtn.disabled = !changed;
    }
    fields.forEach(f => f.addEventListener('input', checkChanges));
    fields.forEach(f => f.addEventListener('change', checkChanges));
    checkChanges();

    // Client-side validation
    function validateField(id) {
        const el = document.getElementById(id);
        const errorEl = document.getElementById('error_' + id.replace('field_', ''));
        if (!el || !errorEl) return true;
        if (!el.value.trim()) {
            el.classList.add('border-red-400');
            errorEl.classList.remove('hidden');
            return false;
        }
        el.classList.remove('border-red-400');
        errorEl.classList.add('hidden');
        return true;
    }

    form.addEventListener('submit', function(e) {
        const validName = validateField('field_username');
        const validPhone = validateField('field_phone');
        const validEdu = validateField('field_education');
        if (!validName || !validPhone || !validEdu) {
            e.preventDefault();
            return;
        }
        saveBtn.disabled = true;
        saveBtnText.classList.add('hidden');
        saveBtnSpinner.classList.remove('hidden');
    });

    // Image upload via form submit
    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be under 2MB.');
            this.value = '';
            return;
        }
        this.closest('form').submit();
    });

    // Remove photo
    const removePhotoBtn = document.getElementById('removePhotoBtn');
    if (removePhotoBtn) {
        removePhotoBtn.addEventListener('click', function() {
            if (!confirm('Remove your profile photo?')) return;
            const fd = new FormData();
            fd.append('remove', '1');
            fetch('<?= BASE_URL ?>/index.php?page=update-profile-image&redirect=edit-profile', {
                method: 'POST',
                body: fd
            }).then(() => window.location.reload());
        });
    }

    // Real-time validation on blur
    ['field_username', 'field_phone', 'field_education'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('blur', function() { validateField(id); });
    });
});
</script>

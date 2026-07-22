<?php
$userData = $_SESSION['user'] ?? [];
$userId = (int)($userData['user_id'] ?? $userData['id'] ?? 0);

$fullName = htmlspecialchars($profile['username'] ?? $userData['username'] ?? 'Student');
$email = htmlspecialchars($profile['email'] ?? $userData['email'] ?? '');
$phone = htmlspecialchars($profile['phone'] ?? '');
$dob = $profile['date_of_birth'] ?? '';
$gender = htmlspecialchars($profile['gender'] ?? '');
$educationLevel = htmlspecialchars($profile['education_level'] ?? '');
$address = htmlspecialchars($profile['address'] ?? '');
$firstLetter = $fullName !== '' ? mb_strtoupper(mb_substr($fullName, 0, 1)) : 'S';

// Resolve profile image (check new uploads dir first, then legacy)
$profileImageRaw = $profile['profile_image'] ?? ($userData['profile_image'] ?? '');
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

// Assessment data
$assessmentSlugs = ['personality', 'interest', 'aptitude', 'values'];
$assessmentLabels = [
    'personality' => 'Personality',
    'interest'    => 'Interest',
    'aptitude'    => 'Aptitude',
    'values'      => 'Career Values',
];
$faIcons = [
    'personality' => 'fa-brain',
    'interest'    => 'fa-heart',
    'aptitude'    => 'fa-chart-line',
    'values'      => 'fa-bullseye',
];
$statusMap = [];
$completedCount = 0;
try {
    $repo = new \App\Modules\Dashboard\Infrastructure\Persistence\DashboardRepository();
    $statusMap = $repo->getAssessmentStatus($userId);
    foreach ($statusMap as $s) {
        if (strtolower($s['status'] ?? '') === 'completed') {
            $completedCount++;
        }
    }
} catch (\Throwable $e) {
    // Silently fail
}
$totalAssessments = 4;
$allCompleted = $completedCount >= $totalAssessments;

// Top recommendation
$recommendation = null;
try {
    $recommendation = $repo->getRecommendation($userId);
} catch (\Throwable $e) {
    // Silently fail
}

$careerIconMap = [
    'Software Engineer' => 'fa-code',
    'Data Analyst' => 'fa-chart-bar',
    'Graphic Designer' => 'fa-paintbrush',
    'Teacher' => 'fa-chalkboard-user',
    'Doctor' => 'fa-user-doctor',
    'Accountant' => 'fa-calculator',
    'Civil Engineer' => 'fa-helmet-safety',
    'Mechanical Engineer' => 'fa-gears',
    'Marketing Specialist' => 'fa-bullhorn',
    'Nurse' => 'fa-user-nurse',
    'Electrician' => 'fa-bolt',
    'Plumber' => 'fa-wrench',
    'Certified Nursing Assistant (CNA)' => 'fa-heart-pulse',
    'Retail Manager' => 'fa-store',
    'HVAC Technician' => 'fa-snowflake',
    'Administrative Assistant' => 'fa-file-lines',
    'Security Guard' => 'fa-shield-halved',
    'Chef / Cook' => 'fa-utensils',
];

$recCareerName = $recommendation ? htmlspecialchars($recommendation['career_name']) : '';
$recIcon = 'fa-trophy';
if ($recommendation && isset($careerIconMap[$recommendation['career_name']])) {
    $recIcon = $careerIconMap[$recommendation['career_name']];
}
?>
<div class="mx-auto w-full max-w-5xl overflow-x-hidden px-4 py-6 sm:px-6 sm:py-8 space-y-6">

    <!-- Profile Header -->
    <section class="rounded-[20px] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start sm:gap-8">
            <!-- Avatar with camera overlay -->
            <div class="relative shrink-0">
                <div id="profileAvatarContainer" class="h-[120px] w-[120px] overflow-hidden rounded-full border-4 border-slate-100 shadow-sm">
                    <?php if ($profileImageUrl): ?>
                        <img id="profileAvatarImg" src="<?= $profileImageUrl ?>" alt="" class="h-full w-full object-cover">
                    <?php else: ?>
                        <div id="profileAvatarPlaceholder" class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-600 text-white">
                            <span class="text-4xl font-bold"><?= $firstLetter ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <label for="profileImageInput" class="absolute -bottom-1 -right-1 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full border-2 border-white bg-purple-600 text-white shadow-sm transition-colors duration-200 hover:bg-purple-700">
                    <i class="fas fa-camera text-xs"></i>
                </label>
                <form id="profileImageForm" action="<?= BASE_URL ?>/index.php?page=update-profile-image" method="post" enctype="multipart/form-data" class="hidden">
                    <input type="file" id="profileImageInput" name="profile_image" accept=".jpg,.jpeg,.png,.webp">
                </form>
            </div>

            <!-- Info -->
            <div class="flex min-w-0 flex-1 flex-col items-center text-center sm:items-start sm:text-left">
                <h1 class="text-xl font-bold text-slate-900 sm:text-2xl"><?= $fullName ?></h1>
                <p class="mt-0.5 text-sm font-medium text-indigo-600"><?= $educationLevel ?: 'Student' ?></p>
                <div class="mt-3 flex flex-col items-center gap-2 sm:flex-row sm:flex-wrap sm:gap-4">
                    <?php if ($email): ?>
                    <div class="flex items-center gap-1.5 text-sm text-slate-500">
                        <i class="fas fa-envelope text-xs text-slate-400"></i>
                        <span><?= $email ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($phone): ?>
                    <div class="flex items-center gap-1.5 text-sm text-slate-500">
                        <i class="fas fa-phone text-xs text-slate-400"></i>
                        <span><?= $phone ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <label for="profileImageInput" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-purple-200 bg-purple-50 px-4 py-2 text-xs font-semibold text-purple-700 no-underline transition-all duration-200 hover:bg-purple-100">
                        <i class="fas fa-camera text-xs"></i>
                        Upload Photo
                    </label>
                    <a href="<?= BASE_URL ?>/index.php?page=edit-profile" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 no-underline transition-all duration-200 hover:bg-slate-50">
                        <i class="fas fa-pen text-xs"></i>
                        Edit Profile
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=change-password" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 no-underline transition-all duration-200 hover:bg-slate-50">
                        <i class="fas fa-lock text-xs"></i>
                        Change Password
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Personal Information + Assessment Progress side by side -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Personal Information -->
        <section class="rounded-[20px] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8 lg:col-span-2">
            <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <h2 class="text-base font-bold text-slate-900">Personal Information</h2>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Full Name</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800"><?= $fullName ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Email</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800"><?= $email ?: '—' ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Phone</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800"><?= $phone ?: '—' ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Date of Birth</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800"><?= $dob ? date('F j, Y', strtotime($dob)) : '—' ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Gender</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800"><?= $gender ?: '—' ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Education Level</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800"><?= $educationLevel ?: '—' ?></p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Location</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800"><?= $address ?: '—' ?></p>
                </div>
            </div>
        </section>

        <!-- Assessment Progress -->
        <section class="rounded-[20px] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="fas fa-chart-pie text-sm"></i>
                </div>
                <h2 class="text-base font-bold text-slate-900">Assessment Progress</h2>
            </div>
            <div class="space-y-3">
                <?php foreach ($assessmentSlugs as $slug):
                    $s = $statusMap[$slug] ?? ['status' => 'Locked', 'completed_at' => null];
                    $isComplete = strtolower($s['status'] ?? '') === 'completed';
                ?>
                <div class="flex items-center gap-3">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full <?= $isComplete ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' ?>">
                        <i class="fas <?= $isComplete ? 'fa-check' : 'fa-circle-notch' ?> text-[10px]"></i>
                    </span>
                    <span class="text-sm <?= $isComplete ? 'font-semibold text-slate-800' : 'text-slate-500' ?>"><?= $assessmentLabels[$slug] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-6 rounded-xl border border-slate-100 bg-slate-50 p-4 text-center">
                <p class="text-2xl font-bold text-slate-900"><?= round(($totalAssessments > 0 ? ($completedCount / $totalAssessments) : 0) * 100) ?>%</p>
                <p class="text-xs font-medium text-slate-500">Overall Completion</p>
                <div class="mx-auto mt-3 h-2 w-full max-w-[160px] overflow-hidden rounded-full bg-slate-200">
                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width: <?= round(($totalAssessments > 0 ? ($completedCount / $totalAssessments) : 0) * 100) ?>%"></div>
                </div>
            </div>
        </section>
    </div>

  


</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('profileImageInput');
    if (input) {
        input.addEventListener('change', function() {
            var file = this.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be under 2MB.');
                this.value = '';
                return;
            }
            var form = this.closest('form');
            var formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    var container = document.getElementById('profileAvatarContainer');
                    if (!container) return;
                    if (data.has_image) {
                        var cacheBusted = data.image_url + '?v=' + Date.now();
                        var existingImg = document.getElementById('profileAvatarImg');
                        var existingPlaceholder = document.getElementById('profileAvatarPlaceholder');
                        if (existingImg) {
                            existingImg.src = cacheBusted;
                        } else {
                            if (existingPlaceholder) existingPlaceholder.remove();
                            var img = document.createElement('img');
                            img.id = 'profileAvatarImg';
                            img.src = cacheBusted;
                            img.alt = '';
                            img.className = 'h-full w-full object-cover';
                            container.appendChild(img);
                        }
                    }
                    if (typeof window.updateNavbarAvatar === 'function') {
                        window.updateNavbarAvatar(data.has_image ? data.image_url : '');
                    }
                } else {
                    alert(data.error || 'Upload failed.');
                }
            })
            .catch(function() { alert('Upload failed.'); });
        });
    }
});
</script>

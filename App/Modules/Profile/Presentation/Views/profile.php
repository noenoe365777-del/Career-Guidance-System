<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<?php
$userData = $_SESSION['user'] ?? [];

$student = [
    'full_name' => htmlspecialchars($profile['username'] ?? $userData['username'] ?? 'Student'),
    'email' => htmlspecialchars($profile['email'] ?? $userData['email'] ?? ''),
    'phone' => htmlspecialchars($profile['phone'] ?? ''),
    'date_of_birth' => htmlspecialchars($profile['date_of_birth'] ?? ''),
    'gender' => htmlspecialchars($profile['gender'] ?? ''),
    'education_level' => htmlspecialchars($profile['education_level'] ?? ''),
    'field_of_study' => htmlspecialchars($profile['field_of_study'] ?? ''),
    'location' => htmlspecialchars($profile['address'] ?? ''),
    'member_since' => htmlspecialchars($userData['created_at'] ?? ''),
    'about_me' => htmlspecialchars($profile['about_me'] ?? ''),
    'preferred_industry' => htmlspecialchars($profile['preferred_industry'] ?? ''),
    'preferred_role' => htmlspecialchars($profile['preferred_role'] ?? ''),
    'preferred_location' => htmlspecialchars($profile['preferred_location'] ?? ''),
    'profile_image' => $profile['profile_image'] ?? '',
];
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="space-y-6">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">My Profile</h1>
                <p class="text-sm text-slate-500 mt-1">View and update your personal information.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="?page=edit-profile" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold text-sm shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
                    <i class="bi bi-pencil-square"></i>
                    Edit Profile
                </a>
                <a href="?page=student-change-password" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 text-white font-semibold text-sm shadow-sm hover:bg-slate-800 transition-all duration-200">
                    <i class="bi bi-key"></i>
                    Change Password
                </a>
            </div>
        </div>

        <!-- Personal Information Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                <!-- Left: Avatar -->
                <div class="flex items-center justify-center lg:items-start lg:justify-start p-6 sm:p-8 lg:p-10 lg:border-r border-slate-100">
                    <?php if (!empty($student['profile_image'])): ?>
                        <img src="<?= BASE_URL ?>/assets/images/<?= $student['profile_image'] ?>" alt="Profile" class="w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-slate-100 shadow-sm">
                    <?php else: ?>
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-indigo-100 border-4 border-slate-100 flex items-center justify-center shadow-sm">
                            <span class="text-4xl sm:text-5xl font-bold text-indigo-500"><?= strtoupper(substr($student['full_name'], 0, 1)) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Middle: Contact Info -->
                <div class="flex-1 p-6 sm:p-8 lg:p-10">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900"><?= $student['full_name'] ?></h2>
                    <div class="mt-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                <i class="bi bi-envelope text-sm text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Email</p>
                                <p class="text-sm font-semibold text-slate-800"><?= $student['email'] ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                <i class="bi bi-telephone text-sm text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Phone</p>
                                <p class="text-sm font-semibold text-slate-800"><?= $student['phone'] ?: 'Not provided' ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                <i class="bi bi-calendar-check text-sm text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Member Since</p>
                                <p class="text-sm font-semibold text-slate-800"><?= $student['member_since'] ? date('F j, Y', strtotime($student['member_since'])) : 'N/A' ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vertical Divider -->
                <div class="hidden lg:block w-px bg-slate-200 my-10"></div>

                <!-- Right: Personal Details -->
                <div class="flex-1 p-6 sm:p-8 lg:p-10">
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-6">Personal Details</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Date of Birth</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $student['date_of_birth'] ? date('F j, Y', strtotime($student['date_of_birth'])) : 'Not specified' ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Gender</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $student['gender'] ?: 'Not specified' ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Education Level</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $student['education_level'] ?: 'Not specified' ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Field of Study</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $student['field_of_study'] ?: 'Not specified' ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Location</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $student['location'] ?: 'Not specified' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Two Equal Cards -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- About Me -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <i class="bi bi-person text-sm text-indigo-600"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">About Me</h3>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed"><?= $student['about_me'] ?: 'No biography added yet.' ?></p>
            </div>

            <!-- Career Preferences -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <i class="bi bi-briefcase text-sm text-indigo-600"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Career Preferences</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Preferred Industry</p>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $student['preferred_industry'] ?: 'Not specified' ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Preferred Role</p>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $student['preferred_role'] ?: 'Not specified' ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Preferred Location</p>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $student['preferred_location'] ?: 'Not specified' ?></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
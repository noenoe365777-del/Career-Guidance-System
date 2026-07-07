<?php
$pageTitle = "My Profile";

$userData = $_SESSION['user'] ?? [];
$profileName = htmlspecialchars($profile['username'] ?? $userData['username'] ?? 'Student');
$profileEmail = htmlspecialchars($profile['email'] ?? $userData['email'] ?? '');
$profileRole = htmlspecialchars($userData['role_name'] ?? 'Student');
$profilePhone = htmlspecialchars($profile['phone'] ?? 'Not provided');
$profileAddress = htmlspecialchars($profile['address'] ?? 'Not provided');
$profileGender = htmlspecialchars($profile['gender'] ?? 'Not specified');
$profileEducation = htmlspecialchars($profile['education_level'] ?? 'Not specified');
$profileDob = htmlspecialchars($profile['date_of_birth'] ?? 'Not specified');

// Fallback image setup
$image = !empty($profile['profile_image'])
    ? BASE_URL . "/assets/images/" . $profile['profile_image']
    : "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=200&q=80"; // Premium default placeholder
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-10 tracking-tight text-slate-800">
    <div class="space-y-8 transition-all duration-300">
        
        <!-- ================= PROFILE HEADER CARD ================= -->
        <div class="bg-white rounded-[2rem] border border-slate-200/80 shadow-[0_12px_40px_rgba(15,23,42,0.03)] overflow-hidden">
            
            <!-- Cover Banner Graphic -->
            <div class="h-40 sm:h-52 bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 relative">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.15),transparent_50%)]"></div>
                
                <!-- Avatar Upload Placement -->
                <form
                    action="<?= BASE_URL ?>/index.php?page=update-profile-image"
                    method="POST"
                    enctype="multipart/form-data"
                    id="imageForm"
                    class="absolute left-6 sm:left-10 -bottom-12 sm:-bottom-14 z-20"
                >
                    <label for="profileImage" class="relative block cursor-pointer group select-none">
                        <img
                            src="<?= htmlspecialchars($image) ?>"
                            id="avatarPreview"
                            class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl border-4 sm:border-[6px] border-white bg-white shadow-md object-cover transition duration-200 hover:scale-[1.01]"
                            alt="Profile Avatar"
                        >
                        <div class="absolute inset-0 rounded-2xl bg-slate-900/40 opacity-0 hover:opacity-100 flex items-center justify-center transition duration-150">
                            <i class="fas fa-camera text-white text-sm sm:text-base"></i>
                        </div>
                    </label>
                    <input type="file" id="profileImage" name="profile_image" accept="image/*" hidden>
                </form>
            </div>

            <!-- Profile Identity Frame -->
            <div class="pt-16 sm:pt-20 pb-6 px-6 sm:px-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-1.5">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900">
                            <?= $profileName ?>
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 font-bold text-xs uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                            <?= $profileRole ?>
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">
                        <?= $profileEmail ?>
                    </p>
                </div>

                <!-- Control Actions Matrix -->
                <div class="flex gap-3 w-full md:w-auto">
                    <a
                        href="<?= BASE_URL ?>/index.php?page=edit-profile"
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 h-10 px-4 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold text-sm shadow-sm hover:bg-slate-50 transition"
                    >
                        <i class="fas fa-user-edit text-xs text-slate-400"></i>
                        <span>Edit Profile</span>
                    </a>
                    <a
                        href="<?= BASE_URL ?>/index.php?page=change-password"
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 h-10 px-4 rounded-xl bg-slate-900 text-white font-semibold text-sm shadow-sm hover:bg-slate-800 transition"
                    >
                        <i class="fas fa-key text-xs opacity-60"></i>
                        <span>Password</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- ================= STATS GRID SYSTEM ================= -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-widest text-slate-400 px-1">
                Ecosystem Metrics
            </h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                        <i class="fas fa-brain text-xs"></i>
                    </div>
                    <span class="block text-2xl font-black text-slate-900">5</span>
                    <span class="block text-xs text-slate-500 font-semibold mt-0.5">Completed Exams</span>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                        <i class="fas fa-briefcase text-xs"></i>
                    </div>
                    <span class="block text-2xl font-black text-slate-900">18</span>
                    <span class="block text-xs text-slate-500 font-semibold mt-0.5">Matched Sectors</span>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                        <i class="fas fa-chart-line text-xs"></i>
                    </div>
                    <span class="block text-2xl font-black text-slate-900">85%</span>
                    <span class="block text-xs text-slate-500 font-semibold mt-0.5">System Progress</span>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs">
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-3">
                        <i class="fas fa-award text-xs"></i>
                    </div>
                    <span class="block text-lg font-black text-emerald-600 flex items-center gap-1.5 mt-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                        Active
                    </span>
                    <span class="block text-xs text-slate-500 font-semibold mt-1">Account Status</span>
                </div>

            </div>
        </div>

        <!-- ================= DETAILS BENTO GRID ================= -->
        <div class="grid lg:grid-cols-2 gap-6">
            
            <!-- Personal Card -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-xs p-6 sm:p-8">
                <div class="flex items-center gap-2.5 mb-6 pb-3 border-b border-slate-100">
                    <i class="far fa-user text-slate-400 text-sm"></i>
                    <h3 class="text-base font-bold text-slate-900">Personal Core Details</h3>
                </div>
                <div class="space-y-4 text-sm font-medium">
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400">Username</span>
                        <span class="text-slate-900 font-bold"><?= $profileName ?></span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400">Email Reference</span>
                        <span class="text-slate-900 font-bold select-all"><?= $profileEmail ?></span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400">Phone Number</span>
                        <span class="text-slate-900 font-bold"><?= $profilePhone ?></span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400">Mailing Address</span>
                        <span class="text-slate-900 font-bold"><?= $profileAddress ?></span>
                    </div>
                </div>
            </div>

            <!-- Academic Card -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-xs p-6 sm:p-8">
                <div class="flex items-center gap-2.5 mb-6 pb-3 border-b border-slate-100">
                    <i class="fas fa-graduation-cap text-slate-400 text-sm"></i>
                    <h3 class="text-base font-bold text-slate-900">Academic Vectors</h3>
                </div>
                <div class="space-y-4 text-sm font-medium">
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400">Gender Orientation</span>
                        <span class="text-slate-900 font-bold"><?= $profileGender ?></span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400">Education Level</span>
                        <span class="inline-flex px-2 py-0.5 text-xs font-bold rounded-md bg-slate-100 border border-slate-200 text-slate-700">
                            <?= $profileEducation ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400">Date of Birth</span>
                        <span class="text-slate-900 font-bold"><?= $profileDob ?></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
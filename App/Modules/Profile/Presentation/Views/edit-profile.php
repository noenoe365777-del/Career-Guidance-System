<?php
$pageTitle = "Edit Profile";

$userData = $_SESSION['user'] ?? [];
$profileName = htmlspecialchars($profile['username'] ?? $userData['username'] ?? 'Student');
$profileEmail = htmlspecialchars($profile['email'] ?? $userData['email'] ?? '');
$profilePhone = htmlspecialchars($profile['phone'] ?? '');
$profileAddress = htmlspecialchars($profile['address'] ?? '');
$profileGender = $profile['gender'] ?? '';
$profileEducation = $profile['education_level'] ?? '';
$profileDob = $profile['date_of_birth'] ?? '';
?>
<main class="max-w-6xl mx-auto px-6 py-10 animate-fadeUp
bg-gradient-to-br from-indigo-50 via-white to-purple-50 rounded-3xl">

<div class="bg-white rounded-3xl shadow-2xl border border-indigo-100 p-10 hover:shadow-indigo-200 transition-all duration-500">
    <!-- Header -->

    


<div class="flex items-center justify-between mb-10">

<div class="flex items-center gap-6">

<div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center shadow-lg animate-float">

<i class="fa-regular fa-circle-user text-5xl text-indigo-600"></i>

</div>

<div>

<h1 class="text-5xl font-bold text-slate-900">

Edit Profile

</h1>

<p class="text-gray-500 mt-2">

Update your personal information

</p>

</div>

</div>

<div class="hidden md:block animate-float">

<i class="fa-solid fa-id-card text-7xl text-indigo-300"></i>

</div>

</div>

<form action="<?= BASE_URL ?>/index.php?page=update-profile" method="POST" class="space-y-6">

<!-- Username -->
<div class="grid md:grid-cols-2 gap-8 items-center bg-white border rounded-2xl shadow-md p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 animate-fadeUp"
style="animation-delay:0.1s;">

<div class="flex items-center gap-4">

<i class="fa-regular fa-user text-3xl text-indigo-600"></i>

<label class="font-semibold text-xl">

Username

</label>

</div>

<input
type="text"
name="username"
value="<?= htmlspecialchars($profileName) ?>"
required
class="w-full rounded-xl border border-gray-300 px-5 py-3
transition-all duration-300
focus:ring-4
focus:ring-indigo-200
focus:border-indigo-500
focus:scale-[1.02]
outline-none">
</div>

<!-- Email -->

<div class="grid md:grid-cols-2 gap-8 items-center bg-white border rounded-2xl shadow-md p-6
hover:-translate-y-1 hover:shadow-xl transition-all duration-300 animate-fadeUp" style="animation-delay:0.2s;">

<div class="flex items-center gap-4">

<i class="fa-regular fa-envelope text-3xl text-indigo-600"></i>

<label class="font-semibold text-xl">

Email

</label>

</div>

<input
type="email"
value="<?= htmlspecialchars($profileEmail) ?>"
readonly
class="w-full rounded-xl border border-gray-300 px-5 py-3
transition-all duration-300
focus:ring-4 focus:ring-indigo-200
focus:border-indigo-500
focus:scale-[1.02]
outline-none">

</div>

<!-- Phone -->

<div class="grid md:grid-cols-2 gap-8 items-center bg-white border rounded-2xl shadow-md p-6
hover:-translate-y-1 hover:shadow-xl transition-all duration-300 animate-fadeUp" style="animation-delay:0.3s;">

<div class="flex items-center gap-4">

<i class="fa-solid fa-phone text-3xl text-indigo-600"></i>

<label class="font-semibold text-xl">

Phone

</label>

</div>

<input
type="text"
name="phone"
value="<?= htmlspecialchars($profilePhone) ?>"
class="w-full rounded-xl border border-gray-300 px-5 py-3">

</div>

<!-- Gender -->

<div class="grid md:grid-cols-2 gap-8 items-center bg-white border rounded-2xl shadow-md p-6
hover:-translate-y-1 hover:shadow-xl transition-all duration-300 animate-fadeUp" style="animation-delay:0.4s;">

<div class="flex items-center gap-4">

<i class="fa-solid fa-venus-mars text-3xl text-indigo-600"></i>

<label class="font-semibold text-xl">

Gender

</label>

</div>

<select
name="gender"
class="w-full rounded-xl border border-gray-300 px-5 py-3">

<option value="">Select Gender</option>

<option value="Male" <?= ($profileGender ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>

<option value="Female" <?= ($profileGender ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>

</select>

</div>

<!-- Education -->

<div class="grid md:grid-cols-2 gap-8 items-center bg-white border rounded-2xl shadow-md p-6
hover:-translate-y-1 hover:shadow-xl transition-all duration-300 animate-fadeUp" style="animation-delay:0.5s;">

<div class="flex items-center gap-4">

<i class="fa-solid fa-graduation-cap text-3xl text-indigo-600"></i>

<label class="font-semibold text-xl">

Education Level

</label>

</div>

<select
name="education_level"
class="w-full rounded-xl border border-gray-300 px-5 py-3">

<option value="High School" <?= ($profileEducation == 'High School') ? 'selected' : '' ?>>High School</option>

<option value="Undergraduate" <?= ($profileEducation == 'Undergraduate') ? 'selected' : '' ?>>Undergraduate</option>

<option value="Graduate" <?= ($profileEducation == 'Graduate') ? 'selected' : '' ?>>Graduate</option>

</select>

</div>

<!-- DOB -->

<div class="grid md:grid-cols-2 gap-8 items-center bg-white border rounded-2xl shadow-md p-6
hover:-translate-y-1 hover:shadow-xl transition-all duration-300 animate-fadeUp" style="animation-delay:0.6s;">

<div class="flex items-center gap-4">

<i class="fa-regular fa-calendar text-3xl text-indigo-600"></i>

<label class="font-semibold text-xl">

Date of Birth

</label>

</div>

<input
type="date"
name="date_of_birth"
value="<?= htmlspecialchars($profileDob) ?>"
class="w-full rounded-xl border border-gray-300 px-5 py-3">

</div>

<!-- Address -->

<div class="grid md:grid-cols-2 gap-8 items-center bg-white border rounded-2xl shadow-md p-6
hover:-translate-y-1 hover:shadow-xl transition-all duration-300 animate-fadeUp" style="animation-delay:0.7s;">

<div class="flex items-center gap-4">

<i class="fa-solid fa-location-dot text-3xl text-indigo-600"></i>

<label class="font-semibold text-xl">

Address

</label>

</div>

<textarea
name="address"
rows="2"
class="w-full rounded-xl border border-gray-300 px-5 py-3"><?= htmlspecialchars($profileAddress) ?></textarea>

</div>

<button
type="submit"
class="w-full py-4 rounded-2xl
bg-gradient-to-r
from-purple-600
via-indigo-600
to-blue-500
text-white
text-xl
font-semibold
hover:scale-105
hover:shadow-2xl
active:scale-95
transition-all
duration-300
animate-glow animate-fadeUp" style="animation-delay:0.8s;">

<i class="fa-regular fa-floppy-disk mr-2"></i>

Save Changes

</button>

<p class="text-center text-gray-500">

<i class="fa-solid fa-lock mr-2"></i>

Your information is secure and protected

</p>

</form>

</div>

</main>
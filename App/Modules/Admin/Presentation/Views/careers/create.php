<?php
$errors = $errors ?? [];
$old = $old ?? [];
$educationLevels = $educationLevels ?? [];

$pageTitle = 'Add Career';
$headerTitle = 'Add Career';
$activeMenu = 'careers';

ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 m-0">Add Career</h1>
        <p class="text-sm text-slate-500 m-0 mt-1">Create a new career path for student recommendations.</p>
    </div>
    <div class="flex gap-2">
        <a href="<?= BASE_URL ?>/index.php?page=admin-careers"
           class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 no-underline">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<?php if (isset($errors['general'])): ?>
    <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium mb-6">
        <i class="bi bi-x-circle-fill text-base text-rose-500"></i>
        <div><?= htmlspecialchars($errors['general']) ?></div>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6">
        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-careers-store" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="career_name">Career Name <span class="text-red-400">*</span></label>
                    <input type="text" id="career_name" name="career_name"
                           class="block w-full px-4 py-2.5 text-sm bg-white border <?= isset($errors['career_name']) ? 'border-red-300' : 'border-slate-200' ?> rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                           value="<?= htmlspecialchars((string)($old['career_name'] ?? '')) ?>"
                           placeholder="e.g. Software Engineer">
                    <?php if (isset($errors['career_name'])): ?>
                        <p class="mt-1.5 text-xs font-medium text-red-500"><?= htmlspecialchars($errors['career_name']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="description">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                              placeholder="Describe the career path..."><?= htmlspecialchars((string)($old['description'] ?? '')) ?></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="required_skills">Required Skills</label>
                    <textarea id="required_skills" name="required_skills" rows="2"
                              class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                              placeholder="e.g. Programming, Problem Solving, Communication"><?= htmlspecialchars((string)($old['required_skills'] ?? '')) ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="average_salary">Average Salary</label>
                    <input type="text" id="average_salary" name="average_salary"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                           value="<?= htmlspecialchars((string)($old['average_salary'] ?? '')) ?>"
                           placeholder="e.g. 55000">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="growth_rate">Growth Rate</label>
                    <input type="text" id="growth_rate" name="growth_rate"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                           value="<?= htmlspecialchars((string)($old['growth_rate'] ?? '')) ?>"
                           placeholder="e.g. High, Medium, Low">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="education_required">Education Required <span class="text-red-400">*</span></label>
                    <select id="education_required" name="education_required"
                            class="block w-full px-4 py-2.5 text-sm bg-white border <?= isset($errors['education_required']) ? 'border-red-300' : 'border-slate-200' ?> rounded-xl text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150">
                        <option value="">Select education level...</option>
                        <?php foreach ($educationLevels as $level): ?>
                            <option value="<?= htmlspecialchars($level) ?>" <?= ((string)($old['education_required'] ?? '') === $level) ? 'selected' : '' ?>><?= htmlspecialchars($level) ?></option>
                        <?php endforeach; ?>
                        <option value="High School" <?= ((string)($old['education_required'] ?? '') === 'High School') ? 'selected' : '' ?>>High School</option>
                        <option value="Undergraduate" <?= ((string)($old['education_required'] ?? '') === 'Undergraduate') ? 'selected' : '' ?>>Undergraduate</option>
                        <option value="Graduate" <?= ((string)($old['education_required'] ?? '') === 'Graduate') ? 'selected' : '' ?>>Graduate</option>
                        <option value="Post Graduate" <?= ((string)($old['education_required'] ?? '') === 'Post Graduate') ? 'selected' : '' ?>>Post Graduate</option>
                        <option value="Doctorate" <?= ((string)($old['education_required'] ?? '') === 'Doctorate') ? 'selected' : '' ?>>Doctorate</option>
                    </select>
                    <?php if (isset($errors['education_required'])): ?>
                        <p class="mt-1.5 text-xs font-medium text-red-500"><?= htmlspecialchars($errors['education_required']) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="personality_type">Personality Type</label>
                    <input type="text" id="personality_type" name="personality_type"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                           value="<?= htmlspecialchars((string)($old['personality_type'] ?? '')) ?>"
                           placeholder="e.g. Introvert, Ambivert, Extrovert">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="interest_type">Interest Type</label>
                    <input type="text" id="interest_type" name="interest_type"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                           value="<?= htmlspecialchars((string)($old['interest_type'] ?? '')) ?>"
                           placeholder="e.g. Practical, Creative, Investigative">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="aptitude_type">Aptitude Type</label>
                    <input type="text" id="aptitude_type" name="aptitude_type"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                           value="<?= htmlspecialchars((string)($old['aptitude_type'] ?? '')) ?>"
                           placeholder="e.g. Developing, Competent, Advanced">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="values_type">Values Type</label>
                    <input type="text" id="values_type" name="values_type"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                           value="<?= htmlspecialchars((string)($old['values_type'] ?? '')) ?>"
                           placeholder="e.g. Defined">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 border-0 outline-none">
                    <i class="bi bi-check-lg mr-2"></i>
                    Create Career
                </button>
                <a href="<?= BASE_URL ?>/index.php?page=admin-careers"
                   class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 no-underline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

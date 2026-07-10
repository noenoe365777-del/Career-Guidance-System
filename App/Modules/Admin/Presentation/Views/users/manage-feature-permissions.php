<?php
$user = $user ?? [];
$features = $features ?? [];
$permissions = $permissions ?? [];
ob_start();
?>
<div class="px-4 sm:px-8 lg:px-10 py-8 space-y-8 flex-1 max-w-[1600px] w-full mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Manage Student Permissions</h2>
            <p class="text-slate-500 text-sm mt-1">Control the student portal features available to <?= htmlspecialchars((string)($user['username'] ?? 'this student')) ?>.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=admin-settings-student-permissions" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg font-medium text-sm transition">
            <i class="fas fa-arrow-left"></i>Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm mb-6">
                Enable or disable the student portal modules for this account. Disabled features will be hidden from the student sidebar and blocked from direct access.
            </div>

            <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-settings-student-permissions-save">
                <input type="hidden" name="user_id" value="<?= (int)($user['user_id'] ?? 0) ?>">

                <div class="space-y-3">
                    <?php foreach ($features as $feature):
                        $featureKey = (string)($feature['key'] ?? '');
                        $isEnabled = !empty($permissions[$featureKey]) || !array_key_exists($featureKey, $permissions);
                    ?>
                        <label class="flex items-center justify-between px-4 py-3 rounded-lg border border-slate-200 hover:border-slate-300 transition cursor-pointer">
                            <span>
                                <span class="font-semibold text-slate-800 text-sm block"><?= htmlspecialchars((string)($feature['label'] ?? $featureKey)) ?></span>
                                <span class="text-xs text-slate-400"><?= htmlspecialchars($featureKey) ?></span>
                            </span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="features[<?= htmlspecialchars($featureKey) ?>]" value="1" class="sr-only peer" <?= $isEnabled ? 'checked' : '' ?>>
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition shadow-sm"><i class="fas fa-save"></i>Save Permissions</button>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-settings-student-permissions" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg font-medium text-sm transition">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
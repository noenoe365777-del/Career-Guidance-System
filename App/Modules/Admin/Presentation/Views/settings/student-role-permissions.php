<?php
$features = $features ?? [];
$message = $message ?? '';
ob_start();
?>

<div class="max-w-3xl mx-auto">
    <?php if ($message === 'updated' || !empty($_SESSION['success'])): ?>
        <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium flex items-center gap-2">
            <i class="bi bi-check-circle"></i>
            <span><?= htmlspecialchars($_SESSION['success'] ?? 'Permissions updated successfully.') ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
        <div class="p-6 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-800 m-0">Student Role Permissions</h2>
                <p class="text-sm text-slate-500 mt-1 m-0">
                    Configure which features are enabled for all student accounts. Disabled features will be hidden from the student sidebar and prevented from direct URL access.
                </p>
            </div>
        </div>

        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-student-role-permissions-update">
            <div class="p-6">
                <div class="space-y-1">
                    <?php foreach ($features as $feature): ?>
                        <?php
                            $featureKey = $feature['feature_key'];
                            $featureLabel = $feature['feature_label'];
                            $isEnabled = (bool)(int)($feature['is_enabled'] ?? 1);
                        ?>
                        <label class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer group">
                            <input type="hidden" name="features[<?= htmlspecialchars($featureKey) ?>]" value="0">
                            <input type="checkbox"
                                   name="features[<?= htmlspecialchars($featureKey) ?>]"
                                   value="1"
                                   <?= $isEnabled ? 'checked' : '' ?>
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            <span class="text-sm font-medium text-slate-700 select-none group-hover:text-indigo-600 transition-colors">
                                <?= htmlspecialchars($featureLabel) ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 rounded-b-xl border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="<?= BASE_URL ?>/index.php?page=admin-dashboard"
                   class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    <i class="bi bi-check-lg mr-1"></i> Save Permissions
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-white rounded-xl border border-slate-100 shadow-sm p-6">
        <h3 class="text-sm font-bold text-slate-700 mb-3">How It Works</h3>
        <ul class="space-y-2 text-sm text-slate-500">
            <li class="flex items-start gap-2">
                <i class="bi bi-check-circle text-emerald-500 mt-0.5"></i>
                <span>When a permission is <strong class="text-slate-700">checked</strong>, all students can access that feature.</span>
            </li>
            <li class="flex items-start gap-2">
                <i class="bi bi-x-circle text-rose-500 mt-0.5"></i>
                <span>When a permission is <strong class="text-slate-700">unchecked</strong>, the feature is hidden from the student sidebar and direct URL access is blocked with a 403 error.</span>
            </li>
            <li class="flex items-start gap-2">
                <i class="bi bi-shield-check text-indigo-500 mt-0.5"></i>
                <span>Changes take effect immediately after saving — no login required.</span>
            </li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';

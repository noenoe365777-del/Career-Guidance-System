<?php
$errors = $errors ?? [];
$old = $old ?? [];
?>
<div class="px-4 sm:px-8 lg:px-10 py-8 space-y-8 flex-1 max-w-[1600px] w-full mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Edit Role</h2>
            <p class="text-slate-500 text-sm mt-1">Update role details and status.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=admin-roles" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg font-medium text-sm transition">
            <i class="fas fa-arrow-left"></i>Back to Role List
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc pl-5 space-y-1">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars((string)$error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="p-4 sm:p-6">
            <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-roles-update" novalidate>
                <input type="hidden" name="id" value="<?= (int)($old['role_id'] ?? 0) ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="role_name" class="block text-sm font-medium text-slate-700 mb-1">Role Name</label>
                        <input id="role_name" type="text" name="role_name" value="<?= htmlspecialchars((string)($old['role_name'] ?? '')) ?>" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 <?= isset($errors['role_name']) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-slate-300' ?>" required minlength="3" maxlength="50">
                        <?php if (!empty($errors['role_name'])): ?><p class="text-xs text-red-600 mt-1"><?= htmlspecialchars($errors['role_name']) ?></p><?php endif; ?>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select id="status" name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white <?= isset($errors['status']) ? 'border-red-500' : '' ?>" required>
                            <?php $statusValue = strtolower((string)($old['status'] ?? 'active')); ?>
                            <option value="active" <?= $statusValue === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $statusValue === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 <?= isset($errors['description']) ? 'border-red-500' : 'border-slate-300' ?>" maxlength="255"><?= htmlspecialchars((string)($old['description'] ?? '')) ?></textarea>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition shadow-sm"><i class="fas fa-save"></i>Update Role</button>
                    <a href="<?= BASE_URL ?>/index.php?page=admin-roles" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg font-medium text-sm transition">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</div>

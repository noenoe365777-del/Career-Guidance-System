<?php
$role = $role ?? [];
?>
<div class="px-4 sm:px-8 lg:px-10 py-8 space-y-8 flex-1 max-w-[1600px] w-full mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Role Details</h2>
            <p class="text-slate-500 text-sm mt-1">Detailed view of the selected role.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=admin-roles" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg font-medium text-sm transition">
            <i class="fas fa-arrow-left"></i>Back to Role List
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Role Name</label>
                    <div class="font-semibold text-slate-900 mt-1"><?= htmlspecialchars((string)($role['role_name'] ?? '')) ?></div>
                </div>
                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Status</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700"><?= htmlspecialchars((string)($role['status'] ?? 'active')) ?></span>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Description</label>
                    <div class="font-semibold text-slate-900 mt-1"><?= htmlspecialchars((string)($role['description'] ?? '')) ?></div>
                </div>
            </div>
        </div>
    </div>

</div>

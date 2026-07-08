<?php
$admin = $admin ?? [];
$users = $users ?? [];
$search = $search ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$totalUsers = $totalUsers ?? 0;
$message = $message ?? null;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'User Management') ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Exact Purple/Indigo Gradient Match from Reference Images */
        .bg-custom-gradient {
            background: linear-gradient(135deg, #5d5bf6 0%, #3b39df 100%) !important;
        }
        /* Custom scrollbar for horizontal table viewing on small screens */
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        /* Prevent layout breaking overlaps caused by native element behaviors */
        @media (min-width: 768px) {
            .mobile-sidebar-cleanup {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-700 antialiased min-h-screen overflow-hidden">

    <?php include __DIR__ . '/../header.php'; ?>

    <div class="flex h-screen w-full pt-[72px] overflow-hidden">
        
        <?php include __DIR__ . '/../sidebar.php'; ?>

        <main class="flex-1 h-full overflow-y-auto custom-scrollbar px-4 sm:px-8 lg:px-12 py-8 bg-[#f8fafc]">
            <div class="w-full max-w-[1500px] mx-auto space-y-6">
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2">
                    <div>
                        <h2 class="text-2xl font-bold text-[#1e293b] tracking-tight mb-1">User Management</h2>
                        <p class="text-sm font-medium text-slate-400">Monitor, create, edit, and manage system users.</p>
                    </div>
                  
                </div>

                <?php if ($message !== null): ?>
                    <div class="transform transition-all duration-300">
                        <?php if ($message === 'created'): ?>
                            <div class="flex items-center gap-3 p-4 border border-emerald-100 bg-emerald-50/50 rounded-2xl text-emerald-800 text-sm font-medium">
                                <i class="bi bi-check-circle-fill text-base text-emerald-500"></i>
                                <div>User created successfully.</div>
                            </div>
                        <?php elseif ($message === 'updated'): ?>
                            <div class="flex items-center gap-3 p-4 border border-blue-100 bg-blue-50/50 rounded-2xl text-blue-800 text-sm font-medium">
                                <i class="bi bi-info-circle-fill text-base text-blue-500"></i>
                                <div>User updated successfully.</div>
                            </div>
                        <?php elseif ($message === 'deleted'): ?>
                            <div class="flex items-center gap-3 p-4 border border-amber-100 bg-amber-50/50 rounded-2xl text-amber-800 text-sm font-medium">
                                <i class="bi bi-exclamation-triangle-fill text-base text-amber-500"></i>
                                <div>User deleted successfully.</div>
                            </div>
                        <?php elseif ($message === 'not_found'): ?>
                            <div class="flex items-center gap-3 p-4 border border-rose-100 bg-rose-50/50 rounded-2xl text-rose-800 text-sm font-medium">
                                <i class="bi bi-x-circle-fill text-base text-rose-500"></i>
                                <div>The selected user was not found.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-[20px] border border-slate-100 p-6 shadow-sm shadow-slate-100/40">
                    <form method="get" class="flex flex-col sm:flex-row items-end gap-4 w-full m-0">
                        <input type="hidden" name="page" value="admin-users">
                        <div class="w-full flex-1">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Search by name or email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                    <i class="bi bi-search text-sm"></i>
                                </span>
                                <input type="text" name="search" 
                                       class="block w-full pl-11 pr-4 py-3 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-[#3b39df] transition-all duration-150" 
                                       value="<?= htmlspecialchars($search) ?>" 
                                       placeholder="Search users by keywords...">
                            </div>
                        </div>
                        <div class="w-full sm:w-auto">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-indigo-600 bg-indigo-50/70 hover:bg-indigo-100/90 transition-all duration-150 border-0 outline-none whitespace-nowrap">
                                Search Records
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm shadow-slate-100/40 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse align-middle">
                            <thead>
                                <tr class="bg-white border-b border-slate-100">
                                    <th class="whitespace-nowrap px-6 py-4.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">User ID</th>
                                    <th class="whitespace-nowrap px-6 py-4.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Full Name</th>
                                    <th class="whitespace-nowrap px-6 py-4.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Email Address</th>
                                    <th class="whitespace-nowrap px-6 py-4.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Education Level</th>
                                    <th class="whitespace-nowrap px-6 py-4.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">System Role</th>
                                    <th class="whitespace-nowrap px-6 py-4.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Account Status</th>
                                    <th class="whitespace-nowrap px-6 py-4.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Created Date</th>
                                    <th class="whitespace-nowrap px-6 py-4.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right pr-6">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-[13px] text-slate-600 font-medium">
                                <?php if ($users === []): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-16 text-slate-400 font-medium bg-white">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <i class="bi bi-folder-x text-4xl text-slate-200"></i>
                                                <span class="text-sm">No data records found.</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                                            <td class="px-6 py-4 text-slate-400 font-normal">
                                                #<?= (int)($user['user_id'] ?? 0) ?>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-[#1e293b] whitespace-nowrap">
                                                <?= htmlspecialchars((string)($user['username'] ?? '')) ?>
                                            </td>
                                            <td class="px-6 py-4 text-slate-500 whitespace-nowrap font-normal">
                                                <?= htmlspecialchars((string)($user['email'] ?? '')) ?>
                                            </td>
                                            <td class="px-6 py-4 text-slate-400 whitespace-nowrap font-normal">
                                                <?= htmlspecialchars((string)$userModel->getEducationLevelName(!empty($user['education_level_id']) ? (int)$user['education_level_id'] : null)) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php 
                                                    $roleName = htmlspecialchars((string)($user['role_name'] ?? 'Student'));
                                                    $isRoleAdmin = strtolower($roleName) === 'admin';
                                                    $roleClasses = $isRoleAdmin ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600';
                                                ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide <?= $roleClasses ?>">
                                                    <?= $roleName ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php 
                                                    $statusName = (string)$userModel->getStatusName((int)($user['status_id'] ?? 3));
                                                    $isActiveState = strtolower($statusName) === 'active';
                                                    $statusClasses = $isActiveState ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600';
                                                ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide <?= $statusClasses ?>">
                                                    <?= htmlspecialchars($statusName) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-400 whitespace-nowrap font-normal">
                                                <?= htmlspecialchars(date('M d, Y', strtotime((string)($user['created_at'] ?? date('Y-m-d'))))) ?>
                                            </td>
                                            <td class="px-6 py-4 text-right pr-6 whitespace-nowrap">
                                                <div class="inline-flex items-center justify-end gap-1.5">
                                                    <a href="<?= BASE_URL ?>/index.php?page=admin-users-view&id=<?= (int)($user['user_id'] ?? 0) ?>" 
                                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all duration-150 no-underline" title="View Profile">
                                                        <i class="bi bi-eye text-base"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>/index.php?page=admin-users-edit&id=<?= (int)($user['user_id'] ?? 0) ?>" 
                                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-[#3b39df] hover:bg-indigo-50/50 transition-all duration-150 no-underline" title="Edit Profile">
                                                        <i class="bi bi-pencil text-sm"></i>
                                                    </a>
                                                    <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-users-delete" onsubmit="return confirm('Delete this user account parameters completely?');" class="inline m-0 p-0">
                                                        <input type="hidden" name="id" value="<?= (int)($user['user_id'] ?? 0) ?>">
                                                        <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-0 bg-transparent text-slate-300 hover:text-rose-600 hover:bg-rose-50/50 transition-all duration-150 outline-none p-0 cursor-pointer" type="submit" title="Delete User">
                                                            <i class="bi bi-trash text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if ($totalPages > 1): ?>
                    <nav class="flex justify-center pt-2 pb-6">
                        <ul class="inline-flex items-center gap-1.5 p-1 bg-white border border-slate-100 rounded-xl shadow-sm shadow-slate-100/20">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php $isCurrent = ($i === $currentPage); ?>
                                <li>
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-users&search=<?= urlencode($search) ?>&page_number=<?= $i ?>" 
                                       class="inline-flex items-center justify-center w-8.5 h-8.5 text-[11px] font-bold rounded-lg transition-all duration-150 no-underline border-0 min-w-8 px-2
                                              <?= $isCurrent ? 'bg-custom-gradient text-white shadow-sm' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-800' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Structural fix preventing conflicting responsive offcanvas items from displaying during sizing modifications
        document.addEventListener("DOMContentLoaded", function() {
            const mobileSidebar = document.getElementById("adminSidebarMobile");
            if (mobileSidebar) {
                mobileSidebar.classList.add("mobile-sidebar-cleanup");
            }
        });
    </script>
</body>
</html>
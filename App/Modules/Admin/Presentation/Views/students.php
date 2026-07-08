<?php
$students = $students ?? [];
$pageTitle = $pageTitle ?? 'Student Management';
?><!doctype html>
<html lang="en" class="h-full bg-[#f4f7fc]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f4f7fc !important; }</style>
</head>
<body class="h-full text-slate-700 antialiased font-sans m-0 p-0">
    <?php
    $sidebarPath = file_exists(__DIR__ . '/sidebar.php') ? __DIR__ . '/sidebar.php' : __DIR__ . '/../sidebar.php';
    $headerPath = file_exists(__DIR__ . '/header.php') ? __DIR__ . '/header.php' : __DIR__ . '/../header.php';
    ?>
    <div class="flex h-screen overflow-hidden">
        <div class="hidden md:flex md:shrink-0 h-full">
            <?php include $sidebarPath; ?>
        </div>
        <div class="flex flex-col flex-1 min-w-0 h-full overflow-hidden">
            <?php include $headerPath; ?>
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-[#f4f7fc]">
                <div class="max-w-[1400px] mx-auto space-y-6">
                    <div class="glass-card rounded-3xl border border-slate-100 bg-white p-6 shadow-[0_10px_40px_rgba(15,23,42,0.04)]">
                        <h2 class="text-2xl font-bold text-slate-800">Student Management</h2>
                        <p class="text-sm text-slate-500">Browse student records, progress, and account activity in the same polished layout.</p>
                    </div>
                    <div class="glass-card rounded-3xl border border-slate-100 bg-white overflow-hidden">
                        <div class="border-b border-slate-100 px-6 py-4">
                            <h3 class="text-lg font-semibold text-slate-800">Student List</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-semibold text-slate-500">Name</th>
                                        <th class="px-6 py-3 text-left font-semibold text-slate-500">Email</th>
                                        <th class="px-6 py-3 text-left font-semibold text-slate-500">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php if ($students === []): ?>
                                        <tr><td colspan="3" class="px-6 py-8 text-center text-slate-400">No student records available yet.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($students as $student): ?>
                                            <tr>
                                                <td class="px-6 py-4 font-semibold text-slate-700"><?= htmlspecialchars((string)($student['full_name'] ?? $student['username'] ?? 'Student')) ?></td>
                                                <td class="px-6 py-4 text-slate-500"><?= htmlspecialchars((string)($student['email'] ?? '')) ?></td>
                                                <td class="px-6 py-4"><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">Active</span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

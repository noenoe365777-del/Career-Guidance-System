<?php
/** @var string $pageTitle */
/** @var string $message */
?><!doctype html>
<html lang="en" class="h-full bg-[#f4f7fc]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Access Denied') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7fc !important; }
    </style>
</head>
<body class="h-full text-slate-700 antialiased font-sans m-0 p-0">
    <div class="min-h-screen flex items-center justify-center bg-[#f4f7fc] p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-lg rounded-3xl border border-slate-100 bg-white p-8 shadow-[0_10px_40px_rgba(15,23,42,0.08)] text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-50 text-rose-500 mb-6">
                <i class="bi bi-shield-exclamation text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Access Denied</h1>
            <p class="mt-3 text-sm text-slate-500">You do not have the required permission to view or perform this action.</p>
            <a href="<?= BASE_URL ?>/index.php?page=admin-dashboard" class="mt-6 inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition-colors duration-200">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

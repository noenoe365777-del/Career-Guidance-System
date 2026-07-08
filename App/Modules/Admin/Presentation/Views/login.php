<?php
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<!doctype html>
<html lang="en" class="h-full bg-[#f4f7fc]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Login') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7fc !important; }
        .glass-card { background: #ffffff; border: 1px solid #eef2f6; }
    </style>
</head>
<body class="h-full text-slate-700 antialiased font-sans m-0 p-0">
    <div class="min-h-screen flex items-center justify-center bg-[#f4f7fc] p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md glass-card rounded-3xl shadow-[0_10px_40px_rgba(15,23,42,0.08)] p-6 sm:p-8">
            <div class="text-center mb-6">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-shield-lock text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Admin Login</h2>
                <p class="text-sm text-slate-500 mt-2">Access the administration dashboard securely.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 mb-4">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-login" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-600 mb-2">Email address</label>
                    <input type="email" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" id="email" name="email" required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-600 mb-2">Password</label>
                    <input type="password" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" id="password" name="password" required>
                </div>

                <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors duration-200">Login</button>
            </form>
        </div>
    </div>
</body>
</html>

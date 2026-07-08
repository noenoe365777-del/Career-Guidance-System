<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= $pageTitle ?? 'Dashboard' ?></title>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body class="bg-slate-50">

<?php
$currentPage = $_GET['page'] ?? ($currentPage ?? 'dashboard');
?>

<div class="flex bg-slate-50/60 min-h-screen text-slate-800 antialiased font-sans overflow-x-hidden">

    <?php require BASE_PATH . '/App/Views/dashboard/sidebar.php'; ?>

    <div class="flex-1 lg:ml-72 flex flex-col min-w-0 transition-all duration-300">

        <?php require BASE_PATH . '/App/Views/dashboard/topbar.php'; ?>

        <main class="flex-1">
            <?= $content ?>
        </main>

    </div>
</div>

</body>

</html>
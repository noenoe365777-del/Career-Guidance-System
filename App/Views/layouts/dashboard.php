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

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="bg-[#f4f7fc]">

<?php
$currentPage = $_GET['page'] ?? ($currentPage ?? 'dashboard');
?>

<div class="flex h-screen overflow-hidden">

    <?php require BASE_PATH . '/App/Views/dashboard/sidebar.php'; ?>

    <div class="flex flex-col flex-1 overflow-hidden">

        <?php require BASE_PATH . '/App/Views/dashboard/topbar.php'; ?>

        <main class="flex-1 overflow-y-auto p-6 bg-[#f4f7fc]">
            <?= $content ?>
        </main>

    </div>
</div>

</body>

</html>
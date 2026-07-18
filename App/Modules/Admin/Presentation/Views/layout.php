<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Panel') ?></title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-[#f4f7fc] min-h-screen">

<div class="flex min-h-screen">
    <?php include __DIR__.'/sidebar.php'; ?>

    <div class="flex flex-col flex-1 min-w-0 lg:ml-64">
        <?php include __DIR__.'/header.php'; ?>

        <main class="flex-1 overflow-y-auto p-6 bg-[#f4f7fc]">
            <?= $content ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
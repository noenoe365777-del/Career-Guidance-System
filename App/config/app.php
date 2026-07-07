<!DOCTYPE html>
<html lang="en" class="h-full scroll-behavior-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Career Guidance System' ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Premium Icons Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            start: '#4f46e5', // Indigo 600
                            mid: '#6366f1',   // Indigo 500
                            end: '#a855f7'    // Purple 500
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full flex flex-col bg-slate-50 text-slate-800 antialiased">

    <!-- Header Partial View Injection Area -->
    <?php require BASE_PATH . '/App/Views/partials/header.php'; ?>

    <!-- Main Clean Dynamic Buffer Content Output Node -->
    <div class="flex-grow flex flex-col relative w-full">
        <?= $content ?>
    </div>

    <!-- Footer Partial View Injection Area -->
    <?php require BASE_PATH . '/App/Views/partials/footer.php'; ?>

</body>
</html>
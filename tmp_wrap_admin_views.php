<?php
$baseDir = __DIR__ . '/App/Modules/Admin/Presentation/Views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));
$skip = ['header.php', 'sidebar.php', 'footer.php', 'dashboard.php'];

foreach ($files as $fileinfo) {
    if (!$fileinfo->isFile()) {
        continue;
    }

    $filePath = $fileinfo->getPathname();
    $fileName = $fileinfo->getFilename();
    if (in_array($fileName, $skip, true)) {
        continue;
    }

    $content = file_get_contents($filePath);
    if ($content === false) {
        continue;
    }

    if (strpos($content, 'admin-shell-wrapper') !== false) {
        continue;
    }

    if (strpos($content, '<!doctype html>') === false && strpos($content, '<!DOCTYPE html>') === false) {
        continue;
    }

    $headPos = strpos($content, '<head>');
    if ($headPos !== false) {
        $headInsert = "<script src=\"https://cdn.tailwindcss.com\"></script>\n    <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">\n    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>\n    <link href=\"https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap\" rel=\"stylesheet\">\n    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css\" rel=\"stylesheet\">\n    <style>\n        body { font-family: 'Inter', sans-serif; background-color: #f4f7fc !important; }\n        .glass-card { background: #ffffff; border: 1px solid #eef2f6; }\n    </style>\n";

        if (strpos($content, 'https://cdn.tailwindcss.com') === false) {
            $content = substr_replace($content, $headInsert, $headPos + 6, 0);
        }
    }

    $bodyPos = strpos($content, '<body');
    if ($bodyPos === false) {
        continue;
    }

    $bodyTagEnd = strpos($content, '>', $bodyPos);
    if ($bodyTagEnd === false) {
        continue;
    }

    $bodyOpenTag = substr($content, $bodyPos, $bodyTagEnd - $bodyPos + 1);
    $bodyTagInner = substr($bodyOpenTag, 5, -1);
    if (strpos($bodyTagInner, 'class=') === false) {
        $bodyOpenTag = str_replace('>', ' class="h-full text-slate-700 antialiased font-sans m-0 p-0">', $bodyOpenTag);
    }

    $bodyClosePos = strpos($content, '</body>', $bodyTagEnd);
    if ($bodyClosePos === false) {
        continue;
    }

    $insideBody = substr($content, $bodyTagEnd + 1, $bodyClosePos - ($bodyTagEnd + 1));

    $sidebarPath = '__DIR__ . \'/sidebar.php\'';
    $headerPath = '__DIR__ . \'/header.php\'';
    if (!file_exists(dirname($filePath) . '/sidebar.php')) {
        $sidebarPath = '__DIR__ . \'/../sidebar.php\'';
    }
    if (!file_exists(dirname($filePath) . '/header.php')) {
        $headerPath = '__DIR__ . \'/../header.php\'';
    }

    $shellStart = "\n<!-- admin-shell-wrapper -->\n<div class=\"flex h-screen overflow-hidden\">\n    <div class=\"hidden md:flex md:shrink-0 h-full\">\n        <?php include {$sidebarPath}; ?>\n    </div>\n    <div class=\"flex flex-col flex-1 min-w-0 h-full overflow-hidden\">\n        <?php include {$headerPath}; ?>\n        <main class=\"flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-[#f4f7fc]\">\n            <div class=\"max-w-[1400px] mx-auto space-y-6\">\n";

    $shellEnd = "\n            </div>\n        </main>\n    </div>\n</div>\n<!-- /admin-shell-wrapper -->\n";

    $newContent = substr($content, 0, $bodyTagEnd + 1) . $shellStart . $insideBody . $shellEnd . substr($content, $bodyClosePos);
    $newContent = str_replace($bodyOpenTag, '<body class="h-full text-slate-700 antialiased font-sans m-0 p-0">', $newContent);

    file_put_contents($filePath, $newContent);
}
?>
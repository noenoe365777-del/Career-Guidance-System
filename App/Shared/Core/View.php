<?php

declare(strict_types=1);

namespace App\Shared\Core;

use Exception;

class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data);

        $viewFile = BASE_PATH . '/App/Modules/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View not found: {$viewFile}");
        }

        ob_start();

        require $viewFile;

        $content = ob_get_clean();

        $layout = $data['layout'] ?? null;
        $useDashboardLayout = str_starts_with($view, 'Dashboard/') || $layout === 'dashboard';

        if ($layout === 'none' || $layout === false) {
            echo $content;
            return;
        }

/*
|--------------------------------------------------------------------------
| Dashboard pages
|--------------------------------------------------------------------------
*/

if ($useDashboardLayout) {

    require BASE_PATH . '/App/Views/layouts/dashboard.php';

    return;
}

/*
|--------------------------------------------------------------------------
| Public website pages
|--------------------------------------------------------------------------
*/

require BASE_PATH . '/App/Views/layouts/app.php';
    }
}
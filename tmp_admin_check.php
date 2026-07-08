<?php
require 'vendor/autoload.php';
$model = new App\Modules\Admin\Infrastructure\AdminModel();
$admin = $model->findAdminByEmail('admin@example.com');
echo json_encode($admin, JSON_UNESCAPED_SLASHES), PHP_EOL;
echo ($admin && $model->isAdmin($admin)) ? 'ADMIN_OK' : 'ADMIN_FAIL';

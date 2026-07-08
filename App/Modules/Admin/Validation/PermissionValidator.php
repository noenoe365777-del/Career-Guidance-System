<?php

declare(strict_types=1);

namespace App\Modules\Admin\Validation;

class PermissionValidator
{
    public function validate(array $data, ?int $excludeId = null, ?callable $existsCheck = null): array
    {
        $errors = [];
        $permissionName = trim((string)($data['permission_name'] ?? ''));
        $moduleName = trim((string)($data['module_name'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));

        if ($permissionName === '') {
            $errors['permission_name'] = 'Permission name is required.';
        } elseif ($existsCheck !== null && $existsCheck($permissionName, $excludeId)) {
            $errors['permission_name'] = 'This permission name already exists.';
        }

        if ($moduleName === '') {
            $errors['module_name'] = 'Module name is required.';
        }

        if ($description === '') {
            $errors['description'] = 'Description is required.';
        }

        return $errors;
    }
}

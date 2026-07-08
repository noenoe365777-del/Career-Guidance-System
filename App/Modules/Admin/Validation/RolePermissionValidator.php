<?php

declare(strict_types=1);

namespace App\Modules\Admin\Validation;

class RolePermissionValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        $roleId = (int)($data['role_id'] ?? 0);
        if ($roleId <= 0) {
            $errors['role_id'] = 'Please select a role.';
        }

        $permissions = $data['permissions'] ?? [];
        if (!is_array($permissions)) {
            $errors['permissions'] = 'Permissions must be provided as a list.';
        }

        return $errors;
    }
}

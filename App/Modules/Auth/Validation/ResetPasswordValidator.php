<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation;

use App\Modules\Auth\Validation\Rules\RequiredRule;
use App\Modules\Auth\Validation\Rules\StrongPasswordRule;
use App\Modules\Auth\Validation\Rules\MatchRule;

class ResetPasswordValidator extends Validator
{
    public function validate(array $data): bool
    {
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        $this->addError(
            'password',
            (new RequiredRule($password, 'New Password'))->validate()
        );

        if (!isset($this->errors['password'])) {
            $this->addError(
                'password',
                (new StrongPasswordRule($password))->validate()
            );
        }

        $this->addError(
            'confirm_password',
            (new RequiredRule($confirmPassword, 'Confirm Password'))->validate()
        );

        if (!isset($this->errors['confirm_password'])) {
            $this->addError(
                'confirm_password',
                (new MatchRule($password, $confirmPassword))->validate()
            );
        }

        return $this->passes();
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation;

use App\Modules\Auth\Validation\Rules\RequiredRule;
use App\Modules\Auth\Validation\Rules\EmailRule;
use App\Modules\Auth\Validation\Rules\MinLengthRule;

class LoginValidator extends Validator
{
    public function validate(array $data): bool
    {
        $email = trim($data['email'] ?? '');

        $this->addError(
            'email',
            (new RequiredRule($email, 'Email'))->validate()
        );

        if (!isset($this->errors['email'])) {
            $this->addError(
                'email',
                (new EmailRule($email))->validate()
            );
        }

        $password = $data['password'] ?? '';

        $this->addError(
            'password',
            (new RequiredRule($password, 'Password'))->validate()
        );

        if (!isset($this->errors['password'])) {
            $this->addError(
                'password',
                (new MinLengthRule($password, 8))->validate()
            );
        }

        return $this->passes();
    }
}
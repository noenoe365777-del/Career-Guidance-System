<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation;

use App\Modules\Auth\Validation\Rules\RequiredRule;
use App\Modules\Auth\Validation\Rules\EmailRule;

class ForgotPasswordValidator extends Validator
{
    public function validate(array $data): bool
    {
        $email = trim((string)($data['email'] ?? ''));

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

        return $this->passes();
    }
}

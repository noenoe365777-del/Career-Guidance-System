<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation;

use App\Modules\Auth\Validation\Rules\RequiredRule;
use App\Modules\Auth\Validation\Rules\EmailRule;
use App\Modules\Auth\Validation\Rules\MinLengthRule;
use App\Modules\Auth\Validation\Rules\MatchRule;
use App\Modules\Auth\Validation\Rules\InRule;
use App\Modules\Auth\Validation\Rules\DateRule;

class RegisterValidator extends Validator
{
    public function validate(array $data): bool
    {
        $fullname = trim($data['fullname'] ?? '');

        $this->addError(
            'fullname',
            (new RequiredRule($fullname, 'Full Name'))->validate()
        );

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

        $confirmPassword = $data['confirm_password'] ?? '';

        $this->addError(
            'confirm_password',
            (new MatchRule($password, $confirmPassword))->validate()
        );

        $education = $data['education'] ?? '';

        $this->addError(
            'education',
            (new InRule(
                $education,
                ['high-school', 'undergraduate', 'graduate']
            ))->validate()
        );

        $gender = $data['gender'] ?? '';

        $this->addError(
            'gender',
            (new InRule(
                $gender,
                ['male', 'female', 'other']
            ))->validate()
        );

        $dob = $data['dob'] ?? '';

        $this->addError(
            'dob',
            (new DateRule($dob))->validate()
        );

        return $this->passes();
    }
}
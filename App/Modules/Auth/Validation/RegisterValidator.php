<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation;


use App\Modules\Auth\Validation\Rules\UsernameRule;
use App\Modules\Auth\Validation\Rules\RequiredRule;
use App\Modules\Auth\Validation\Rules\StrongPasswordRule;
use App\Modules\Auth\Validation\Rules\EmailRule;
use App\Modules\Auth\Validation\Rules\MinLengthRule;
use App\Modules\Auth\Validation\Rules\MatchRule;
use App\Modules\Auth\Validation\Rules\InRule;
use App\Modules\Auth\Validation\Rules\DateRule;

class RegisterValidator extends Validator
{
    public function validate(array $data): bool
    {
        $username = trim($data['username'] ?? '');

        $this->addError(
            'username',
            (new RequiredRule($username, 'Username'))->validate()
        );

        if (!isset($this->errors['username'])) {
            $this->addError(
                'username',
                (new UsernameRule($username))->validate()
            );
        }

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
                (new StrongPasswordRule($password))->validate()
            );
        }

       $confirmPassword = $data['confirm_password'] ?? '';

$this->addError(
    'confirm_password',
    (new RequiredRule(
        $confirmPassword,
        'Confirm Password'
    ))->validate()
);

if (!isset($this->errors['confirm_password'])) {

    $this->addError(
        'confirm_password',
        (new MatchRule(
            $password,
            $confirmPassword
        ))->validate()
    );

}
        $education = $data['education'] ?? '';

        $this->addError(
            'education',
           (new InRule(
    $education,
    ['8', '9', '10']
))->validate()
        );

        $gender = $data['gender'] ?? '';

        $this->addError(
            'gender',
          
            (new InRule(
    $gender,
    ['5', '6', '7']
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
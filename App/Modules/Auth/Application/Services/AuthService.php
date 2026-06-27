<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Domain\Repositories\AuthRepositoryInterface;
use App\Modules\Auth\Validation\RegisterValidator;
use App\Modules\Auth\Validation\LoginValidator;

class AuthService
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */
    public function register(array $data): array
    {
    $validator = new RegisterValidator();

    if (!$validator->validate($data)) {
        return [
            'success' => false,
            'errors' => $validator->errors()
        ];
    }

    if ($this->authRepository->emailExists($data['email'])) {
        return [
            'success' => false,
            'errors' => [
                'email' => 'Email already exists.'
            ]
        ];
    }

    $username = trim($data['username']);
    $email = trim($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // IDs from master_data
    $userRoleId = 2;          // Student
    $statusId = 3;            // Active
    $genderId = (int)$data['gender'];
    $educationLevelId = (int)$data['education'];

    // Create user
    $userId = $this->authRepository->createUser(
        $username,
        $email,
        $password,
        null,
        $userRoleId,
        $statusId
    );

    if ($userId <= 0) {
        return [
            'success' => false,
            'message' => 'Registration failed.'
        ];
    }

    // Create student profile
    $profile = $this->authRepository->createStudentProfile(
        $userId,
        $genderId,
        $educationLevelId,
        null,
        null,
        $data['dob']
    );

    if (!$profile) {
        return [
            'success' => false,
            'message' => 'Student profile creation failed.'
        ];
    }

    return [
        'success' => true,
        'message' => 'Registration successful.'
    ];
}
    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

   public function login(array $data): array
{
   $validator = new LoginValidator();

if (!$validator->validate($data)) {

    return [
        'success' => false,
        'errors' => $validator->errors()
    ];
}

    $email = trim($data['email']);
    $password = $data['password'];

    $user = $this->authRepository->findUserByEmail($email);

    if (!$user) {
        return [
            'success' => false,
            'message' => 'Invalid email or password.'
        ];
    }

    if (!password_verify($password, $user['password'])) {
        return [
            'success' => false,
            'message' => 'Invalid email or password.'
        ];
    }

    return [
        'success' => true,
        'user' => $user
    ];
}
    /*
    |--------------------------------------------------------------------------
    | Google Login
    |--------------------------------------------------------------------------
    */

    public function loginWithGoogle(
        string $googleId,
        string $fullName,
        string $email
    ): array {

        // Find by Google ID
        $user = $this->authRepository->findUserByGoogleId($googleId);

        if ($user) {
            return [
                'success' => true,
                'message' => 'Google login successful.',
                'user' => $user
            ];
        }

        // Find by email
        $existingUser = $this->authRepository->findUserByEmail($email);

        if ($existingUser) {

            /*
             * Optional:
             * Update google_id here if your repository supports it.
             */

            return [
                'success' => true,
                'message' => 'Google login successful.',
                'user' => $existingUser
            ];
        }

        // Create new Google account
        $created = $this->authRepository->createGoogleUser(
            $fullName,
            $email,
            $googleId
        );

        if (!$created) {
            return [
                'success' => false,
                'message' => 'Failed to create Google account.'
            ];
        }

        $newUser = $this->authRepository->findUserByEmail($email);

        if (!$newUser) {
            return [
                'success' => false,
                'message' => 'Unable to retrieve Google user.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Google login successful.',
            'user' => $newUser
        ];
    }
}
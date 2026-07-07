<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Domain\Repositories\AuthRepositoryInterface;
use App\Modules\Auth\Validation\RegisterValidator;
use App\Modules\Auth\Validation\LoginValidator;
use App\Modules\Auth\Validation\ForgotPasswordValidator;
use App\Modules\Auth\Validation\ResetPasswordValidator;
use App\Shared\Services\Mailer;

class AuthService
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $data): array
    {
        $validator = new RegisterValidator();

        if (!$validator->validate($data)) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        $username = trim((string)($data['username'] ?? ''));
        $email = trim((string)($data['email'] ?? ''));

        if ($this->authRepository->usernameExists($username)) {
            return [
                'success' => false,
                'errors' => ['username' => 'Username already exists.']
            ];
        }

        if ($this->authRepository->emailExists($email)) {
            return [
                'success' => false,
                'errors' => ['email' => 'Email already exists.']
            ];
        }

        $password = password_hash((string)($data['password'] ?? ''), PASSWORD_DEFAULT);

        $userRoleId = 2;
        $statusId = 3;
        $genderId = (int)($data['gender'] ?? 0);
        $educationLevelId = (int)($data['education'] ?? 0);

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

        $profile = $this->authRepository->createStudentProfile(
            $userId,
            $genderId,
            $educationLevelId,
            null,
            null,
            $data['dob'] ?? null
        );

        if (!$profile) {
            return [
                'success' => false,
                'message' => 'Student profile creation failed.'
            ];
        }

        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = date('Y-m-d H:i:s', time() + 60 * 60);

        if (!$this->authRepository->createVerification($userId, $code, $expiresAt)) {
            return [
                'success' => false,
                'message' => 'Failed to create verification record.'
            ];
        }

        $subject = 'Verify your email';
        $message = "Your verification code: {$code}\nThis code expires in 1 hour.";
        $sent = Mailer::send($email, $subject, $message);

        if (!$sent) {
            error_log('Verification email failed to send to: ' . $email);
            return [
                'success' => false,
                'message' => 'Registration was saved, but the verification email could not be sent. Please try again later or contact support.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Registration successful. A verification code has been sent to your email.',
            'email' => $email,
            'user_id' => $userId
        ];
    }

    public function login(array $data): array
    {
        $validator = new LoginValidator();

        if (!$validator->validate($data)) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        $email = trim((string)($data['email'] ?? ''));
        $password = (string)($data['password'] ?? '');

        $user = $this->authRepository->findUserByEmail($email);
        if (!$user) {
            return [
                'success' => false,
                'errors' => ['password' => 'Invalid email or password.']
            ];
        }

        if (!password_verify($password, (string)($user['password'] ?? ''))) {
            return [
                'success' => false,
                'errors' => ['password' => 'Invalid email or password.']
            ];
        }

        $userId = (int)($user['user_id'] ?? $user['id'] ?? 0);

        if (!$this->authRepository->isUserVerifiedById($userId)) {
            return [
                'success' => false,
                'errors' => ['email' => 'Please verify your email before logging in.']
            ];
        }

        return [
            'success' => true,
            'user' => $user
        ];
    }

    public function forgotPassword(array $data): array
    {
        $validator = new ForgotPasswordValidator();

        if (!$validator->validate($data)) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        $email = trim((string)($data['email'] ?? ''));
        $user = $this->authRepository->findUserByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'errors' => ['email' => 'Email not found.']
            ];
        }

        $userId = (int)($user['user_id'] ?? $user['id'] ?? 0);
        if ($userId <= 0) {
            return [
                'success' => false,
                'message' => 'Email not found.'
            ];
        }

        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = date('Y-m-d H:i:s', time() + (15 * 60));

        if (!$this->authRepository->createPasswordResetRequest($userId, $code, $expiresAt)) {
            return [
                'success' => false,
                'message' => 'Unable to prepare password reset request. Please try again.'
            ];
        }

        $subject = 'Password Reset Code';
        $message = "Your password reset code is: {$code}\nThis code expires in 15 minutes.";
        $sent = Mailer::send($email, $subject, $message);

        if (!$sent) {
            return [
                'success' => false,
                'message' => 'Unable to send email. Please try again.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Verification code sent to your email.',
            'email' => $email,
            'user_id' => $userId
        ];
    }

    public function verifyPasswordResetCode(string $email, string $code): array
    {
        $email = trim($email);
        $code = trim($code);

        if ($email === '' || $code === '') {
            return [
                'success' => false,
                'message' => 'Invalid or expired code.'
            ];
        }

        $user = $this->authRepository->findUserByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid or expired code.'
            ];
        }

        $resetRequest = $this->authRepository->findPasswordResetRequestByEmailAndCode($email, $code);

        if (!$resetRequest) {
            return [
                'success' => false,
                'message' => 'Invalid or expired code.'
            ];
        }

        $userId = (int)($user['user_id'] ?? $user['id'] ?? 0);

        return [
            'success' => true,
            'message' => 'Code verified.',
            'user_id' => $userId
        ];
    }

    public function resetPassword(int $userId, array $data): array
    {
        if ($userId <= 0) {
            return [
                'success' => false,
                'message' => 'Unable to reset password. Please try again.'
            ];
        }

        $validator = new ResetPasswordValidator();

        if (!$validator->validate($data)) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        $password = $data['password'] ?? '';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        if (!$this->authRepository->updatePassword($userId, $passwordHash)) {
            return [
                'success' => false,
                'message' => 'Unable to update password. Please try again.'
            ];
        }

        $this->authRepository->deletePasswordResetRequestsForUser($userId);

        return [
            'success' => true,
            'message' => 'Password reset successfully.'
        ];
    }

    public function loginWithGoogle(string $googleId, string $fullName, string $email): array
    {
        $user = $this->authRepository->findUserByGoogleId($googleId);

        if ($user) {
            return [
                'success' => true,
                'message' => 'Google login successful.',
                'user' => $user
            ];
        }

        $existingUser = $this->authRepository->findUserByEmail($email);

        if ($existingUser) {
            return [
                'success' => true,
                'message' => 'Google login successful.',
                'user' => $existingUser
            ];
        }

        $created = $this->authRepository->createGoogleUser($fullName, $email, $googleId);

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

    public function verifyEmail(string $email, string $code, int $userId = 0): array
    {
        $ok = $this->authRepository->verifyCode($email, $code, $userId);
        echo $ok;

        if (!$ok) {
            return [
                'success' => false,
                'message' => 'Invalid or expired verification code.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Email verified successfully.'
        ];
    }

    public function resendVerification(string $email, int $userId = 0): array
    {
        $user = null;
        if ($userId > 0) {
            $user = $this->authRepository->findUserByEmail($email);
        } else {
            $user = $this->authRepository->findUserByEmail($email);
        }

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Email not found.'
            ];
        }

        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = date('Y-m-d H:i:s', time() + 60 * 60);
        $targetUserId = $userId > 0 ? $userId : (int)($user['user_id'] ?? $user['id'] ?? 0);

        if (!$this->authRepository->createVerification($targetUserId, $code, $expiresAt)) {
            return [
                'success' => false,
                'message' => 'Failed to create verification record.'
            ];
        }

        $subject = 'Your verification code';
        $message = "Your verification code: {$code}\nThis code expires in 1 hour.";
        $sent = Mailer::send($email, $subject, $message);

        if (!$sent) {
            error_log('Resend verification email failed to send to: ' . $email);
            return [
                'success' => false,
                'message' => 'Could not resend verification email. Please try again later.'
            ];
        }

        return [
            'success' => true,
            'message' => 'A new verification code has been sent.'
        ];
    }
}
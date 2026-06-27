<?php

declare(strict_types=1);

namespace App\Helpers;

class FormHelper
{
    public static function old(array $old, string $field): string
    {
        return htmlspecialchars((string)($old[$field] ?? ''));
    }

    public static function error(array $errors, string $field): string
    {
        if (!isset($errors[$field])) {
            return '';
        }

        return sprintf(
            '<small class="error-text">%s</small>',
            htmlspecialchars($errors[$field])
        );
    }

    public static function hasError(array $errors, string $field): string
    {
        return isset($errors[$field]) ? 'error' : '';
    }

    public static function selected(
        array $old,
        string $field,
        string $value
    ): string {
        return (($old[$field] ?? '') === $value)
            ? 'selected'
            : '';
    }

    public static function checked(
        array $old,
        string $field,
        string $value = '1'
    ): string {
        return (($old[$field] ?? '') === $value)
            ? 'checked'
            : '';
    }

    public static function value(
        array $old,
        string $field,
        string $default = ''
    ): string {
        return htmlspecialchars(
            (string)($old[$field] ?? $default)
        );
    }
}
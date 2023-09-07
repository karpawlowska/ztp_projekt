<?php
/**
 * User role.
 */

namespace App\Entity\Enum;

/**
 * Enum UserRole.
 */
enum UserRole: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Get the role label.
     *
     * @return string Role label
     */
    public function label(): string
    {
        return match ($this) {
            UserRole::ROLE_ADMIN => 'label.role_admin',
        };
    }
}

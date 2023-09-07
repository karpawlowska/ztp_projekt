<?php
/**
 * User Service Interface.
 */

namespace App\Service;

use App\Entity\User;

/**
 * Class UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Save entity.
     *
     * @param User   $user     User entity
     * @param string $password Password
     */
    public function save(User $user, string $password): void;

    /**
     * Edit password.
     *
     * @param User   $user     User entity
     * @param string $password Password
     */
    public function upgradePassword(User $user, string $password): void;

    /**
     * Edit data.
     *
     * @param User $user User entity
     */
    public function editData(User $user): void;
}

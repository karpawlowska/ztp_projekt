<?php
/**
 * User Service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * UserService constructor.
     *
     * @param UserRepository              $userRepository User repository
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     */
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Save entity.
     *
     * @param User   $user     User entity
     * @param string $password Password
     */
    public function save(User $user, string $password): void
    {
        // encode the plain password
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $password
            )
        );

        $this->userRepository->save($user, true);
    }

    /**
     * Delete entity.
     *
     * @param User $user User entity
     */
    public function delete(User $user): void
    {
        $this->userRepository->remove($user, true);
    }

    /**
     * Edit password.
     *
     * @param User   $user     User entity
     * @param string $password Password
     */
    public function upgradePassword(User $user, string $password): void
    {
        // encode the plain password
        $password = $this->passwordHasher->hashPassword(
            $user,
            $password
        );

        $this->userRepository->upgradePassword($user, $password);
    }

    /**
     * Edit data.
     *
     * @param User $user User entity
     */
    public function editData(User $user): void
    {
        $this->userRepository->save($user, true);
    }
}

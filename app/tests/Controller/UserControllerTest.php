<?php
/**
 * User controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends WebTestCase
{
    /**
     * Test client.
     */
    private const TEST_ROUTE = '/user';

    /**
     * Test client.
     */
    private KernelBrowser $httpClient;

    /**
     * Set up tests.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test show action for user.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    public function testShowAction(): void
    {
        // given
        $expectedStatusCode = 200;
        $user = $this->createUser([UserRole::ROLE_ADMIN->value], 'test_user_show_user@example.com');
        $this->httpClient->loginUser($user);
        $userId = $user->getId();
        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$userId);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }


    /**
     * Test edit password.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    public function testEditPassword(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_ADMIN->value], 'test_edit_password@example.com');
        $this->httpClient->loginUser($user);

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$user->getId().'/edit-password');

        // when
        $this->httpClient->submitForm('Edytuj',
            [
                'user_password' => [
                    'password' => [
                        'first' => 'Npas123!@#',
                        'second' => 'Npas123!@#',
                    ],
                ],
            ]);

        // then
        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Test edit user data.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    public function testEditUserData(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_ADMIN->value], 'user_edit_data@example.com');
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userId = $user->getId();
        $this->httpClient->loginUser($user);

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$user->getId().'/edit');
        $editedEmail = 'user_edited_data@example.com';
        $editedFirstName = 'e_first_name';
        $editedLastName = 'e_last_name';

        // when
        $this->httpClient->submitForm('Edytuj',
            [
                'user' => [
                    'email' => $editedEmail,
                    'firstName' => $editedFirstName,
                    'lastName' => $editedLastName,
                ],
            ]);

        // then
        $editedUser = $userRepository->findOneBy(['id' => $userId]);
        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
        $this->assertEquals($editedEmail, $editedUser->getEmail());
        $this->assertEquals($editedFirstName, $editedUser->getFirstName());
        $this->assertEquals($editedLastName, $editedUser->getLastName());
    }

    /**
     * Create user.
     *
     * @param array $roles User roles
     *
     * @return User User entity
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    private function createUser(array $roles, $email): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail($email);
        $user->setFirstName('USer');
        $user->setLastName('User');
        $user->setRoles($roles);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'p@55w0rd'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user, true);

        return $user;
    }
}

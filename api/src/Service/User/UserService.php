<?php

namespace App\Service\User;

use App\Entity\User\Permission;
use App\Entity\User\User;
use App\Exception\DbException;
use App\Repository\User\UserRepository;
use App\Service\Auth\PasswordHashService;
use App\Service\Helper\SerializeService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * Class UserService
 * @package App\Service\User
 */
class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SerializeService
     */
    private $serializeService;

    /**
     * @var PasswordHashService
     */
    private $passwordHashService;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param SerializeService $serializeService
     */
    public function __construct(UserRepository $userRepository, SerializeService $serializeService, PasswordHashService $passwordHashService)
    {
        $this->userRepository = $userRepository;
        $this->serializeService = $serializeService;
        $this->passwordHashService = $passwordHashService;
    }

    /**
     * Get all users
     *
     * @param $email
     * @param $status
     * @param $role
     * @param $page
     * @return array
     */
    public function all($email, $status, $role, $page): array
    {
        return $this->serializeService->normalize($this->userRepository->getAll($email, $status, $role, $page));
    }

    /**
     * Return count users
     *
     * @param $email
     * @param $status
     * @param $role
     * @return int
     * @throws DbException
     */
    public function totalUsers($email, $status, $role): int
    {
        try {
            return $this->userRepository->getCountUsers($email, $status, $role);
        } catch (NoResultException | NonUniqueResultException $e) {
            throw new DbException($e);
        }
    }

    /**
     * Get single user
     *
     * @param $id
     * @return array
     */
    public function single($id): array
    {
        return $this->serializeService->normalize($this->userRepository->get($id));
    }

    /**
     * Create user
     *
     * @param array $data
     * @return User $user
     */
    public function create(array $data): User
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if ($user !== null) {
            throw new \InvalidArgumentException("User who has this email already exists.");
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPasswordHash($this->passwordHashService->hashPassword($user, $data['password']));
        $user->setRoles($data['roles']);
        $user->setStatus($data['status'])->onPrePersist()->onPreUpdate();
        $this->userRepository->save($user);
        return $user;
    }

    /**
     * Update user
     *
     * @param array $data
     * @param int $id
     * @return User $user
     */
    public function update(array $data, int $id): User
    {
        $user = $this->userRepository->get($id);
        $user->setEmail($data['email']);
        if (array_key_exists('password', $data) && $data['password'] !== '') {
            $user->setPasswordHash($this->passwordHashService->hashPassword($user, $data['password']));
        }
        $user->setRoles($data['roles']);
        $user->setStatus($data['status'])->onPreUpdate();
        $this->userRepository->save($user);
        return $user;
    }

    /**
     * Change user password
     *
     * @param array $data
     * @param int $id
     * @return User
     */
    public function changePassword(array $data, int $id): User
    {
        $user = $this->userRepository->get($id);
        if (array_key_exists('password', $data) && $data['password'] !== '') {
            $user->setPasswordHash($this->passwordHashService->hashPassword($user, $data['password']));
        }
        $user->onPreUpdate();
        $this->userRepository->save($user);
        return $user;
    }

    /**
     * Assign permission to user
     *
     * @param $id
     * @param Permission $permission
     * @return User
     */
    public function assignPermission($id, Permission $permission): User
    {
        $user = $this->userRepository->get($id);
        $user->setPermission($permission)->onPreUpdate();
        $this->userRepository->save($user);
        return $user;
    }

    /**
     * Delete user
     *
     * @param $id
     * @return void
     */
    public function delete($id): void
    {
        $user = $this->userRepository->get($id);
        $this->userRepository->delete($user);
    }

    /**
     * Activate user
     *
     * @param $id
     * @return User $user
     */
    public function activate($id): User
    {
        $user = $this->userRepository->get($id);
        $user->setStatus(User::$STATUS_ACTIVE);
        $this->userRepository->save($user);
        return $user;
    }

    /**
     * Block user
     *
     * @param $id
     * @return User $user
     */
    public function block($id): User
    {
        $user = $this->userRepository->get($id);
        $user->setStatus(User::$STATUS_BLOCKED);
        $this->userRepository->save($user);
        return $user;
    }
}

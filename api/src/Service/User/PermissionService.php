<?php

namespace App\Service\User;

use App\Entity\User\Permission;
use App\Repository\User\PermissionRepository;
use App\Service\Helper\SerializeService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use http\Exception\RuntimeException;

/**
 * Class PermissionService
 * @package App\Service\User
 */
class PermissionService
{
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    /**
     * @var SerializeService
     */
    private $serializeService;

    /**
     * PermissionService constructor.
     * @param PermissionRepository $permissionRepository
     * @param SerializeService $serializeService
     */
    public function __construct(PermissionRepository $permissionRepository, SerializeService $serializeService)
    {
        $this->permissionRepository = $permissionRepository;
        $this->serializeService = $serializeService;
    }

    /**
     * Get all permissions
     *
     * @param string $name
     * @param string $status
     * @param int $page
     * @return array
     */
    public function all(string $name, string $status, int $page): array
    {
        return $this->serializeService->normalize($this->permissionRepository->getAll($name, $status, $page));
    }

    /**
     * Get count all permissions
     *
     * @param string $name
     * @param string $status
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function totalPermissions(string $name, string $status): int
    {
        return $this->permissionRepository->getCountPermissions($name, $status);
    }

    /**
     * Get single permission
     *
     * @param int $id
     * @return array
     */
    public function single(int $id): array
    {
        return $this->serializeService->normalize($this->permissionRepository->get($id));
    }

    /**
     * Create permission
     *
     * @param array $data
     * @return Permission $permission
     */
    public function create(array $data): Permission
    {
        $permission = $this->permissionRepository->findOneBy(['name' => $data['name']]);
        if ($permission !== null) {
            throw new \InvalidArgumentException("Permission already exists.");
        }

        $permission = new Permission();
        $permission->setName($data['name']);
        $permission->setStatus($data['status'])->onPrePersist()->onPreUpdate();
        try {
            $this->permissionRepository->save($permission);
        } catch (OptimisticLockException | ORMException $e) {
            throw new RuntimeException($e);
        }
        return $permission;
    }

    /**
     * Update permission
     *
     * @param array $data
     * @param int $id
     * @return Permission $permission
     */
    public function update(array $data, int $id): Permission
    {
        $permission = $this->permissionRepository->get($id);
        $permission->setName($data['name']);
        $permission->setStatus($data['status'])->onPreUpdate();
        try {
            $this->permissionRepository->save($permission);
        } catch (OptimisticLockException | ORMException $e) {
            throw new RuntimeException($e);
        }
        return $permission;
    }

    /**
     * Delete user
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $permission = $this->permissionRepository->get($id);
        try {
            $this->permissionRepository->delete($permission);
        } catch (OptimisticLockException | ORMException $e) {
            throw new RuntimeException($e);
        }
    }
}

<?php

namespace App\Service\User;

use App\Entity\User\Permission;
use App\Repository\User\PermissionRepository;
use App\Service\Helper\SerializeService;

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
     * @param $name
     * @param $status
     * @param $page
     * @return array
     */
    public function all($name, $status, $page): array
    {
        return $this->serializeService->normalize($this->permissionRepository->getAll($name, $status, $page));
    }

    /**
     * Get count all permissions
     *
     * @param $name
     * @param $status
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function totalPermissions($name, $status): int
    {
        return $this->permissionRepository->getCountPermissions($name, $status);
    }

    /**
     * Get single permission
     *
     * @param $id
     * @return array
     */
    public function single($id): array
    {
        return $this->serializeService->normalize($this->permissionRepository->get($id));
    }
}

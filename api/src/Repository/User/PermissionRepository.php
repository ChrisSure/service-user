<?php

namespace App\Repository\User;

use App\Entity\User\Permission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Permission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Permission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Permission[]    findAll()
 * @method Permission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermissionRepository extends ServiceEntityRepository
{
    /**
     * @var mixed
     */
    private $pageCount;

    /**
     * PermissionRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permission::class);
        $this->pageCount = $_ENV['PAGE_COUNT'];
    }

    /**
     * Get permission
     *
     * @param $id
     * @return Permission
     */
    public function get($id): Permission
    {
        $permission = $this->find($id);
        if (!$permission)
            throw new NotFoundHttpException('Permission doesn\'t exist.');
        return $permission;
    }

    /**
     * Save permission
     *
     * @param Permission $socialUser
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Permission $socialUser): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($socialUser);
        $entityManager->flush();
    }

    /**
     * Get all permissions
     *
     * @param $name
     * @param $status
     * @param null $page
     * @return array
     */
    public function getAll($name, $status, $page = null): array
    {
        $qb = $this->createQueryBuilder('u');
        if ($name) {
            $qb->andWhere('u.name LIKE :name')->setParameter('name', "%".$name."%");
        }
        if ($status) {
            $qb->andwhere('u.status = :status')->setParameter('status', $status);
        }

        $page = $page ?: 1;
        $offset = ($page - 1)  * $this->pageCount;
        $qb->setMaxResults($this->pageCount)->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get count all permissions
     *
     * @param $name
     * @param $status
     * @return string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountPermissions($name, $status): string
    {
        $qb = $this->createQueryBuilder('u')->select('COUNT(u)');
        if ($name) {
            $qb->andWhere('u.name LIKE :name')->setParameter('name', "%".$name."%");
        }
        if ($status) {
            $qb->andwhere('u.status = :status')->setParameter('status', $status);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}

<?php

namespace App\Controller\User;

use App\Service\User\PermissionService;
use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use OpenApi\Annotations as OA;

/**
 * @Route("/permissions")
 * @OA\Tag(name="Permissions")
 * @IsGranted("ROLE_ADMIN")
 */
class PermissionController extends AbstractController
{
    /**
     * @var PermissionService
     */
    private $permissionService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * @Route("/",  methods={"GET"})
     * Get all permissions
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $status = $request->query->get('status');
        $page = $request->query->get('page');

        try {
            $permissions = $this->permissionService->all($name, $status, $page);
            $totalPermissions = $this->permissionService->totalPermissions($name, $status);
            return new JsonResponse(
                [
                    'permissions' => $permissions,
                    'totalPermissions' => $totalPermissions
                ],  Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/{id}",  methods={"GET"})
     * Get single permission
     *
     * @param int $id
     * @return JsonResponse
     */
    public function single($id): JsonResponse
    {
        try {
            $permission = $this->permissionService->single($id);
            return new JsonResponse(['permission' => $permission], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

}

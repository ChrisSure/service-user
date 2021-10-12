<?php

namespace App\Controller\User;

use App\Service\User\PermissionService;
use App\Service\User\UserService;
use App\Validation\User\PermissionValidation;
use Doctrine\DBAL\DBALException;
use http\Exception\RuntimeException;
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
     * @param PermissionService $permissionService
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
    public function single(int $id): JsonResponse
    {
        try {
            $permission = $this->permissionService->single($id);
            return new JsonResponse(['permission' => $permission], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("",  methods={"POST"})
     * Create permission
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new PermissionValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->permissionService->create($data);
            return new JsonResponse(['message' => "Created successful"], Response::HTTP_CREATED);
        } catch(\InvalidArgumentException $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/{id}",  methods={"PUT"})
     * Update permission
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new PermissionValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->permissionService->update($data, $id);
            return new JsonResponse(['message' => "Updated successful"], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch(RuntimeException $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/{id}",  methods={"DELETE"})
     * Delete permission
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->permissionService->delete($id);
            return new JsonResponse(['message' => "Permission was deleted"], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch(RuntimeException $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

}

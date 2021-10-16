<?php

namespace App\Controller\Auth;

use App\Service\Auth\AuthService;
use App\Service\Auth\JWTService;
use App\Service\User\UserService;
use App\Validation\Auth\ForgetPasswordValidation;
use App\Validation\Auth\NewPasswordValidation;
use App\Validation\Auth\UserAuthValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/auth")
 * @OA\Tag(name="Auth")
 */
class AuthController extends AbstractController
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var JWTService
     */
    private $jwtService;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService
     * @param JWTService $jwtService
     */
    public function __construct(AuthService $authService, JWTService $jwtService)
    {
        $this->authService = $authService;
        $this->jwtService = $jwtService;
    }

    /**
     * @Route("/signin",  methods={"POST"})
     * Sign in user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signIn(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $violations = (new UserAuthValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $token = $this->authService->loginUser($data);
            return new JsonResponse(['token' => $token], JsonResponse::HTTP_OK);
        } catch (NotFoundHttpException | AccessDeniedHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/signup",  methods={"POST"})
     * Sign up user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signUp(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new UserAuthValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $this->authService->createUser($data);
            return new JsonResponse(['message' => 'For confirm registration check your email'], JsonResponse::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Confirm user email
     * @Route("/confirm-register",  methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmRegister(Request $request): JsonResponse
    {
        $data = $request->query->all();

        try {
            $token = $this->authService->confirmRegisterUser($data);
            return new JsonResponse(['token' => $token], JsonResponse::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Forget user email
     * @Route("/forget-password",  methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function forgetPassword(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new ForgetPasswordValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $this->authService->forgetPassword($data);
            return new JsonResponse(['message' => 'Check your email for the next step.'], JsonResponse::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (\BadMethodCallException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Confirm new user password
     * @Route("/confirm-new-password",  methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmNewPassword(Request $request): JsonResponse
    {
        $data = $request->query->all();

        try {
            $this->authService->confirmNewPassword($data);
            return new JsonResponse(['message' => "Confirmed"], JsonResponse::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (\BadMethodCallException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Set new user password
     * @Route("/new-password/{id}",  methods={"POST"})
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function newPassword(Request $request, $id): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new NewPasswordValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $token = $this->authService->newPassword($data, $id);
            return new JsonResponse(['token' => $token], JsonResponse::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    /**
     * Login using social networks
     * @Route("/signin-social",  methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function loginSocial(Request $request): JsonResponse
    {
        $data = $request->request->all();

        try {
            $token = $this->authService->loginSocialUser($data);
            return new JsonResponse(['token' => $token], 201);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }


}

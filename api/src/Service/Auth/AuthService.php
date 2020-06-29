<?php

namespace App\Service\Auth;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\Email\AuthMailService;
use App\Service\Helper\SerializeService;
use App\Service\User\UserTokenService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthService
{
    /**
     * @var PasswordHashService
     */
    private $passwordHashService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var JWTService
     */
    private $jwtService;

    /**
     * @var UserTokenService
     */
    private $userTokenService;

    /**
     * @var SerializeService
     */
    private $serializeService;

    /**
     * @var AuthMailService
     */
    private $authMailService;


    /**
     * AuthService constructor.
     *
     * @param UserRepository $userRepository
     * @param PasswordHashService $passwordHashService
     * @param JWTService $jwtService
     * @param UserTokenService $userTokenService
     * @param SerializeService $serializeService
     * @param AuthMailService $authMailService
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordHashService $passwordHashService,
        JWTService $jwtService,
        UserTokenService $userTokenService,
        SerializeService $serializeService,
        AuthMailService $authMailService
    )
    {
        $this->passwordHashService = $passwordHashService;
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
        $this->userTokenService = $userTokenService;
        $this->serializeService = $serializeService;
        $this->authMailService = $authMailService;
    }

    /**
     * Login user
     *
     * @param array $data
     * @return string
     */
    public function loginUser(array $data): string
    {
        $user = $this->checkCredentials($data);
        return $this->jwtService->create($user);
    }

    /**
     * Create user on site
     *
     * @param array $data
     */
    public function createUser(array $data): void
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if ($user !== null) {
            throw new \InvalidArgumentException("User who has this email already exists.");
        }

        $token = $this->userTokenService->generateToken();
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPasswordHash($this->passwordHashService->hashPassword($user, $data['password']));
        $user->setRoles(User::$ROLE_USER);
        $user->setToken($this->serializeService->serialize($token));
        $user->setStatus(User::$STATUS_NEW)->onPrePersist()->onPreUpdate();
        $this->userRepository->save($user);

        $this->authMailService->sendCheckRegistration($user, $token->getToken());
    }

    /**
     * Check user credentials
     *
     * @param array $data
     * @return User
     * @throws NotFoundHttpException
     * @throws AccessDeniedHttpException
     */
    private function checkCredentials(array $data): User
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);

        if (!$user || !$this->passwordHashService->checkPassword($data['password'], $user))
            throw new NotFoundHttpException('You have entered mistake login or password.');

        if ($user->getStatus() != User::$STATUS_ACTIVE)
            throw new AccessDeniedHttpException('You didn\'t accept your email.');

        if ($data['type'] === 'admin' && ($user->getRoles()[0] !== $user::$ROLE_ADMIN && $user->getRoles()[0] !== $user::$ROLE_SUPER_ADMIN))
            throw new AccessDeniedHttpException('You don\'t have permission.');

        return $user;
    }
}
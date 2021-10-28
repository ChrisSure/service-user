<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserControllerTest extends Base
{
    /**
     * @test
     */
    public function updateErrorValidation(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['email' => ''];
        $this->client->request('PUT', '/users/3', [], [], [], json_encode($data));
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(is_string($response->error));
    }

    /**
     * @test
     */
    public function updateNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = json_encode(['email' => 'super_admin@gmail.com', 'roles' => User::$ROLE_ADMIN, 'status' => User::$STATUS_ACTIVE]);
        $this->client->request(
            'PUT',
            '/users/235',
            [], [], [], $data
        );
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('User doesn\'t exist.', $response->error);
    }

    /**
     * @test
     */
    public function createAlreadyIssetEmail(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = json_encode(['email' => 'super_admin@gmail.com', 'roles' => User::$ROLE_ADMIN, 'status' => User::$STATUS_ACTIVE]);
        $this->client->request(
            'PUT',
            '/users/3',
            [], [], [], $data
        );
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('User who has this email already exists.', $response->error);
    }

    /**
     * @test
     */
    public function updateSuccessfull(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = json_encode(['email' => 'admin_test@gmail.com', 'roles' => User::$ROLE_ADMIN, 'status' => User::$STATUS_ACTIVE]);
        $this->client->request(
            'PUT',
            '/users/3',
            [], [], [], $data
        );
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Updated successful', $response->message);

        $this->revertChanges();
    }

    private function revertChanges()
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin_test@gmail.com']);
        $user->setEmail('admin@gmail.com');
        $this->manager->persist($user);
        $this->manager->flush();
    }
}

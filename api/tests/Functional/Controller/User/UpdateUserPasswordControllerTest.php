<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserPasswordControllerTest extends Base
{
    /**
     * @test
     */
    public function updatePasswordErrorValidation(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['password' => ''];
        $this->client->request('PUT', '/users/3/update-password', [], [], [], json_encode($data));
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(is_string($response->error));
    }

    /**
     * @test
     */
    public function updatePasswordNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = json_encode(['password' => '123']);
        $this->client->request(
            'PUT',
            '/users/235/update-password',
            [], [], [], $data
        );
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('User doesn\'t exist.', $response->error);
    }

    /**
     * @test
     */
    public function updatePasswordSuccessfull(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = json_encode(['password' => '123']);
        $this->client->request(
            'PUT',
            '/users/3/update-password',
            [], [], [], $data
        );
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Updated successful', $response->message);
    }

}

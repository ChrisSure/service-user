<?php

namespace App\Tests\Functional\Controller\Permission;

use App\Entity\User\Permission;
use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\Response;

class CreatePermissionControllerTest extends Base
{
    /**
     * @test
     */
    public function createErrorValidation(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['name' => ''];
        $this->client->request('POST', '/permissions', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(is_string($response->error));
    }

    /**
     * @test
     */
    public function createAlreadyIssetEmail(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['name' => 'Permission 1', 'status' => Permission::$STATUS_ACTIVE];
        $this->client->request('POST', '/permissions', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Permission already exists.', $response->error);
    }

    /**
     * @test
     */
    public function createSuccessfull(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['name' => $name = 'Permission 99', 'status' => Permission::$STATUS_ACTIVE];
        $this->client->request('POST', '/permissions', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Created successful', $response->message);

        $this->revertChanges($name);
    }

    private function revertChanges($name)
    {
        $permission = $this->doctrine->getRepository(Permission::class)->findOneBy(['name' => $name]);
        $this->manager->remove($permission);
        $this->manager->flush();
    }
}

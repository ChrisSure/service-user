<?php

namespace App\Tests\Functional\Controller\Permission;

use App\Entity\User\Permission;
use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\Response;

class UpdatePermissionControllerTest extends Base
{
    /**
     * @test
     */
    public function updateErrorValidation(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['name' => ''];
        $this->client->request('PUT', '/permissions/1', [], [], [], json_encode($data));
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
        $data = ['name' => 'Permission 99', 'description' => 'Permission 99 description', 'status' => Permission::$STATUS_ACTIVE];
        $this->client->request('PUT', '/permissions/123', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Permission doesn\'t exist.', $response->error);
    }

    /**
     * @test
     */
    public function createAlreadyIssetName(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['name' => 'Permission 2', 'description' => 'Permission 2 description', 'status' => Permission::$STATUS_ACTIVE];
        $this->client->request('PUT', '/permissions/1', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Permission who has this name already exists.', $response->error);
    }

    /**
     * @test
     */
    public function updateSuccessfull(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['name' => $name = 'Permission 99', 'description' => 'Permission 99 description', 'status' => Permission::$STATUS_ACTIVE];
        $this->client->request('PUT', '/permissions/1', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Updated successful', $response->message);

        $this->revertChanges($name);
    }

    private function revertChanges($name)
    {
        $permission = $this->doctrine->getRepository(Permission::class)->findOneBy(['name' => $name]);
        $permission->setName('Permission 1');
        $permission->setDescription('Permission 1 description');
        $this->manager->persist($permission);
        $this->manager->flush();
    }
}

<?php

namespace App\Tests\Functional\Controller\Permission;

use App\Entity\User\Permission;
use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeletePermissionControllerTest extends Base
{
    /**
     * @test
     */
    public function deleteNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('DELETE', '/permissions/239');
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Permission doesn\'t exist.', $response->error);
    }

    /**
     * @test
     */
    public function deleteSuccess(): void
    {
        $id = $this->createPermission();

        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('DELETE', '/permissions/' . $id);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Permission was deleted', $response->message);
    }

    private function createPermission(): int
    {
        $permission = new Permission();
        $permission->setName('Permission 99');
        $permission->setDescription('Permission 99 description');
        $permission->setStatus(Permission::$STATUS_ACTIVE);
        $permission->onPrePersist()->onPreUpdate();
        $this->manager->persist($permission);
        $this->manager->flush();
        return $permission->getId();
    }
}

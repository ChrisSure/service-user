<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Entity\User\Permission;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;

class AssignPermissionUserControllerTest extends Base
{
    /**
     * @test
     */
    public function assignUserNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('GET', '/users/199/assign-permission/1');
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_NOT_FOUND);
        $this->assertEquals($response->error, 'User doesn\'t exist.');
    }

    /**
     * @test
     */
    public function assignPermissionNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('GET', '/users/1/assign-permission/199');
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_NOT_FOUND);
        $this->assertEquals($response->error, 'Permission doesn\'t exist.');
    }

    /**
     * @test
     */
    public function assignSuccess(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('GET', '/users/1/assign-permission/2');
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_OK);
        $this->assertEquals($response->message, 'Permission was assigned successfull');

        $this->revertChanges();
    }

    private function revertChanges()
    {
        $user = $this->doctrine->getRepository(User::class)->get(1);
        $permission = $this->doctrine->getRepository(Permission::class)->get(2);
        $user->removePermission($permission);
        $this->manager->persist($user);
        $this->manager->flush();
    }

}

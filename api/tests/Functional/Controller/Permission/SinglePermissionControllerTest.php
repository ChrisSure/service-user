<?php

namespace App\Tests\Functional\Controller\Permission;

use App\Entity\User\User;
use App\Tests\Functional\Base;

class SinglePermissionControllerTest extends Base
{
    /**
     * @test
     */
    public function singleSuccess(): void
    {
        $this->signIn(User::$ROLE_ADMIN);

        $this->client->request('GET', '/permissions/' . $id = 1);

        $response = json_decode($this->client->getResponse()->getContent());

        $permission = $response->permission;

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($permission->id, $id);
        $this->assertEquals("Permission 1", $permission->name);

    }

    /**
     * @test
     */
    public function singleNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);

        $this->client->request('GET', '/permissions/' . $id = 19999);

        $response = json_decode($this->client->getResponse()->getContent());
        $error = $response->error;

        $this->assertTrue($this->client->getResponse()->isNotFound());
        $this->assertEquals("Permission doesn't exist.", $error);

    }
}

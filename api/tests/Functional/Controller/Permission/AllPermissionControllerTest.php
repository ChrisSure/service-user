<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Tests\Functional\Base;

class AllPermissionControllerTest extends Base
{
    /**
     * @test
     */
    public function all(): void
    {
        $this->signIn(User::$ROLE_ADMIN);

        $this->client->request('GET', '/permissions?name=&status=&page=1');

        $this->client->followRedirect();
        $response = json_decode($this->client->getResponse()->getContent());

        $permissions = $response->permissions[0];

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals("Permission 1", $permissions->name);
        $this->assertEquals("active", $permissions->status);
    }
}

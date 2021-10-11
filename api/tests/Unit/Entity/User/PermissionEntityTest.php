<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\Permission;
use App\Tests\Unit\Base;

class PermissionEntityTest extends Base
{
    /**
     * @test
     */
    public function checkEntity(): void
    {
        $permission = new Permission();
        $permission->setName($name = $this->faker->title);
        $permission->setStatus($status = $permission::$STATUS_ACTIVE);

        $this->assertEquals($name, $permission->getName());
        $this->assertEquals($status, $permission->getStatus());
    }
}

<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\Permission;
use App\Entity\User\User;
use App\Tests\Unit\Base;
use Doctrine\Common\Collections\ArrayCollection;

class UserEntityTest extends Base
{
    /**
     * @test
     */
    public function checkEntity(): void
    {
        $permission = new Permission();
        $permission->setName($this->faker->title);
        $permission->setStatus($permission::$STATUS_ACTIVE);

        $user = new User();
        $user->setEmail($email = $this->faker->email);
        $user->setRoles($role = User::$ROLE_USER);
        $user->setPasswordHash($password = $this->faker->password);
        $user->setStatus($status = $user::$STATUS_ACTIVE);
        $user->setToken($token = $this->faker->sentence);
        $user->setPermission($permission);

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals([$role], $user->getRoles());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($status, $user->getStatus());
        $this->assertEquals($token, $user->getToken());
        $this->assertEquals($permission->getName(), $user->getPermission()[0]->getName());

        $this->assertTrue($user->getSocial() instanceof ArrayCollection);
    }
}

<?php

namespace App\Tests\Unit\Validation\User;

use App\Entity\User\User;
use App\Tests\Unit\Base;
use App\Validation\User\PermissionValidation;

class PermissionValidationTest extends Base
{
    /**
     * @test
     */
    public function successValidate(): void
    {
        $validate = new PermissionValidation();
        $data = ['name' => $this->faker->title, 'description' => $this->faker->text, 'status' => User::$STATUS_NEW];
        $result = $validate->validate($data);

        $this->assertEquals(0, $result->count());
    }

    /**
     * @test
     */
    public function failureValidate(): void
    {
        $validate = new PermissionValidation();
        $data = ['name' => ''];
        $result = $validate->validate($data);

        $this->assertEquals(4, $result->count());
    }
}

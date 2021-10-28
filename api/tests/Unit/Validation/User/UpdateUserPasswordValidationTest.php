<?php

namespace App\Tests\Unit\Validation\User;

use App\Entity\User\User;
use App\Tests\Unit\Base;
use App\Validation\User\UpdateUserPasswordValidation;
use App\Validation\User\UpdateUserValidation;

class UpdateUserPasswordValidationTest extends Base
{
    /**
     * @test
     */
    public function successValidate(): void
    {
        $validate = new UpdateUserPasswordValidation();
        $data = ['password' => $this->faker->password];
        $result = $validate->validate($data);

        $this->assertEquals(0, $result->count());
    }

    /**
     * @test
     */
    public function failureValidate(): void
    {
        $validate = new UpdateUserPasswordValidation();
        $data = ['password' => ''];
        $result = $validate->validate($data);

        $this->assertEquals(2, $result->count());
    }
}

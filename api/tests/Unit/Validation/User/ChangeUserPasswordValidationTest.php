<?php

namespace App\Tests\Unit\Validation\User;

use App\Tests\Unit\Base;
use App\Validation\User\ChangeUserPasswordValidation;

class ChangeUserPasswordValidationTest extends Base
{
    /**
     * @test
     */
    public function successValidate(): void
    {
        $validate = new ChangeUserPasswordValidation();
        $data = ['password' => $this->faker->password];
        $result = $validate->validate($data);

        $this->assertEquals(0, $result->count());
    }

    /**
     * @test
     */
    public function failureValidate(): void
    {
        $validate = new ChangeUserPasswordValidation();
        $data = ['password' => ''];
        $result = $validate->validate($data);

        $this->assertEquals(2, $result->count());
    }
}

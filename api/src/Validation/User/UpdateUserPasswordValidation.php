<?php

namespace App\Validation\User;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * Class UpdateUserPasswordValidation
 * @package App\Validation\User
 */
class UpdateUserPasswordValidation
{
    /**
     * Validator for change password
     * @param array $data
     * @return ConstraintViolationListInterface
     */
    public function validate(array $data): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(
            [
                'password' =>
                    [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 2])
                    ],
            ]
        );
        return $validator->validate($data, $constraint);
    }
}

<?php

namespace App\Validation\User;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * Class PermissionValidation
 * @package App\Validation\User
 */
class PermissionValidation
{
    /**
     * Validor for login
     * @param array $data
     * @return ConstraintViolationListInterface
     */
    public function validate(array $data): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(
            [
                'name' =>
                    [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 2])
                    ],
                'status' =>
                    [
                        new Assert\NotBlank()
                    ]

            ]
        );
        return $validator->validate($data, $constraint);
    }
}

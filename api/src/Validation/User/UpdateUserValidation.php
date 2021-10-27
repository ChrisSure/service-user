<?php

namespace App\Validation\User;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * Class UserAuthValidation
 * @package App\Validation\User
 */
class UpdateUserValidation
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
                'email' =>
                    [
                        new Assert\NotBlank(),
                        new Assert\Email()
                    ],
                'roles' =>
                    [
                        new Assert\NotBlank()
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

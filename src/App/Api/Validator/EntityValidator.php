<?php

namespace App\Api\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class EntityValidator
{
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(
        $value,
        $constraints = null,
        $groups = null
    ): ConstraintViolationListInterface {
        return $this->validator->validate($value, $constraints, $groups);
    }
}

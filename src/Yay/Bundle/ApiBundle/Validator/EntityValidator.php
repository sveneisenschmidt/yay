<?php

namespace Yay\Bundle\ApiBundle\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class EntityValidator
{
    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param      $value
     * @param null $constraints
     * @param null $groups
     *
     * @return ConstraintViolationListInterface
     */
    public function validate($value, $constraints = null, $groups = null): ConstraintViolationListInterface
    {
        return $this->validator->validate($value, $constraints, $groups);
    }
}

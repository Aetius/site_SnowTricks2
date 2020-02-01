<?php

namespace App\Validator;

use App\Repository\TrickGroupRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TrickGroupNotExistValidator extends ConstraintValidator
{

    /**
     * @var TrickGroupRepository
     */
    private $repository;

    public function __construct(TrickGroupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\TrickGroupNotExist */

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->repository->findOneBy(['name' => $value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('value', $value)
                ->addViolation();
        }
    }

}
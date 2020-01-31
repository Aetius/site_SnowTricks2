<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LoginNotExistValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\LoginNotExist */

        if (null === $value || '' === $value) {
            return;
        }

        if ($result = $this->repository->findOneBy(['login' => $value])) {
            if (($result->getId() !=$this->context->getObject()->id)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('value', $value)
                    ->addViolation();
            }
        }
    }
}

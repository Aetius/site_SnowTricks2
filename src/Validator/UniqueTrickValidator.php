<?php

namespace App\Validator;

use App\Repository\TrickRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueTrickValidator extends ConstraintValidator
{

    /**
     * @var TrickRepository
     */
    private $repository;

    public function __construct(TrickRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\UniqueTrick */

        if (null === $value || '' === $value) {
            return;
        }

        if ( $result = $this->repository->findOneBy(['title' => $value])){
            if (($result->getId() !=$this->context->getObject()->id)){
                $this->context->buildViolation($constraint->message)
                    ->setParameter('value', $value)
                    ->addViolation();
            }
        }
    }
}

<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PicturesCollectionNotNullValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\PicturesCollectionNotNull */

        if (null === $value || '' === $value) {
            return;
        }
//dd($value);
     if (empty($value) || ($value == null) )
     {
         $this->context->buildViolation($constraint->message)
             ->setParameter('{{ value }}', "image")
             ->addViolation();
     }

    }
}

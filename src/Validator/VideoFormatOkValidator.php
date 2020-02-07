<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VideoFormatOkValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\VideoFormatOk */

        if (!is_null($this->context->getObject()->id) && is_null($value["required"])){
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        foreach ($value as $key => $url)
        {
            if (preg_match('/youtube|youtu.be|dailymotion|dai.ly/', $url) != true)
            {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('value', $url)
                    ->atPath("videos")
                    ->addViolation();
            }

        }

    }
}

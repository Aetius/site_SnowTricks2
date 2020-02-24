<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VideoFormatOkValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\VideoFormatOk */

        if (null === $value || '' === $value) {
            return;
        }

        if (preg_match('#^https://youtu.be/#', $value)) {
            return;
        }
        if (preg_match('#^https://www.youtube.com/#', $value)) {
            return;
        }
        if (preg_match('#^https://dai.ly/#', $value)) {
            return;
        }

        if (preg_match('#^https://www.dailymotion.com/#', $value)) {
            return;
        }

        if (preg_match('#^<iframe#', $value)) {
            $array = explode(" ", $value);
            foreach ($array as $value) {
                if (strstr($value, 'src="https://www.youtube.com/embed/')) {
                    return;
                }
                if (strstr($value, 'src="https://www.dailymotion.com/embed/')) {
                    return;
                }
            }
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }

}

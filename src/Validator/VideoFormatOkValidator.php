<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VideoFormatOkValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\VideoFormatOk */

        /*    if (!is_null($this->context->getObject()->id) && is_null($value["required"])){
                return;
            }*/

        if (null === $value || '' === $value) {
            return;
        }

        foreach ($value as $key => $url) {
            if (preg_match('#^https://youtu.be/#', $url)) {
                return;
            }
            if (preg_match('#^https://www.youtube.com/#', $url)) {
                return;
            }
            if (preg_match('#^https://dai.ly/#', $url)) {
                return;
            }

            if (preg_match('#^https://www.dailymotion.com/#', $url)) {
                return;
            }
            if (preg_match('#^https://www.dailymotion.com/#', $url)) {
                return;
            }

            if (preg_match('#^<iframe#', $url)) {
                $array = explode(" ", $url);
                foreach ($array as $value) {
                    if (strstr($value, 'src="https://www.youtube.com/embed/')) {
                        return;
                    }
                    if (strstr($value, 'src="https://www.dailymotion.com/embed/')) {
                        return;
                    }
                }
            }
            if (($this->context->getObject()->id !== null) AND ($value['required'] === null) ){
                return;
            }

            $this->context->buildViolation($constraint->message)
                ->setParameter('value', 'video')
                ->atPath("videos")
                ->addViolation();
        }

    }
}

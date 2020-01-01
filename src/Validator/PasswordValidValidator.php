<?php

namespace App\Validator;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordValidValidator extends ConstraintValidator
{

    /**
     * @var Security
     */
    private $user;

    /**
     * PasswordValidValidator constructor.
     * @param Security $user
     */
    public function __construct(Security $user)
    {

        $this->user = $user;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\PasswordValid */

        if (null === $value || '' === $value) {
            return;
        }

        if (!password_verify($value, $this->user->getUser()->getPassword())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

    }
}

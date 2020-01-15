<?php


namespace App\Form\User\DTO;


use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class EditUserDTO extends UserDTO
{

    /**
     * @Assert\Callback
     */
    public function changingPassword(ExecutionContextInterface $context)
    {
       if ($this->password !== null) {
            $context->getMetadata()->addPropertyConstraint(
               'currentPassword',
               new UserPassword());
        }
    }

}
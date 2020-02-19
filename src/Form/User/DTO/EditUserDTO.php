<?php


namespace App\Form\User\DTO;


use App\Entity\User;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class EditUserDTO extends UserDTO
{

    static function createFromUser(User $user): EditUserDTO
    {
        $dto = new EditUserDTO();
        $dto->login = $user->getLogin();
        $dto->emailUser = $user->getEmail();
        $dto->id = $user->getId();
        return $dto;
    }


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
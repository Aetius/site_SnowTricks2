<?php


namespace App\Form\User\DTO;


use App\Validator\LoginNotExist;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    /**
     * @Assert\Length(min = 3)
     * @LoginNotExist()
     */
    public $login;


    public $currentPassword;


    public $password;

    /**
     * @Assert\Email()
     */
    public $emailUser;

    /**
     **/
    public $isActivate;

    public $roles;

    /**
     * @Assert\Image(maxSize="3M")
     */
    public $picture;

    public $id;


}
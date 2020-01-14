<?php


namespace App\Form\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class EditUserDTO
{
    /**
     * @Assert\Email()
     */
    public $login;
}
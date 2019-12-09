<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EncodePassword {

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct()
    {

    }

    public function password($pass)
    {
        $user = new User();
        $user->setPassword($this->encoder->encodePassword($user, $pass));
    }
}
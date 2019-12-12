<?php

namespace App\Security;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncode
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this -> encoder = $encoder;
    }

    public function run($user, $password)
    {
        return $this->encoder->encodePassword($user, $password);
    }

}
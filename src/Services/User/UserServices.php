<?php


namespace App\Services\User;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserServices
{

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var User
     */
    protected $user;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $this -> encoder = $encoder;
        $this -> entityManager = $entityManager;
        $this->user = new User();
    }

}
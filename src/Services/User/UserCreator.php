<?php


namespace App\Services\User;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreator extends UserServices
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, UserRepository $repository)
    {
        parent::__construct($encoder, $entityManager);
        $this->repository = $repository;
    }

    public function create($userData)
    {
        $this->user
            ->setLogin($userData['login'])
            ->setPassword($this->encoder->encodePassword($this->user, $userData['password']))
            ->setEmailUser($userData['emailUser']);
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }


}
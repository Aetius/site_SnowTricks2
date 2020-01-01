<?php


namespace App\Services\User;


use App\Entity\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEditor extends UserServices
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, UserRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($encoder, $entityManager);
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

    public function update($userData, $user, $email)
    {
        $this->user = $user;
        if (!is_null($userData['login'])) {
            $this->user->setLogin($userData['login']);
            $returnUser['login']=$userData['login'];
        }
        if (!is_null($userData['password'])) {
            $this->user->setPassword($this->encoder->encodePassword($this->user, $userData['password']));
        }
        if (!is_null($userData['emailUser'])) {
            $this->user->removeEmail($email);
            $this->user->setEmailUser($userData['emailUser']);
            $returnUser['email']=$userData['emailUser'];
        }


        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
        return $returnUser;

    }


}
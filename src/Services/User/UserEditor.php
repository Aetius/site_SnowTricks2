<?php


namespace App\Services\User;


use App\Notification\ContactNotification;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEditor extends UserServices
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var ContactNotification
     */
    private $notification;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, UserRepository $repository, ContactNotification $notification)
    {
        parent::__construct($encoder, $entityManager);
        $this->repository = $repository;
        $this->notification = $notification;
    }

    /**
     * @param $userData
     */
    public function create($userData)
    {
        $this->user
            ->setLogin($userData['login'])
            ->setPassword($this->encoder->encodePassword($this->user, $userData['password']))
            ->setEmailUser($userData['emailUser']);

        $this->notification->confirmEmail();

        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }

    /**
     * @param $userData
     * @param $user
     * @param $email
     * @return array
     */
    public function update($userData, $user, $email)
    {
        $returnUser = [];
        $this->user = $user;
        if (!is_null($userData['login'])) {
            $this->user->setLogin($userData['login']);
            $returnUser['login'] = $userData['login'];
        }
        if (!is_null($userData['password'])) {
            $this->user->setPassword($this->encoder->encodePassword($this->user, $userData['password']));
        }
        if (!is_null($userData['emailUser'])) {
            $this->user->removeEmail($email);
            $this->user->setEmailUser($userData['emailUser']);
            $returnUser['email'] = $userData['emailUser'];
            $this->notification->confirmEmail($userData['emailUser'], $this->user->getLogin(), $this->user->getId() );
        }

        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
        return $returnUser;
    }


}
<?php

namespace App\Services\Email;

use App\Repository\EmailRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class Email
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EmailRepository
     */
    private $emailRepository;


    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository,
                                EmailRepository $emailRepository)
    {

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->emailRepository = $emailRepository;
    }

    public function validationEmail($email, $login, $pass)
    {
        $user = $this->userRepository->findOneBy(['login' => $login]);
        $emailUser = $this->emailRepository->findOneBy(['email' => $email]);
        $userId = $user->getId().'emailConfirm';
        $dateCreationEmail = $emailUser->getDateCreation();
        $limitDateConfirmation = new \DateTime('-7 days');

        if (($user == null) OR ($emailUser == null)){
            return false;
        }
        if ($emailUser->getUser()->getId() === $user->getId()) {
            if ((password_verify($userId, $pass)) && ($dateCreationEmail > $limitDateConfirmation)) {
                $emailUser->setIsVerified(true);
                $this->entityManager->persist($emailUser);
                $this->entityManager->flush();
                return true;
            }
        }
    }

    public function lostPassword($user)
    {
        $limitDateConfirmation = new \DateTime('-7 days');
        if (($user->getTokenResetPassword()->getDateCreation()) > $limitDateConfirmation){
           return true;
        };

        /*
        $user = $this->userRepository->findOneBy(['login' => $login]);
        $emailUser = $this->emailRepository->findOneBy(['email' => $email]);
        $userId = $user->getId().'resetPass';
        $lastUpdate = $user->getLastUpdate();
        $limitDateConfirmation = new \DateTime('-7 days');

        if (empty($user) OR empty($emailUser)){
            return false;
        }

        if ($emailUser->getUser()->getId() === $user->getId()) {
            if ((password_verify($userId, $pass)) && ($lastUpdate > $limitDateConfirmation)) {
                return true;
            }
        }*/
    }


    /////voir pour compiler lostPassword et validationEMail => fonctions ayant beaucoup de similitudes
}
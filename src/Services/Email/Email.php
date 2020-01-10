<?php

namespace App\Services\Email;

use App\Entity\EmailLinkToken;
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

    public function validationEmail($user)
    {
        $limitDateConfirmation = new \DateTime('-7 days');
        if (($limitDateConfirmation < $user->getEmail()->getDateCreation()) &&
            ($user->getEmailLinkToken()->getAction()['0'] === EmailLinkToken::ACTION[0])){
            $user->getEmail()->setIsVerified(true);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;
        }
       /* dd($user->getEmail()->getDateCreation());

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
        }*/
    }

    public function lostPassword($user)
    {
        $limitDateConfirmation = new \DateTime('-7 days');
        if ((($user->getEmailLinkToken()->getDateCreation()) > $limitDateConfirmation) &&
            ($user->getEmailLinkToken()->getAction()['0'] === EmailLinkToken::ACTION[1])){
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
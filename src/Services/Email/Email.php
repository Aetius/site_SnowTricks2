<?php

namespace App\Services\Email;

use App\Entity\EmailLinkToken;
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


    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function validationEmail($user)
    {
        $limitDateConfirmation = new \DateTime('-7 days');

        if (($limitDateConfirmation < $user->getEmailLinkToken()->getDateCreation()) &&
            ($user->getEmailLinkToken()->getAction() === EmailLinkToken::ACTION_UPDATE_EMAIL)){
            $user->setEmailIsValid(true);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;
        }
    }

    public function lostPassword($user)
    {
        $limitDateConfirmation = new \DateTime('-7 days');
        if ((($user->getEmailLinkToken()->getDateCreation()) > $limitDateConfirmation) &&
            ($user->getEmailLinkToken()->getAction() === EmailLinkToken::ACTION_RESET_PASSWORD)){
           return true;
        };


    }


}
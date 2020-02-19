<?php

namespace App\Services\Email;

use App\Entity\EmailLinkToken;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class Mailer
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

    public function validationEmail(User $user)
    {
        $limitDateConfirmation = new \DateTime('-7 days');

        if (($limitDateConfirmation < $user->getEmailLinkToken()->getDateCreation()) &&
            ($user->getEmailLinkToken()->getAction() === EmailLinkToken::ACTION_UPDATE_EMAIL)) {
            $user->setEmailIsValid(true);
            $user->setRole("ROLE_EDITOR");
            return true;
        }
    }

    public function lostPassword(User $user)
    {
        $limitDateConfirmation = new \DateTime('-7 days');
        if ((($user->getEmailLinkToken()->getDateCreation()) > $limitDateConfirmation) &&
            ($user->getEmailLinkToken()->getAction() === EmailLinkToken::ACTION_RESET_PASSWORD)) {
            return true;
        };
    }

    public function save(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


}
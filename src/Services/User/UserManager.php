<?php


namespace App\Services\User;


use App\Entity\EmailLinkToken;
use App\Entity\User;
use App\Form\User\DTO\UserDTO;
use App\Notification\EmailNotification;
use App\Repository\UserRepository;
use App\Security\TokenEmail;
use App\Services\Upload\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EmailNotification
     */
    private $notification;
    /**
     * @var TokenEmail
     */
    private $token;
    /**
     * @var Uploader
     */
    private $uploadService;
    /**
     * @var User
     */
    private $user;

    /**
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $repository
     * @param EmailNotification $notification
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager,
                                UserRepository $repository, EmailNotification $notification, TokenEmail $token,
                                Uploader $uploader)
    {
        $this->repository = $repository;
        $this->notification = $notification;
        $this->token = $token;
        $this->uploadService = $uploader;
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
        $this->user = new User;
    }

    /**
     * @param UserDTO $userData
     */
    public function create(UserDTO $userDTO)
    {
        $this->user
            ->setLogin($userDTO->login)
            ->setPassword($this->encoder->encodePassword($this->user, $userDTO->password))
            ->setEmail($userDTO->emailUser)
            ->setUpdatedAt(new \DateTime('now'))
            ->setPicture($this->uploadService->uploadUserImage($userDTO->picture));

        $this->token->create($this->user, EmailLinkToken::ACTION_UPDATE_EMAIL);

        return ($this->user);

    }

    /**
     * @param User $user
     * @param UserDTO $userDTO
     * @return User
     */
    public function update(User $user, UserDTO $userDTO)
    {
        $this->user = $user;
        if (!empty($userDTO->login)) {
            $this->user->setLogin($userDTO->login);
        }
        if (!empty($userDTO->password)) {
            $this->user->setPassword($this->encoder->encodePassword($this->user, $userDTO->password));
        }
        if (!empty($userDTO->emailUser)) {
            $this->user->setEmail($userDTO->emailUser);
            $this->user->setEmailIsValid(true);
            $this->token->create($user, EmailLinkToken::ACTION_UPDATE_EMAIL);
            $this->notification->confirmEmail($this->user);
        }
        if (!empty($userDTO->picture)) {
            $this->user->setPicture($this->uploadService->uploadUserImage($userDTO->picture));
        }

        $this->user->setUpdatedAt(new \DateTime('now'));

        return $this->user;
    }


    public function save(User $user)
    {
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }


    /**
     * @param User $user
     */
    public function resetPassword(User $user)
    {
        $this->user = $user;
        $this->token->create($this->user, EmailLinkToken::ACTION_RESET_PASSWORD);
        $this->notification->lostPassword($this->user);

        $this->user->setUpdatedAt(new \DateTime('now'));
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }

    public function adminEditUser(array $users)
    {
        $this->entityManager->flush();
    }


}
<?php


namespace App\Services\User;


use App\Entity\EmailLinkToken;
use App\Entity\User;
use App\Form\User\DTO\UserDTO;
use App\Notification\EmailNotification;
use App\Repository\UserRepository;
use App\Security\TokenEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditorService extends UserService
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
     * EditorService constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $repository
     * @param EmailNotification $notification
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager,
                                UserRepository $repository, EmailNotification $notification, TokenEmail $token)
    {
        parent::__construct($encoder, $entityManager);
        $this->repository = $repository;
        $this->notification = $notification;
        $this->token = $token;
    }

    /**
     * @param array $userData
     */
    public function create(array $userData)
    {
        $this->user
            ->setLogin($userData['login'])
            ->setPassword($this->encoder->encodePassword($this->user, $userData['password']))
            ->setEmail($userData['emailUser']);
            ;

        $this->user->setUpdatedat(new \DateTime('now'));
        $this->token->create($this->user, 0);
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();

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

        $this->user->setUpdatedat(new \DateTime('now'));
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
        return $this->user;
    }

    public function resetPassword(User $user)
    {
        $this->user = $user;
        $this->token->create($this->user, EmailLinkToken::ACTION_RESET_PASSWORD);
        $this->notification->lostPassword($this->user);

        $this->user->setUpdatedat(new \DateTime('now'));
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }


}
<?php


namespace App\Services\User;


use App\Entity\Email;
use App\Entity\User;
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
        $email = new Email;
        $email->setEmail($userData['emailUser']);
        $this->user
            ->setLogin($userData['login'])
            ->setPassword($this->encoder->encodePassword($this->user, $userData['password']))
            ->setEmail($email)
            ;

        $this->user->setLastUpdate(new \DateTime('now'));
        $this->token->create($this->user, 0);
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();

        return ($this->user);

    }

    /**
     * @param User $user
     * @param array $formFields
     * @return array
     */
    public function update(User $user, array $formFields)
    {
        $returnUser = [];
        $this->user = $user;
        if (!empty($formFields['login'])) {
            $this->user->setLogin($formFields['login']);
            $returnUser['login'] = $formFields['login'];
        }
        if (!empty($formFields['password'])) {
            $this->user->setPassword($this->encoder->encodePassword($this->user, $formFields['password']));
        }
        if (!empty($formFields['emailUser'])) {
            $this->user->getEmail()->setEmail($formFields['emailUser']);
            $this->user->getEmail()->setIsVerified(0);
            $returnUser['email'] = $formFields['emailUser'];
            $this->token->create($user, 0);
            $this->notification->confirmEmail($this->user);
        }

        $this->user->setLastUpdate(new \DateTime('now'));
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
        return $returnUser;
    }

    public function resetPassword(User $user)
    {
        $this->user = $user;
        $this->token->create($this->user, 1);
        $this->notification->lostPassword($this->user);

        $this->user->setLastUpdate(new \DateTime('now'));
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }


}
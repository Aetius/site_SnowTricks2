<?php


namespace App\Services\User;


use App\Entity\Email;
use App\Entity\User;
use App\Notification\EmailNotification;
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
     * @var EmailNotification
     */
    private $notification;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, UserRepository $repository, EmailNotification $notification)
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
     * @param User $user
     * @param Email $email
     * @param array|null $formFields
     * @return array
     */
    public function update(User $user, Email $email=null, array $formFields=null)
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
            $this->user->removeEmail($email);
            $this->user->setEmailUser($formFields['emailUser']);
            $returnUser['email'] = $formFields['emailUser'];
            $this->notification->confirmEmail($formFields['emailUser'], $this->user->getLogin(), $this->user->getId() );
        }


        $this->user->setLastUpdate(new \DateTime('now'));
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
        return $returnUser;
    }




}
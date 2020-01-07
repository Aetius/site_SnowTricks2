<?php

namespace App\Services\User;

use App\Entity\TokenResetPassword;
use App\Entity\User;
use App\Notification\EmailNotification;
use App\Repository\TokenResetPasswordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPassword
{

    /**
     * @var TokenResetPasswordRepository
     */
    private $tokenResetPasswordRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;



    public function __construct(EntityManagerInterface $entityManager,
                                TokenResetPasswordRepository $tokenResetPasswordRepository)
    {
        $this->tokenResetPasswordRepository = $tokenResetPasswordRepository;
        $this->entityManager = $entityManager;
    }

    public function reset(User $user)
    {
        if ($tokenIn = $this->tokenResetPasswordRepository->findBy(['user'=>$user->getId()])){
            foreach ($tokenIn as $item){
                $this->entityManager->remove($item);
            }
        }
        $token = md5(random_bytes(10));
        $tokenResetPassword = new TokenResetPassword();
        $tokenResetPassword->setToken($token);
       $user->setTokenResetPassword($tokenResetPassword);


      /*  if ($this->repository->findOneBy(['user'=>$user->getId()])){
            dd('coucou');
        }*/

        $this->entityManager->persist($user);
        $this->entityManager->flush();;

        return $token;

    }
}
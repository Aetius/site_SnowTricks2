<?php

namespace App\Services\User;

use Doctrine\ORM\EntityManagerInterface;

class AdminService
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function update($user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update3($users){

       // $this->entityManager->persist($users);
        $this->entityManager->flush();
    }

    public function update2($user, $form)
    {
        $user->setRoles([$form['roles']]);
        $user->setIsActivate($form['isActivate']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
<?php


namespace App\Tests\Repository;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait UserRepositoryTest
{
    public function defineUser(KernelBrowser $client)
    {
        $user = $this->findLastUser($client);

        /** @var User $user */
        if ($user !== null){
            $name = explode(" ", $user->getLogin());
           return ($name[0]." ".($name[1]+1));
        }
        return  'Sim';

    }

    public function findLastUser(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $users = $entityManager
            ->getRepository(User::class)
            ->findLast();
       return $users[0];
    }

}
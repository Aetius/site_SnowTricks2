<?php


namespace App\Tests\Repository;


use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait TrickRepositoryTest
{
    public function defineTitle(KernelBrowser $client)
    {
        $trick = $this->findLastTrick($client);

        /** @var Trick $trick */
        if ($trick !== null){
            $name = explode(" ", $trick->getTitle());
           return ($name[0]." ".($name[1]+1));
        }
        return  'Raley 21';

    }

    public function findLastTrick(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $tricks = $entityManager
            ->getRepository(Trick::class)
            ->findLast();
       return $tricks[0];
    }

}
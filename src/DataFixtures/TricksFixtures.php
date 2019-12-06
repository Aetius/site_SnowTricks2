<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TricksFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for($i = 0; $i < 20; $i ++){
            $tricks = new Trick();
            $tricks->setDescription('demo');
            $tricks->setTitle('title '.$i);
            $tricks->setDescription('super contenu');
            $tricks->setPublicated(false);

            $manager->persist($tricks);

            $manager->flush();
        }
    }
}

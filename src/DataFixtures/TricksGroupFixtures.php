<?php

namespace App\DataFixtures;

use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TricksGroupFixtures extends Fixture
{

    private $group =
        ["Grab",
        "Rotation",
        "Flip",
        "Rotation désaxée",
        "Slide",
        "One foot",
        "Old shool"];



    public function load(ObjectManager $manager)
    {
        foreach ($this->group as $groupName){
            $group = new TrickGroup();
            $group->setName($groupName);
            $manager->persist($group);
            $manager->flush();
        }

    }
}

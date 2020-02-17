<?php

namespace App\DataFixtures;

use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TricksGroupFixtures extends Fixture
{

    const NB_GROUPS = 7;

    private $group =
        [
            "Grab",
            "Rotation",
            "Flip",
            "Rotation désaxée",
            "Slide",
            "One foot",
            "Old shool"
        ];


    public function load(ObjectManager $manager)
    {
        foreach ($this->group as $key => $groupName) {
            $group = new TrickGroup();
            $group->setName($groupName);

            $this->addReference('group'.$key, $group);

            $manager->persist($group);
            $manager->flush();
        }

    }
}

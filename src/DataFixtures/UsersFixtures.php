<?php

namespace App\DataFixtures;

use App\Entity\Email;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this -> encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <20; $i++){
            $user = new User();
            $user->setLogin('sim'.$i);
            $user->setPassword($this->encoder->encodePassword($user, 'demo'));
            $user->setEmail("sim$i@yahoo.fr");

            $manager->persist($user);
            $manager->flush();
        }
    }
}

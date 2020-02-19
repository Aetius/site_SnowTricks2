<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentsFixtures extends Fixture implements DependentFixtureInterface
{

    private $content;

    const NB_COMMENTS = 200;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::NB_COMMENTS; $i++) {
            $comment = new Comment();
            $comment->setTrick($this->defineTrick());
            $comment->setUser($this->defineUser());
            $comment->setContent($this->getContent());
            $comment->setDateCreation(new \DateTime());
            $manager->persist($comment);
            $manager->flush();
        }
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        $this->content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc et leo rutrum, tempor justo in, 
        elementum lacus. Vivamus dignissim, enim sit amet accumsan egestas, mi velit accumsan ante, vel malesuada lorem 
        leo eu erat. Phasellus pulvinar faucibus sollicitudin. Maecenas sed nulla id metus feugiat pretium vitae vel libero. 
        Proin sodales dapibus nisi, nec sodales nulla eleifend quis. Duis rhoncus iaculis arcu. Suspendisse varius lobortis 
        tortor eu ultrices. Nunc aliquet tempor ante id porta. Morbi scelerisque orci in ullamcorper faucibus. Sed ut dolor 
        et nisi consequat dignissim. ";
        return $this->content;
    }

    /**
     * @return User
     */
    protected function defineUser(): User
    {
        $number = rand(0, UsersFixtures::NB_USERS -1);
        /** @var User $user */
        $user = $this->getReference('user'.$number);
        return $user;
    }

    /**
     * @return Trick
     */
    protected function defineTrick(): Trick
    {
        $number = rand(0, TricksFixtures::NB_TRICKS -1);
        /** @var Trick $trick */
        $trick = $this->getReference('trick'.$number);
        return $trick;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return array(
            UsersFixtures::class,
            TricksFixtures::class
        );
    }
}

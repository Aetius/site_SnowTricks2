<?php

namespace App\DataFixtures;


use App\Entity\Picture;
use App\Entity\User;
use App\Services\Upload\Uploader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var Uploader
     */
    private $uploadService;

    public function __construct(UserPasswordEncoderInterface $encoder, Uploader $uploadService)
    {
        $this -> encoder = $encoder;
        $this->uploadService = $uploadService;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <20; $i++){
            $user = new User();
            $this->addPicture();
            $user->setLogin('sim'.$i)
                ->setPassword($this->encoder->encodePassword($user, 'demo'))
                ->setEmail("sim$i@yahoo.fr")
                ->setPicture($this->addPicture());

            $manager->persist($user);
            $manager->flush();
        }
    }

    private function addPicture(){
        //$picture = new Picture();
        $filesystem = new Filesystem();
        $file = new File(__DIR__.'/images/montagne.jpg');

        $targetPath = sys_get_temp_dir().'/montagne.jpg';
        $filesystem->copy($file, $targetPath, true);

        return $this->uploadService->uploadTrickImage(new File($targetPath));

    }
}

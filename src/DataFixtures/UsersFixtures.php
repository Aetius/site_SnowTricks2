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

    public const USERS = "getUsers";

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var Uploader
     */
    private $uploadService;

    private $picturePath = '/images/montagne.jpg';

    private $targetPath = '/montagne.jpg';



    public function __construct(UserPasswordEncoderInterface $encoder, Uploader $uploadService)
    {
        $this -> encoder = $encoder;
        $this->uploadService = $uploadService;
    }

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i <20; $i++){
            $user = new User();

            $user->setLogin('sim'.$i)
                ->setPassword($this->encoder->encodePassword($user, 'demo'))
                ->setEmail("sim$i@yahoo.fr")
                ->setPicture($this->addPicture());

            $this->addReference("user".$i, $user);

            $manager->persist($user);
            $manager->flush();
        }

    }



    private function addPicture(): string
    {
        $picture = new Picture();

        $filesystem = new Filesystem();
        $file = new File(__DIR__.$this->picturePath);

        $targetPath = sys_get_temp_dir().$this->targetPath;
        $filesystem->copy($file, $targetPath, true);

        $namePicture=$this->uploadService->uploadUserImage(new File($targetPath));
        $picture
            ->setFilename($namePicture)
            ->setSelectedPicture(false);

        return $namePicture;

    }








}

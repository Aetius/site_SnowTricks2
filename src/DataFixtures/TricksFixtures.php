<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Services\Upload\Uploader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\DateTime;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @var Uploader
     */
    private  $uploadService;

   /**
     * @var TricksDatasFixtures
     */
    private $datas;


    public function __construct(Uploader $uploadService, TricksDatasFixtures $datas)
    {
        $this->uploadService = $uploadService;
        $this->datas = $datas;
    }


    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 20; $i ++){
            $trick = new Trick();

            $video = new Video();
            $video->setName($this->datas->getVideo());
            $picture = new Picture();
            $filesystem = new Filesystem();
            $file = new File($this->datas->getPictureTrickPath());
            $targetPath = $this->datas->getPictureTargetPath();

            $filesystem->copy($file, $targetPath, true);

            $namePicture=$this->uploadService->uploadTrickImage(new File($targetPath));
            $picture
                ->setFilename($namePicture)
                ->setSelectedPicture(false);

            $trick
                ->setTitle($this->datas->getTitle()." ".$i)
                ->setDescription($this->datas->description())
                ->setPublicated(true)
                ->setDatePublication(new \DateTime())
                ->addPicture($picture)
                ->setTrickGroup($this->datas->trickGroup())
                ->addVideo($video);

            $manager->persist($trick);
            $manager->flush();

            $this->addReference('trick'.$i, $trick);
        }
    }


    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return array(
            TricksGroupFixtures::class
        );
    }




}

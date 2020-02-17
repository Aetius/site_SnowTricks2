<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\Video;
use App\Services\Upload\Uploader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    const NB_TRICKS = 20;

    /**
     * @var Uploader
     */
    private $uploadService;


    private $title = ["Switch", "180°", "Scarecrow – 360°", "Indy Glide", "Mobydick", "Scarecrow", "HeelSide 360°", "Hellside 360°",
        "Backroll", "Ollie 180° Front – 180°", "Ollie 360°", "Backroll", "Raley", "S-Bend", "Raley", "Vulcan", "backmob",
        "Switch 313 front", "Wakeskate Ollie North", "Shove it", "Boardslide 360° sur rooftop", "360° rewind"];


    private $pictureTrickPath = '/images/montagne.jpg';

    private $pictureTargetPath = '/montagne.jpg';

    private $video = "https://www.youtube.com/embed/V9xuy-rVj9w";


    private $description =
        " Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer bibendum id dolor ut viverra. 
        Aliquam vestibulum nisi eu nisi bibendum convallis. Cras egestas bibendum semper. Mauris ut nibh metus. Nullam 
        risus neque, eleifend in suscipit quis, euismod non purus. Suspendisse eget urna eget quam finibus ultrices. 
        Fusce iaculis orci vel felis egestas accumsan. Nam vel tempus eros. Proin sodales fermentum sem et consectetur. 
        Nunc tincidunt quis lorem eget tincidunt. Nulla volutpat ex a velit cursus bibendum. Fusce sed tristique lacus. 
        Vivamus sed massa in purus dignissim ultrices bibendum cursus turpis. Etiam viverra, massa vel fringilla dapibus, 
        turpis augue tempor eros, eu viverra dui justo sed ligula. Pellentesque sit amet sagittis arcu.
        Suspendisse quis urna convallis ligula aliquet eleifend viverra nec erat. Donec egestas neque iaculis, mattis leo 
        eget, consequat orci. Morbi fringilla blandit orci vitae ultrices. Donec id massa vel ligula pulvinar condimentum. 
        Sed blandit nisl in elit molestie vestibulum. Morbi condimentum vehicula nibh, et tempor augue feugiat ac. Nulla 
        magna ipsum, euismod in ultrices in, feugiat eu odio. Interdum et malesuada fames ac ante ipsum primis in faucibus. 
        Phasellus commodo id velit id viverra. Donec consequat rhoncus suscipit. Pellentesque fringilla tincidunt euismod. 
        Phasellus vel ligula vitae ipsum dignissim facilisis a nec leo. Morbi quis viverra felis. Pellentesque lobortis 
        auctor arcu faucibus pharetra. ";


    public function __construct(Uploader $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::NB_TRICKS; $i++) {

            $trick = new Trick();

            $video = $this->video();
            $picture = $this->picture();

            $trick
                ->setTitle($this->getTitle()." ".$i)
                ->setDescription($this->description)
                ->setPublicated(true)
                ->setDatePublication(new \DateTime())
                ->addPicture($picture)
                ->setTrickGroup($this->trickGroup())
                ->addVideo($video);

            $manager->persist($trick);
            $manager->flush();

            $this->addReference('trick'.$i, $trick);
        }
    }


    public function video()
    {
        $video = new Video();
        $video->setName($this->video);
        return $video;
    }

    public function picture()
    {
        $picture = new Picture();
        $filesystem = new Filesystem();
        $file = new File($this->getPictureTrickPath());
        $targetPath = $this->getPictureTargetPath();
        $filesystem->copy($file, $targetPath, true);
        $namePicture = $this->uploadService->uploadTrickImage(new File($targetPath));
        $picture
            ->setFilename($namePicture)
            ->setSelectedPicture(false);
        return $picture;
    }

    /**
     * @return TrickGroup|object
     */
    public function trickGroup()
    {
        $number = rand(0, TricksGroupFixtures::NB_GROUPS -1);
        $group = $this->getReference('group'.$number);
        /** @var TrickGroup $group */
        return $group;
    }

    /**
     * @return string
     */
    public function getPictureTrickPath(): string
    {
        $path = __DIR__.$this->pictureTrickPath;
        return $path;
    }

    /**
     * @return string
     */
    public function getPictureTargetPath(): string
    {
        $target = (sys_get_temp_dir().$this->pictureTargetPath);
        return $target;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        $number = rand(0, (count($this->title)) - 1);
        return $this->title[$number];
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

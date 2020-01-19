<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Services\Trick\UploadService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\DateTime;

class TricksFixtures extends Fixture
{

    /**
     * @var UploadService
     */
    private UploadService $uploadService;

    private $content = " Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer bibendum id dolor ut viverra. Aliquam vestibulum nisi eu nisi bibendum convallis. Cras egestas bibendum semper. Mauris ut nibh metus. Nullam risus neque, eleifend in suscipit quis, euismod non purus. Suspendisse eget urna eget quam finibus ultrices. Fusce iaculis orci vel felis egestas accumsan. Nam vel tempus eros. Proin sodales fermentum sem et consectetur. Nunc tincidunt quis lorem eget tincidunt. Nulla volutpat ex a velit cursus bibendum. Fusce sed tristique lacus. Vivamus sed massa in purus dignissim ultrices bibendum cursus turpis. Etiam viverra, massa vel fringilla dapibus, turpis augue tempor eros, eu viverra dui justo sed ligula. Pellentesque sit amet sagittis arcu.
Suspendisse quis urna convallis ligula aliquet eleifend viverra nec erat. Donec egestas neque iaculis, mattis leo eget, consequat orci. Morbi fringilla blandit orci vitae ultrices. Donec id massa vel ligula pulvinar condimentum. Sed blandit nisl in elit molestie vestibulum. Morbi condimentum vehicula nibh, et tempor augue feugiat ac. Nulla magna ipsum, euismod in ultrices in, feugiat eu odio. Interdum et malesuada fames ac ante ipsum primis in faucibus. Phasellus commodo id velit id viverra. Donec consequat rhoncus suscipit. Pellentesque fringilla tincidunt euismod. Phasellus vel ligula vitae ipsum dignissim facilisis a nec leo. Morbi quis viverra felis. Pellentesque lobortis auctor arcu faucibus pharetra. ";



    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function load(ObjectManager $manager)
    {

        for($i = 0; $i < 20; $i ++){
            $tricks = new Trick();
            $picture = new Picture();
            $filesystem = new Filesystem();
            $file = new File(__DIR__.'/images/montagne.jpg');

            $targetPath = sys_get_temp_dir().'/'.$file;
            $filesystem->copy($targetPath, $targetPath, true);

            $namePicture=$this->uploadService->uploadTrickImage($file);
            $picture->setFilename($namePicture);

            $tricks
                ->setTitle('Superbe trick '.$i)
                ->setDescription($this->content)
                ->setPublicated(true)
                ->setDatePublication(new \DateTime())
                ->addPicture($picture);


            $manager->persist($tricks);

            $manager->flush();
        }
    }
}

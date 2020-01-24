<?php


namespace App\Services\Trick;


use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\Trick\DTO\CreateDTO;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Context;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EditorService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UploadService
     */
    private $uploadService;

    public function __construct(EntityManagerInterface $entityManager, UploadService $uploadService)
    {
        $this->entityManager = $entityManager;
        $this->uploadService = $uploadService;
    }

    public function create(CreateDTO $createDTO)
    {
        $trick = new Trick();


       /* foreach ($createDTO->pictureFiles as $pictureFile){
            $picture = new Picture();
            $namePicture=$this->uploadService->uploadTrickImage($pictureFile);
            $picture->setFilename($namePicture);
            $trick ->addPicture($picture);
        }*/

       $this->addImage($trick, $createDTO->pictureFiles);
        $trick
            ->setTitle($createDTO->title)
            ->setDescription($createDTO->description);


        $this->entityManager->persist($trick);
        $this->entityManager->flush();

    }

    public function edit (Trick $trick, array $uploadedFile)
    {
        $this->addImage($trick, $uploadedFile);
       /* $picture = new Picture();
        $namePicture=$this->uploadService->uploadTrickImage($uploadedFile);
        $picture->setFilename($namePicture);
        $trick -> addPicture($picture);*/

        $this->entityManager->persist($trick);
        $this->entityManager->flush();
    }

    private function addImage(Trick $trick, array $pictures)
    {
        foreach ($pictures as $pictureFile){
            $picture = new Picture();
            $namePicture=$this->uploadService->uploadTrickImage($pictureFile);
            $picture->setFilename($namePicture);
            $trick ->addPicture($picture);
        }

    }


}
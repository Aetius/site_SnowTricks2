<?php


namespace App\Services\Trick;


use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\Trick\DTO\CreateDTO;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
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
        $picture = new Picture();

        $namePicture=$this->uploadService->uploadTrickImage($createDTO->pictureFiles[0]);
        $picture->setFilename($namePicture);

        $trick
            ->setTitle($createDTO->title)
            ->setDescription($createDTO->description)
            ->addPicture($picture);

        $this->entityManager->persist($trick);
        $this->entityManager->flush();

    }

    public function edit (Trick $trick, UploadedFile $uploadedFile)
    {
        $picture = new Picture();
        $namePicture=$this->uploadService->uploadTrickImage($uploadedFile);
        $picture->setFilename($namePicture);
       $trick -> addPicture($picture);

        $this->entityManager->persist($trick);
        $this->entityManager->flush();
    }
}
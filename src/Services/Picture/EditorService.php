<?php


namespace App\Services\Picture;


use App\Entity\Picture;
use App\Services\Trick\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

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

    /**
     * @var string
     */
    private $uploadsPath;


    public function __construct(string $uploadsPath, EntityManagerInterface $entityManager, UploadService $uploadService)
    {
        $this->entityManager = $entityManager;
        $this->uploadService = $uploadService;
        $this->uploadsPath = $uploadsPath;
    }


    public function delete(Picture $picture)
    {
        $this->deleteFile($picture);
        $this->entityManager->remove($picture);
        $this->entityManager->flush();
    }

    public function edit(Picture $picture, File $file = null)
    {
        if ($file) {
            $this->deleteFile($picture);
            $namePicture = $this->uploadService->uploadTrickImage($file);
            $picture->setFilename($namePicture);
        }
        $this->entityManager->persist($picture);
        $this->entityManager->flush();
    }

    protected function deleteFile(Picture $picture)
    {
        $thumbnailPath = $this->uploadsPath.'/'.UploadService::THUMBNAIL_IMAGE.'/'.$picture->getFilename();
        $imagePath = $this->uploadsPath.'/'.UploadService::ARTICLE_IMAGE.'/'.$picture->getFilename();

        @unlink(($imagePath));
        @unlink($thumbnailPath);
    }


}
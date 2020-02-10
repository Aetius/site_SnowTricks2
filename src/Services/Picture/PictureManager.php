<?php


namespace App\Services\Picture;


use App\Entity\Picture;
use App\Entity\Trick;
use App\Services\Upload\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class PictureManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Uploader
     */
    private $uploadService;

    /**
     * @var string
     */
    private $uploadsPath;


    public function __construct(string $uploadsPath, EntityManagerInterface $entityManager, Uploader $uploadService)
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
        return $this;
    }

    public function save(Picture $picture)
    {
        $this->entityManager->persist($picture);
        $this->entityManager->flush();
    }

    public function add(Trick $trick, array $pictures)
    {
        foreach ($pictures as $pictureFile) {
            $picture = new Picture();
            $namePicture = $this->uploadService->uploadTrickImage($pictureFile);
            $picture->setFilename($namePicture);
            $trick->addPicture($picture);
        }
        return $trick;
    }

    protected function deleteFile(Picture $picture)
    {
        $thumbnailPath = $this->uploadsPath.'/'.Uploader::THUMBNAIL_IMAGE.'/'.$picture->getFilename();
        $imagePath = $this->uploadsPath.'/'.Uploader::ARTICLE_IMAGE.'/'.$picture->getFilename();

        @unlink(($imagePath));
        @unlink($thumbnailPath);
    }


}
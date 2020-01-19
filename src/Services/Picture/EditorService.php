<?php


namespace App\Services\Picture;


use App\Entity\Picture;
use App\Services\Trick\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class EditorService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var string
     */
    private string $uploadsPath;

    public function __construct(string $uploadsPath, EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
        $this->uploadsPath = $uploadsPath;
    }

    public function delete(Picture $picture)
    {
        $thumbnailPath = $this->uploadsPath.'/'.UploadService::THUMBNAIL_IMAGE.'/'.$picture->getFilename();
        $imagePath = $this->uploadsPath.'/'.UploadService::ARTICLE_IMAGE.'/'.$picture->getFilename();

dd($imagePath);

        dd('ici');

            $filesystem = new Filesystem();
            $filesystem->remove($imagePath);
            $filesystem->remove($thumbnailPath);

            $this->entityManager->remove($picture);
            $this->entityManager->flush();


    }

    public function deletePicture($filename)
    {dump(__DIR__);

        dd($filename);

    }

}
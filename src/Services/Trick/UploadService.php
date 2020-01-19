<?php


namespace App\Services\Trick;


use Gedmo\Sluggable\Util\Urlizer;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    const ARTICLE_IMAGE = 'trick_images/1024x768';
    const THUMBNAIL_IMAGE = 'trick_images/thumbnails';

    /**
     * @var string
     */
    private $uploadsPath;
    /**
     * @var RequestStackContext
     */
    private $requestStackContext;

    public function __construct(string $uploadsPath, RequestStackContext $requestStackContext)
    {
        $this->uploadsPath = $uploadsPath;
        $this->requestStackContext = $requestStackContext;
    }

    public function uploadTrickImage(File $uploadedFile)
    {
        $directory = $this->uploadsPath.'/'.self::ARTICLE_IMAGE;

        if ($uploadedFile instanceof UploadedFile){
            $originalNamePicture = $uploadedFile->getClientOriginalName();
        }else{
            $originalNamePicture = $uploadedFile->getFilename();
        }

        $namePicture = Urlizer::urlize(pathinfo($originalNamePicture, PATHINFO_FILENAME)).'-'.uniqid().'.'.$uploadedFile->guessExtension();
//dd($uploadedFile->guessExtension());
        $uploadedFile->move(
            $directory,
            $namePicture);

        $this->resize($directory.'/'.$namePicture);
        return $namePicture;
    }

    public function getPath($path)
    {
        return $this->requestStackContext->getBasePath().'uploads/'.$path;
    }

    public function resize(string $path)
    {
        $imagine = new \Imagine\Gd\Imagine();
        $imagine
            ->open($path)
            ->resize(new Box(1024, 768))
            ->save($path);
    }

    public function resizeThumbnail(string $path)
    {
        $pathThumbnail = $this->uploadsPath.'/'.self::THUMBNAIL_IMAGE.'/'.$path;
        if (!file_exists($this->uploadsPath.'/'.self::THUMBNAIL_IMAGE.'/'.$path)) {
            $imagine = new \Imagine\Gd\Imagine();
            $image = $imagine->open($this->uploadsPath.'/'.self::ARTICLE_IMAGE.'/'.$path);
            $size = new Box(200, 200);
            $mode = ImageInterface::THUMBNAIL_INSET;
            $image
                ->thumbnail($size, $mode)
                ->save($pathThumbnail);
        }
    }
}
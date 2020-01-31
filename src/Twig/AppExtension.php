<?php


namespace App\Twig;


use App\Services\Upload\Uploader;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension implements ServiceSubscriberInterface
{

    private $container;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function  getUploadService(){
        return $this->container
            ->get(Uploader::class);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('uploaded_asset', [$this, 'getUploadedAssetPath']),
            new TwigFunction('uploaded_thumbnail_asset', [$this, 'getUploadedThumbnailAssetPath']),
            new TwigFunction('uploaded_user_asset',[$this, 'getUploadedUserAssetPath'])
        ];
    }

   /* public function getFilters()
    {
        return [
            new TwigFilter('test', [$this, 'getTest'])
        ];
    }*/

    public function getUploadedAssetPath(string $path): string
    {
         return $this->getUploadService()
             ->getPath(Uploader::ARTICLE_IMAGE.'/'.$path);
    }

    public function getUploadedThumbnailAssetPath(string $path)
    {
        return  $this->getUploadService()
            ->resizeThumbnail($path)
            ->getPath(Uploader::THUMBNAIL_IMAGE.'/'.$path);
    }

    public function getUploadedUserAssetPath(string $path)
    {
        return $this->getUploadService()
            ->getPath(Uploader::USER_IMAGE.'/'.$path);
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return [
            Uploader::class,
        ];
    }

    /*public function getTest($path)
    {
        return $this->cache->create($path);

       $imagine = new \Imagine\Gd\Imagine();
        $image = $imagine->open($path);
        $size = new Box(100, 100);
        $mode    = ImageInterface::THUMBNAIL_INSET;
        return( $image
            ->thumbnail($size, $mode)
            ->show('png')
            );
    }*/

}
<?php


namespace App\Twig;


use App\Services\Cache\ImageCache;
use App\Twig\ImageFilter;
use App\Services\Picture\UploadService;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
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
            ->get(UploadService::class);
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
             ->getPath(UploadService::ARTICLE_IMAGE.'/'.$path);
    }

    public function getUploadedThumbnailAssetPath(string $path)
    {
        return  $this->getUploadService()
            ->resizeThumbnail($path)
            ->getPath(UploadService::THUMBNAIL_IMAGE.'/'.$path);
    }

    public function getUploadedUserAssetPath(string $path)
    {
        return $this->getUploadService()
            ->getPath(UploadService::USER_IMAGE.'/'.$path);
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return [
            UploadService::class,
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
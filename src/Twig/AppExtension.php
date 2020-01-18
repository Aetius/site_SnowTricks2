<?php


namespace App\Twig;


use App\Services\Cache\ImageCache;
use App\Twig\ImageFilter;
use App\Services\Trick\UploadService;
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
    /**
     * @var ImageCache
     */
    private $cache;
    /**
     * @var UploadService
     */
    private $uploadService;

    public function __construct(ContainerInterface $container, ImageCache $cache, UploadService $uploadService)
    {
        $this->container = $container;
        $this->cache = $cache;
        $this->uploadService = $uploadService;
    }


    public function getFunctions(): array
    {
        return [
            new TwigFunction('uploaded_asset', [$this, 'getUploadedAssetPath']),
            new TwigFunction('uploaded_thumbnail_asset', [$this, 'getUploadedThumbnailAssetPath'])
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
        return $this->container
            ->get(UploadService::class)
            ->getPath(UploadService::ARTICLE_IMAGE.'/'.$path);
    }

    public function getUploadedThumbnailAssetPath(string $path)
    {
        $this->uploadService->resizeThumbnail($path);

        return $this->container
            ->get(UploadService::class)
            ->getPath(UploadService::THUMBNAIL_IMAGE.'/'.$path);


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
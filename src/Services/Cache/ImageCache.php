<?php


namespace App\Services\Cache;


use App\Services\Trick\UploadService;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageCache
{
    /**
     * @var AdapterInterface
     */
    private $cache;
    /**
     * @var UploadService
     */
    private $uploadService;

    public function __construct(AdapterInterface $cache, UploadService $uploadService )
    {

        $this->cache = $cache;
        $this->uploadService = $uploadService;
    }
//taille max image : 1024 - 768
    public function create2 ($path/*$key, $content*/)
    {
        $key = explode('/', $path);

        $item = $this->cache->getItem($key[2]);
       if (!$item->isHit()){
            $imagine = new \Imagine\Gd\Imagine();
            $image = $imagine->open($path);
            $size = new Box(100, 100);
            $mode    = ImageInterface::THUMBNAIL_INSET;
            $content = $image
                ->thumbnail($size, $mode);
        $item->set($content);
dump($item);
            dump($content);

            dd(($this->cache->save($item)));
        }

        dd (($this->cache->getItem($key[2])));

    }

    public function create ($path)
    {
        $key = explode('/', $path);
dd($this->cache->getItem());
        $item = $this->cache->getItem($key[2]);dd($item);
        if (!$item->isHit()) {
            $newPath = "uploads/trick_images/thumbnails/".$key[2];
            $fileSystem = new Filesystem();
            $targetPath = sys_get_temp_dir().'/'.$key[2];

            $imagine = new \Imagine\Gd\Imagine();
            $image = $imagine->open($path);
            $size = new Box(100, 100);
            $mode = ImageInterface::THUMBNAIL_INSET;
            $content = $image
                ->thumbnail($size, $mode)
                ->save($newPath);
            $item->set($content);

            ($this->cache->save($item));

            //$fileSystem->copy(sys_get_temp_dir(), $targetPath, true );
            //$fileSystem->copy(__DIR__.'images/'.$key[2], $targetPath, true);
            //$this->uploadService->uploadTrickImage(new UploadedFile($targetPath, $key[2]));
            $this->cache->getItem($key[2]);
            }
        dd($item);
        dump($item->get()->show('jpg'));
dd($content);

            dd ($content->show('jpg'));

    }

}
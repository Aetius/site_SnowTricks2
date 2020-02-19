<?php


namespace App\Services\Video;


use App\Entity\Video;
use App\Form\Video\DTO\VideoDTO;
use Doctrine\ORM\EntityManagerInterface;

class VideoManager
{

    /**
     * @var EntityManagerInterface
     */
    private $EntityManagerInterface;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function cleanUrl(string $videoPath)
    {
        if (!strpbrk('https', $videoPath)) {
            str_replace('http://', 'https://', $videoPath);
        }
        if (preg_match('#^<iframe#', $videoPath)) {
            $array = explode(" ", $videoPath);
            foreach ($array as $value) {
                if (strstr($value, 'src=')) {
                    $value = str_replace("src=", "", $value);
                    $value = str_replace('"', "", $value);
                    return $value;
                }
            }
        }
        if (strpbrk('youtu', $videoPath)) {
            $videoPath = str_replace('youtu.be/', 'www.youtube.com/embed/', $videoPath);
            $videoPath = str_replace('www.youtube.com/watch?v=', 'www.youtube.com/embed/', $videoPath);
            return $videoPath;
        };

        if (strpbrk('dai', $videoPath)) {
            $videoPath = str_replace('dai.ly/', 'www.dailymotion.com/embed/video/', $videoPath);
            $videoPath = str_replace('www.dailymotion.com/video/', 'www.dailymotion.com/embed/video/', $videoPath);
            return $videoPath;
        };

        return null;

    }

    public function edit(Video $video, VideoDTO $DTO)
    {
        $video->setName($this->cleanUrl($DTO->name));
        $this->save($video);
    }

    public function delete(Video $video)
    {
        $this->entityManager->remove($video);
        $this->entityManager->flush();
    }

    private function save(Video $video)
    {
        $this->entityManager->persist($video);
        $this->entityManager->flush();
    }
}
<?php


namespace App\Form\Video\DTO;


use App\Entity\Video;
use App\Validator\VideoFormatOk;

class VideoDTO
{
    /**
     * @VideoFormatOk()
     */
    public $name;

    public $id;

    public $trick;


    static function createFromTrick(Video $video): VideoDTO
    {
        $dto = new VideoDTO();
        $dto->name = $video->getName();
        $dto->trick = $video->getTrick();
        $dto->id = $video->getId();
        return $dto;
    }
}
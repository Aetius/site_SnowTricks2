<?php


namespace App\Form\Trick\DTO;


use App\Entity\Trick;
use App\Validator\UniqueTrick;
use App\Validator\VideoFormatOk;
use Symfony\Component\Validator\Constraints as Assert;

class TrickDTO
{
    /**
     * @Assert\Length(min=5, max=100)
     * @Assert\NotNull()
     * @UniqueTrick()
     */
    public $title;


    /**
     * @Assert\Length(min=10)
     * @Assert\NotNull()
     */
    public $description;

    /**
     * @Assert\All({
     *     @Assert\Image(
     *     maxSize="3M")
     *     })
     */
    public $pictureFiles;

    /**
     *
     */
    public $id;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public $trickGroup;


    /**
     * @VideoFormatOk()
     */
    public $videos;


    static function createFromTrick(Trick $trick): TrickDTO
    {
        $dto = new TrickDTO();
        $dto->title = $trick->getTitle();
        $dto->description = $trick->getDescription();
        $dto->trickGroup = $trick->getTrickGroup();
        $dto->id = $trick->getId();
        return $dto;
    }

}
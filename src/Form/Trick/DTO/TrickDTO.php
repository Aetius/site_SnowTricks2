<?php


namespace App\Form\Trick\DTO;


use App\Entity\Trick;
use App\Validator\UniqueTrick;
use App\Validator\VideoFormatOk;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TrickDTO
{
    /**
     * @Assert\Length(min=5, max=100)
     * @UniqueTrick()
     */
    public $title;


    /**
     * @Assert\Length(min=10)
     */
    public $description;

    /**
     * @Assert\All({
     *     @Assert\Image(maxSize="3M")
     *     })
     */
    public $pictureFiles;

    /**
     *
     */
    public $id;

    /**
     * @Assert\NotBlank()
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